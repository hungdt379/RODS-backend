## Deploy steps

### Setup Requirements

- Git
- Composer
- MongoDB

### Setup Project

- Clone source code
```
git clone https://git.ntq.solutions/hoai.tran/backend-apis-vieted.git
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
- Migrate tables
```
php artisan migrate
```
- Install passport
```
php artisan passport:install
```
- Fix passport MongoDB
```
php artisan fix:passport
```
- Run seeder to create user admin and fake data role - permissions
```
composer dump-autoload
php artisan db:seed
```
- Enjoy it!
