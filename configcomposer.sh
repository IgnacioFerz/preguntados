sudo apt update
sudo apt install php-cli unzip
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
composer
sudo apt-get install php-xml
apt install docker-compose
cd ~/preguntados
composer install
