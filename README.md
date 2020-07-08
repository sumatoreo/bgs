# Application

1) В hosts надо прописать 
```
127.0.0.1	bgs.test
```
2) docker-compose up -d
3) ./composer install
4) ./artisan migrate
5) ./artisan test --filter=ApiTest
6) ./artisan db:seed --class=EventSeeder
7) cd bgs; npm install; npm run production
8) http://bgs.test


# Консоль

```sh
docker-compose exec app bash
```

# Email 
Сохраняю в виде файла в storage/app/email-*