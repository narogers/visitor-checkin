set :stage, :production
set :branch, :master
# server-based syntax
# ======================
# Defines a single server with a list of roles and multiple properties.
# You can define all roles on a single server, or split them:

server 'lib-secundo.clevelandart.org', user: 'nrogers', roles: %w{app db web}, my_property: :my_value

namespace :deploy do
	desc 'Prepare resources for sharing'
	task :compile_assets do
		on roles(:app), in: :sequence, wait: 1 do
			execute "cp -r #{deploy_to}/../components/vendor #{release_path}"
		end
	end
end
	
end