.PHONY: start stop shell install migrate seed test

start:
	docker-compose up -d

stop:
	docker-compose down

shell:
	docker-compose exec php bash

install:
	docker-compose exec php composer install

migrate:
	docker-compose exec php php bin/console doctrine:migrations:migrate

seed:
	docker-compose exec php php bin/console app:fixtures:load

test:
	docker-compose exec php php bin/phpunit
