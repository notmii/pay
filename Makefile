build:
	docker-compose build

up:
	docker-compose up

down:
	docker-compose down

migrate:
	docker exec -it test-webapp php artisan migrate

phpunit:
	docker exec -it test-webapp php vendor/bin/phpunit --testdox

compute:
	docker exec -it test-webapp php artisan compute:commissions --csv=./input.csv
