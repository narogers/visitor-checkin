namespace :libcma do
  desc "Stop application if running"
  task :stop_artisan do
    on roles(:app, :web) do
      # Should test to see if artisan is running first but that is
      # for a later implementation
      info "Putting application into maintainance mode"
      if test("[ -f #{release_path}/artisan ]")
        invoke "laravel:artisan", "down"
      end
    end
  end

  desc "Start artisan"
  task :start_artisan do
    on roles(:app, :web) do
      invoke "laravel:artisan", "up"
    end
  end
end