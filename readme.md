# FUSEDSYNC - MACHSHIPSYNC
Specifications : https://fused.atlassian.net/wiki/spaces/MA/pages/4849783/FusedSync+Machship+Specification
Technical Workflow : https://fused.atlassian.net/wiki/spaces/MA/pages/153092127/FusedSync+MachShip+Technical+Workflow

## Project Installation

- clone this repo to your local
- run composer install - this is for laravel vendor packages
- run npm install - this is for vuejs node_module
- update your .env file adding 
API_DOMAIN=efs-integration.local
API_STANDARDS_TREE=x
API_SUBTYPE=core
API_PREFIX=api
API_VERSION=v1
API_DEBUG=true
API_STRICT=false
MIX_APP_URL=http://localhost:8000
EMAIL_ERRORS=errors@fusedsoftware.com

- run php artisan jwt:secret - this is to generate jwt key to be used in token
- run php artisan migrate


### CI Commands
**ci check all files** 
`$ ./vendor/bin/phpcs ./`

**ci fix all warnings / errors**
`$ ./vendor/bin/phpcbf ./`


### Test Seeders

**seed ACL**
`$ php artisan db:seed --class=IntegrationACLSeeder`
For testing ACL integration id must be 1

**seed DELF**
`$ php artisan db:seed --class=IntegrationDELFSeeder`
For testing DELF integration id must be 2

**seed D2G**
`$ php artisan db:seed --class=IntegrationD2GSeeder`
For testing D2G integration id must be 3

### Queue Job

`$ php artisan queue:work `
Open new terminal and run queue

### Helpers

**Machship cache initialization**
`$ php artisan init:machship_cache`

**Create Platform**
`$ php artisan make:platform {name}`

**Create Integration custom function**
`$ php artisan make:integration-custom-function {client : The ID of the Client\'s Account}`

**Dry Run Scheduler**
`$ php artisan schedule:run`