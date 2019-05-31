FROM ubuntu:bionic

MAINTAINER AryanSadeghi

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    php7.2 php7.2-cli php7.2-gd php7.2-mbstring php7.2-mysql \
    php7.2-opcache php7.2-odbc php7.2-xml php-memcached composer \
    php7.2-zip

RUN DEBIAN_FRONTEND=noninteractive apt-get install -y php-apcu php7.2-curl curl

VOLUME /code
EXPOSE 9092
COPY ./start.sh /start/start.sh
CMD ["/bin/bash", "/start/start.sh"]