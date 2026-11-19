# Solitaire

A solitaire card game built with Laravel 13, Inertia.js v3, Vue 3, and Tailwind CSS v4.

## Local Development Setup

### Prerequisites

Visit [php.new](https://php.new) to install the full Laravel development environment in one command. This sets up PHP, Composer, Laravel Herd, Node.js, and npm/bun.

### Getting Started

1. **Clone the repository:**

   ```bash
   git clone git@github.com:laravel-gtm/solitaire.git
   cd solitaire
   ```

2. **Install dependencies:**

   ```bash
   composer install
   bun install
   ```

3. **Configure environment:**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Set up the database:**

   The project defaults to SQLite for local development, which requires no additional configuration. To use MySQL instead, update your `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=solitaire
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   If using MySQL, create the database first:

   ```bash
   mysql -u root -e "CREATE DATABASE solitaire;"
   ```

5. **Run migrations:**

   ```bash
   php artisan migrate
   ```

6. **Build frontend assets:**

   ```bash
   bun run build
   ```

7. **Access the app:**

   With Laravel Herd, the site is automatically served at [http://solitaire.test](http://solitaire.test). No need to run a server manually.

   Alternatively, for development with hot reloading:

   ```bash
   composer run dev
   ```

## Deploying on Laravel Cloud

[Laravel Cloud](https://cloud.laravel.com/) is the recommended deployment platform for Laravel applications.

### 1. Create the Application

- Sign in at [cloud.laravel.com](https://cloud.laravel.com/) and select **+ New application**
- Authenticate with your Git provider (GitHub, GitLab, or Bitbucket)
- Select the repository, name your application, and choose a region
- Click **Create Application** — this creates a default environment

### 2. Add a MySQL Database

From your environment's infrastructure canvas:

- Select **Add database** and create a new MySQL cluster
- Choose an instance size and storage (5GB minimum)
- The database region must match your compute cluster's region
- Name the database within the cluster (e.g. `solitaire`)

Laravel Cloud automatically injects `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_DATABASE` into your environment when the database is attached.

### 3. Configure Build & Deploy Commands

Laravel Cloud separates **build commands** (run during image build) and **deploy commands** (run just before the deployment goes live).

**Build commands** — install dependencies and compile assets:

```
composer install --no-dev --optimize-autoloader
npm install -g bun
bun install
bun run build
php artisan optimize
```

> Laravel Cloud uses npm by default. Since this project uses bun, you need to install it first via `npm install -g bun`. See the [Cloud docs on using bun](https://cloud.laravel.com/docs/knowledge-base/using-yarn-bun-pnpm).

**Deploy commands** — run migrations:

```
php artisan migrate --force
```

> Note: Do not add `php artisan queue:restart` or `php artisan optimize:clear` to deploy commands — Cloud handles queue restarts automatically, and `optimize:clear` can cause cache issues.

### 4. Deploy

Push to your connected branch and Laravel Cloud will automatically build and deploy your application with zero downtime. Push-to-deploy is enabled by default.

You can also trigger deployments manually from the environment dashboard.

## Deploying with Terraform

The [`terraform/`](terraform/) directory creates the same setup (application, environment, App instance, and a Laravel MySQL database) using the [Laravel Cloud Terraform provider](https://github.com/laravel/terraform-provider-laravel).

### Prerequisites

- Terraform 1.5+ and the [Laravel Cloud CLI](https://github.com/laravel/cloud-cli)
- A GitHub connection in your Cloud organization with access to this repository

### 1. Authenticate

```bash
cloud auth
export LARAVEL_CLOUD_API_TOKEN=$(jq -r '.api_tokens[0]' ~/.config/cloud/config.json)
```

If you're logged in to more than one organization, run `cloud auth:token --list` and replace `0` with your organization's position in that list.

### 2. Configure (optional)

The defaults deploy to shared cloud in `us-east-1`. To change the region, or to deploy a fork with `repository`, copy the example and edit it:

```bash
cd terraform
cp terraform.tfvars.example terraform.tfvars
```

### 3. Deploy

```bash
terraform init
terraform apply
```

Provisioning the database cluster can take twenty minutes or more, and neither the cluster nor its database can be changed or deleted until it finishes. If an apply or destroy fails with **"This database cluster is already being updated"**, wait until the database shows as available in the Cloud dashboard and run it again.

If the first apply fails with **"The type value is invalid" on `laravel_cloud_instance.app`**, Cloud already created an App instance with the environment. Get its ID with `cloud instance:list <environment-id>`, then import it by creating `terraform/imports.tf`, and run `terraform apply` again:

```hcl
import {
  to = laravel_cloud_instance.app
  id = "inst-..."
}
```

Delete `imports.tf` once the apply succeeds.

### Deploying to a private cloud

In `terraform.tfvars`, set `region` to the private cloud's region, `cluster_id` to its `applications` cluster, and `instance_size` to a dedicated size such as `dedicated.g-1vcpu-4gb`. The database stays on shared cloud. To list your clusters:

```bash
curl -s -H "Authorization: Bearer $LARAVEL_CLOUD_API_TOKEN" -H "Accept: application/json" \
  https://cloud.laravel.com/api/dedicated-clusters \
  | jq -r '.data[] | [.id, .attributes.name, .attributes.type, .attributes.region] | @tsv'
```

### Notes

- `terraform.tfstate` contains database credentials and is git-ignored. Keep it: Terraform needs it to manage these resources.
- Set environment variables other than `APP_KEY` in the Cloud dashboard.
- `terraform destroy` deletes everything, including the database and its data. If the database is still provisioning, it can't be deleted yet; run the destroy again once it's available.
