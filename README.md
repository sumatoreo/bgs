# В первую очередь
Надо создать отдельную подсеть

```sh
docker network create \
--driver=bridge \
--subnet=10.10.0.0/16 \
--gateway=10.10.0.1 \
--ip-range=10.10.88.0/24 \
br0
```

# Application

1) В hosts надо прописать 
```
127.0.0.1	bgs.test
```
2) ```docker-compose up -d```
3) ```./composer install```
4) ```./artisan migrate```
5) ```./artisan test --filter=ApiTest```
6) ```./artisan db:seed --class=EventSeeder```
7) ```cd bgs; npm install; npm run production```
8) http://bgs.test


# Консоль

```sh
docker-compose exec app bash
```

# Email 
Сохраняю в виде файла в storage/app/email-*

# Postman

Коллекция запросов для тестов
BGS.postman_collection.json