#!/usr/bin/env bash

sudo hostname flood-furniture-drive-dev

echo "--- Installing applications and setting them up ---"

echo ">> Updating packages list"
sudo apt-get update > /dev/null 2>&1

echo ">> Installing base packages"
sudo apt-get install -y build-essential vim curl python-software-properties wget pv libcurl4-openssl-dev pkg-config libssl-dev libsslcommon2-dev libpcre3-dev > /dev/null 2>&1
echo ">> Installing LAMP packages"
sudo apt-get install -y php5 apache2 php5-mcrypt mysql-server-5.5 php5-mysql php5-curl php5-gd > /dev/null 2>&1
echo ">> Installing MongoDB packages"
sudo apt-get install -y mongodb > /dev/null 2>&1

echo ">> Installing PHP-specific packages"
sudo apt-get install -y php5-xdebug php5-dev php-pear > /dev/null 2>&1

echo "xdebug.remote_connect_back = 1
xdebug.remote_enable = 1
xdebug.remote_handler = \"dbgp\"
xdebug.remote_port = 9000
xdebug.var_display_max_children = 512
xdebug.var_display_max_data = 1024
xdebug.var_display_max_depth = 10
xdebug.idekey = \"PHPSTORM\"" > /etc/php5/mods-available/xdebug.ini

sudo pear upgrade pear > /dev/null 2>&1
sudo pecl install -f xhprof > /dev/null 2>&1

sudo curl -sS https://getcomposer.org/installer > /dev/null 2>&1
sudo php --install-dir=/usr/local/bin --filename=composer > /dev/null 2>&1

echo "extension=xhprof.so
zend_extension=\"/usr/lib/php5/20100525+lfs/xdebug.so\"" >> /etc/php5/apache2/php.ini

echo ">> Installing MongoDB PHP driver"

sudo pecl install -f mongodb > /dev/null 2>&1

echo "extension=mongodb.so" >> /etc/php5/cli/php.ini
echo "extension=mongodb.so" >> /etc/php5/apache2/php.ini

echo ">> Installing MailCatcher-related packages"
sudo apt-get install -y libsqlite3-dev ruby1.9.1-dev > /dev/null 2>&1

echo ">> Installing MailCatcher"
sudo /usr/bin/gem install mailcatcher > /dev/null 2>&1

echo "description \"Mailcatcher\"

start on runlevel [2345]
stop on runlevel [!2345]

respawn

exec /usr/bin/env /usr/local/bin/mailcatcher --foreground --http-ip=0.0.0.0" > /etc/init/mailcatcher.conf

# Apache conf
# Apache conf files are in /etc/apache2/

echo ">> Enabling mod-rewrite"
sudo a2enmod rewrite > /dev/null 2>&1

echo ">> Setting up document root"
sudo rm -rf /var/www/html > /dev/null 2>&1
sudo ln -fs /vagrant /var/www/flood-furniture-drive > /dev/null 2>&1

echo ">> Copying Apache config file"
sudo cp /vagrant/vagrant_bootstrap/config-files/000-default.conf /etc/apache2/sites-available/000-default.conf

echo ">> Turn PHP errors on"
sudo sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php5/apache2/php.ini
sudo sed -i "s/display_errors = .*/display_errors = On/" /etc/php5/apache2/php.ini

echo ">> Restart apache"
sudo service apache2 restart > /dev/null 2>&1

echo "--- Setup complete ---"