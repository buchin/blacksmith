<h1>Blacksmith</h1>
<p>The PHP deployment tool with support for Dojo scripts out of the box. Forked from deployer/deployer</p>

## Features

- Automatic server **provisioning**.
- **Zero downtime** deployments.
- Ready to use recipes for **most scripts**.

## Build

- Clone this repo
- `php -d phar.readonly=0 bin/build -v7.4.2-fix`
- `mv deployer.phar blacksmith`

## How to install dojo script with blacksmith
What you need:
1. Ubuntu 20.04 created with any VPS provider (DigitalOcean, Vultr, Contabo, etc)
2. Domain name (optional) with Registrar (for DNS Management)
3. Github/bitbucket/gitlab account
4. Blacksmith (download from podobejo.com/blacksmith)

### Preparing Repository
1. Create a repository for your source code (github, bitbucket etc).
2. Add your source code to repository
3. Commit and push

### Server provisioning
1. Put blacksmith in your local repository 
2. [Optional] If using domain / subdomain, add A record point it to server's IP address. If using IP address, you may skip this step.
3. Run `php blacksmith init && php blacksmith dojo:provision`, you can put domain name or IP as host. This will install PHP, MySQL, Caddy, etc in your server

### Code deployment
1. Run `php blacksmith deploy`

