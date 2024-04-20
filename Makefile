PORT ?= 8000

start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT) -t public

install:
	composer install

validate:
	composer validate

setup:
	cp -n .env.example .env || true
	touch database/database.sqlite
	composer install
	php artisan key:generate
	php artisan migrate
	npm install
	npm ci
	npm run build

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app routes tests database
	composer exec --verbose phpstan

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 app routes tests database

test:
	php artisan test

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
