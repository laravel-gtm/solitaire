terraform {
  required_version = ">= 1.5"

  required_providers {
    laravel = {
      source  = "laravel/laravel"
      version = "~> 1.2"
    }
  }
}

provider "laravel" {}

variable "region" {
  description = "Region to deploy to, e.g. us-east-1. On a private cloud, its region."
  type        = string
  default     = "us-east-1"
}

variable "cluster_id" {
  description = "Private cloud applications cluster ID. Leave null for shared cloud."
  type        = string
  default     = null
}

variable "instance_size" {
  description = "App instance size. Private clouds need a dedicated size, e.g. dedicated.g-1vcpu-4gb."
  type        = string
  default     = "flex.c-1vcpu-512mb"
}

variable "repository" {
  description = "GitHub repository to deploy, as owner/name."
  type        = string
  default     = "laravel-gtm/solitaire"
}
