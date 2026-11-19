resource "laravel_cloud_application" "solitaire" {
  name                         = "solitaire"
  region                       = var.region
  cluster_id                   = var.cluster_id
  repository                   = var.repository
  source_control_provider_type = "github"
}

resource "laravel_cloud_environment" "main" {
  application_id = laravel_cloud_application.solitaire.id
  cluster_id     = var.cluster_id
  name           = "main"
  branch         = "main"
  database_id    = laravel_cloud_database.main.id

  php_version  = "8.4:1"
  node_version = "24"
  uses_octane  = false

  build_command = chomp(<<-EOT
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

    npm i -g bun
    bun install
    bun run build:ssr
  EOT
  )
  deploy_command = "php artisan migrate --force"

  uses_push_to_deploy = true
  uses_deploy_hook    = false

  cache_strategy                           = "default"
  firewall_block_path                      = false
  firewall_browser_integrity_check         = true
  response_headers_content_type            = "nosniff"
  response_headers_frame                   = "deny"
  response_headers_hsts_include_subdomains = false
  response_headers_hsts_preload            = false
  response_headers_robots_tag              = "index, follow"
}

resource "laravel_cloud_instance" "app" {
  environment_id                      = laravel_cloud_environment.main.id
  name                                = "App"
  type                                = "app"
  size                                = var.instance_size
  scaling_type                        = "custom"
  min_replicas                        = 2
  max_replicas                        = 4
  scaling_cpu_threshold_percentage    = 60
  scaling_memory_threshold_percentage = 60
  uses_scheduler                      = false
}

# Laravel MySQL has no cluster_id, so it runs on shared cloud even when the app
# is on a private cloud.
resource "laravel_cloud_database_cluster" "mysql" {
  name    = "solitaire"
  type    = "laravel_mysql"
  version = "8.4"
  region  = var.region

  create_default_database = false

  config = jsonencode({
    size                     = "mysql-flex-512mb"
    storage                  = 20
    is_public                = true
    uses_scheduled_snapshots = true
    retention_days           = 7
    suspend_seconds          = 300
  })
}

resource "laravel_cloud_database" "main" {
  cluster_id = laravel_cloud_database_cluster.mysql.id
  name       = "main"
}

# Creating this resource triggers a real deployment; later applies don't redeploy.
resource "laravel_cloud_deployment" "initial" {
  environment_id = laravel_cloud_environment.main.id

  depends_on = [
    laravel_cloud_instance.app,
  ]
}
