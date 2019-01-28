# Run migrations (if any) on deploy
namespace :deploy do
  after :updated, "deploy:migrate"
  task :migrate do
    on roles(:db) do
      symfony_console('doctrine:migrations:migrate', '--no-interaction')
    end
  end
end
