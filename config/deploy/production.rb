set :stage, :production
# Peg to a previous commit until the server is ready for PHP 7
#set :branch, :master
set :branch, ENV.fetch('REVISION', 'master')
set :deploy_to, '/home/web/www/library/apps/visitor-checkin'

set :laravel_server_user, "apache"
set :laravel_artisan_flags, "--env=production"

# Composer overrides
set :composer_install_flags, "--no-dev --no-interaction --optimize-autoloader --prefer-dist"
set :composer_roles, :all
set :composer_dump_autoload, "--optimize"

# Override the defaults which are for Laravel 4
set :file_permissions_paths, [
	"storage/",
	"storage/framework",
	"storage/framework/cache",
	"storage/framework/sessions",
	"storage/framework/views",
	"storage/logs"
]
set :file_permissions_users, ['apache', 'nrogers']
set :file_permissions_groups, %w{web}

# server-based syntax
# ======================
# Defines a single server with a list of roles ant
# multiple properties.
# You can define all roles on a single server, or split them:
server 'lib-secundo.clevelandart.org', user: 'nrogers', roles: %w{app db web}
