install:
	composer install

gendiff:
	./bin/gendiff

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

exam:
	php bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json

test:
	composer exec --verbose phpunit tests

