# config valid only for current version of Capistrano
lock '3.9.1'

set :application, 'visitor_checkin'
set :repo_url, 'https://github.com/ClevelandMuseumArt/visitor-checkin'
set :laravel_version, 5.2

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default value for :scm is :git
#set :scm, :git

# Default value for :format is :pretty
set :format, :pretty

# Default value for :log_level is :debug
set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
set :linked_files, fetch(:linked_files, []).push('.env')
set :linked_dirs, fetch(:linked_dirs, []).push('storage/logs')
set :laravel_upload_dotenv_file_on_deploy, false

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 5

namespace :deploy do
  before :starting, :map_composer_command do
    on roles(:app) do |server|
      SSHKit.config.command_map[:composer] = "#{shared_path.join("composer.phar")}"
    end
  end

  after :starting, 'composer:install_executable'
  after :updating, "composer:install"
  after :updating, "laravel:migrate" 
end
