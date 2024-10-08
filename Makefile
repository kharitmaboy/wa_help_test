build:
	docker compose build

run:
	docker compose up

exec-php:
	docker exec -it wa_help_test_php /bin/bash

exec-db:
	docker exec -it wa_help_test_db /bin/bash

stop:
	docker compose stop