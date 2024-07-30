# Test task

## Task:
- Use PHP as the primary programming language.
- Input: date, currency code, base currency code (default RUR)
- Get exchange rates from http://cbr.ru
- Output: exchange rate value and difference from the previous trading day
Cache data from http://cbr.ru
- Demonstrate skills in working with message brokers and implement data collection from cbr for the previous 180 days using a worker through a console command.
- 
## Description
Project was realized using PHP 8.2, Symfony, Rabbit, Redis, Nginx, Docker.
Api was documented in [api.yml](api.yml)

NOTE: all credentials was hard coded to simplifying. Trust me, i can do it. So you dont need to modify [.env](.env)

NOTE: there are no tests there because task takes enough time as is

## Init
Run Docker compose:
```shell
docker compose up --build -d
```
Install dependencies:
```shell
docker exec cbr-php-1 composer i
```
Setup rabbit queue and exchange:
```shell
docker exec cbr-php-1 bin/console rabbitmq:setup-fabric
```
Done. Project is ready

## Usage
API Request:
```shell
curl -X GET --location "http://localhost/rate?date=2024-07-25&currency_code=USD&base_currency_code=EUR"
```
Run consumer:
```shell
docker exec cbr-php-1 bin/console rabbitmq:consumer rates_history
```
Run command to fill up the queue:
```shell
docker exec cbr-php-1 bin/console warmup_rate_history_cache
```
You can control Rabbit queues in UI (guest/guest) http://localhost:15672