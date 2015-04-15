FROM tutum/apache-php:latest

RUN echo -n "\ndeb http://archive.ubuntu.com/ubuntu/ trusty-backports main restricted\ndeb-src http://archive.ubuntu.com/ubuntu/ trusty-backports main restricted" >> /etc/apt/sources.list \
 && DEBIAN_FRONTEND=noninteractive apt-get update \
 && DEBIAN_FRONTEND=noninteractive apt-get install \
    php5-curl php5-cgi php5-gd php-pear php5-mcrypt \
    php5-sqlite php5-tidy php5-xmlrpc php5-xsl
