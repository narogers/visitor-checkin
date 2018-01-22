namespace :libcma do
  desc "Stop application if running"
  task :down do
    on roles(:app, :web) do
      invoke "laravel:artisan", "down"
    end
  end

  desc "Start artisan"
  task :up do
    on roles(:app, :web) do
      invoke "laravel:artisan", "up"
    end
  end
end
