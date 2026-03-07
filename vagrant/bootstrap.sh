#!/usr/bin/env bash

apt-get update

#essentials
apt-get install software-properties-common curl vim git language-pack-en -y

#get latest php repo
add-apt-repository -y ppa:ondrej/php
apt-get update

#nginx
apt-get install -y nginx


#php
apt-get install -y php-pear
apt-get install -y php5.6
apt-get install -y php5.6-mcrypt
apt-get install -y php5.6-mysql
apt-get install -y php5.6-curl
apt-get install -y php5.6-fpm
apt-get install -y php5.6-xcache
apt-get install -y php5.6-gd
apt-get install -y php5.6-mbstring
apt-get install -y php5.6-xml
service php5.6-fpm restart

#sqlite
apt-get install sqlite3 libsqlite3-dev
apt-get install php5.6-sqlite

#mysql setup
echo mysql-server-5.6 mysql-server/root_password password PASSWORD | debconf-set-selections
echo mysql-server-5.6 mysql-server/root_password_again password PASSWORD | debconf-set-selections
apt-get install -y mysql-common mysql-server mysql-client

#mysql database user set up
echo "create database alchemis" | mysql -u root -pPASSWORD
echo "CREATE USER 'alchemis'@'%' IDENTIFIED BY 'rYT4maP7'"  | mysql -u root -pPASSWORD
echo "GRANT ALL ON *.* to 'alchemis'@'%'" | mysql -u root -pPASSWORD
echo "CREATE USER 'alchemis'@'localhost' IDENTIFIED BY 'rYT4maP7'"  | mysql -u root -pPASSWORD
echo "GRANT ALL ON *.* to 'alchemis'@'localhost'" | mysql -u root -pPASSWORD

#import the db
# mysql -u root -pPASSWORD alchemis < /vagrant/vagrant/import.sql

#link site file in vagrant to defualt directory
mkdir -p /var/www/html
ln -fs /vagrant /var/www/html/alchemis

# php setup
sed -i 's/;date.timezone =/date.timezone = Europe\/London/' /etc/php/5.6/fpm/php.ini
sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' /etc/php/5.6/fpm/php.ini
sed -i 's/request_order = "GP"/request_order = "GPC"/' /etc/php/5.6/fpm/php.ini
sed -i 's/; max_input_vars = 1000/max_input_vars = 10000/' /etc/php/5.6/fpm/php.ini
service php5.6-fpm restart

#nginx config
#sendfile virtual machine issue
sed -i 's/sendfile on/sendfile off/' /etc/nginx/nginx.conf
#virtualhost
rm /etc/nginx/sites-enabled/*
cp /vagrant/vagrant/virtualhost.conf /etc/nginx/sites-enabled/virtualhost.conf
service nginx restart

#composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
cd /var/www/html/alchemis && composer install

apt-get clean

pear upgrade --force --alldeps http://pear.php.net/get/PEAR-1.9.4