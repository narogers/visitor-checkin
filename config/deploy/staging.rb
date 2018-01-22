set :stage, :staging
set :log_level, :info

# Peg to a previous commit until the server is ready for PHP 7
#set :branch, :master
set :branch, ENV.fetch('REVISION', 'master')
set :deploy_to, "/var/www/sites/libdrupal/apps/visitor-checkin"

set :laravel_server_user, "apache"

# Composer overrides
set :composer_install_flags, "--no-interaction --optimize-autoloader --prefer-dist"
set :composer_roles, :all
set :composer_dump_autoload, "--optimize"

# server-based syntax
# ======================
# Defines a single server with a list of roles ant
# multiple properties.
# You can define all roles on a single server, or split them:
server 'appdev02.clevelandart.org', user: 'nrogers', roles: %w{app db web}
