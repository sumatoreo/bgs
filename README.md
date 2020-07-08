# Application

1) В hosts надо прописать 
```
127.0.0.1	bgs.test
```
2) docker-compose up -d
3) ./composer install
4) cd bgs; npm install; npm run production
5) ./artisan migrate
6) ./artisan test --filter=ApiTest
7) ./artisan db:seed --class=EventSeeder


# Консоль

```sh
docker-compose exec app bash
```