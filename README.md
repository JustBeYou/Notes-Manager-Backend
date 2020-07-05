Notes manager backend
===

- `chcon -Rt svirt_sandbox_file_t ./src/` - necessary only on Linux if SELinux is used
- `docker-compose up`
- `docker compose exec php php artisan migrate` - this is necessary only for the first run, so the DB schema will be created
