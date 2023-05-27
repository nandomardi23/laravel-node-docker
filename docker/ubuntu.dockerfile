FROM ubuntu:22.04

RUN apt-get update && apt-get upgrade -y

WORKDIR /var/www/lara-cashier

COPY ../src/* /var/www/lara-cashier

