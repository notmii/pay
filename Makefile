build:
	docker-compose build

up:
	docker-compose up

down:
	docker-compose down

migrate:
	docker exec -it paycera-webapp php artisan migrate
