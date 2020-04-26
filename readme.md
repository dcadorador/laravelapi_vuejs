# FUSEDSYNC - MACHSHIPSYNC

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
