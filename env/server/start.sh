cd /code/
composer update
echo "Running web server on port 9092"
/usr/bin/php -S 0.0.0.0:9092 -t /code/root