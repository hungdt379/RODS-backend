## Deploy steps

### Setup Requirements

- Git
- Composer
- MongoDB

### Setup Project

- Clone source code
```
git clone https://github.com/hungdt379/RODS-backend.git
```
- Create mongoDB database
- Copy `.env` file from `.env.example`
- Config `.env`, `Dockerfile`, `docker-compose.yml`, `dockerfiles/nginx/conf.d/app.conf`, `dockerfiles/php/local.ini`
- Enable php mongodb extension ssl (Linux)
```
sudo apt-get install -y autoconf pkg-config libssl-dev
```
- Install vendor packages
```
composer install (sudo docker-compose exec app composer install)
```
- Create keys
```
php artisan key:generate (sudo docker-compose exec app php artisan key:generate)
```

- Run seeder to create user admin and fake data role - permissions
```
php artisan db:seed
```
- Enjoy it!
