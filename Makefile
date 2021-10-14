build:
	docker-compose build

up:
	docker-compose up

down:
	docker-compose down

migrate:
	docker exec -it paysera-webapp php artisan migrate

phpunit:
	docker exec -it paysera-webapp php vendor/bin/phpunit --testdox
