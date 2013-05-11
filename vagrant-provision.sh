
apt-get update

# has to be the same as in config/database.yml
MYSQL_PASSWORD="password"

# if apache2 does no exist
if [ ! -f /etc/apache2/apache2.conf ];
then
  # Install MySQL
  echo "mysql-server-5.5 mysql-server/root_password password $MYSQL_PASSWORD" | debconf-set-selections
  echo "mysql-server-5.5 mysql-server/root_password_again password $MYSQL_PASSWORD" | debconf-set-selections
  apt-get -y install mysql-client mysql-server-5.5

  # Install Apache2
  apt-get -y install apache2

  # Install PHP5 support
  apt-get -y install php5 \
    libapache2-mod-php5 \
    php5-mysql \
    php5-curl \
    php5-xsl \
    php5-cli

  # Enable mod_rewrite
  a2enmod rewrite

  # Add www-data to vagrant group
  usermod -a -G vagrant www-data

  # run apache as vagrant user
  # rm -rf /var/lock/apache2
  # echo "export APACHE_RUN_USER=vagrant" >> /etc/apache2/envvars
  # echo "export APACHE_RUN_GROUP=vagrant" >> /etc/apache2/envvars

  # copy vhost file
  cp /vagrant/config/vagrant.vhost /etc/apache2/sites-enabled/forge

  # Restart services
  service apache2 restart

  # install moof_forge

  # create mysql database
  echo "CREATE DATABASE forge_mooforge;" | mysql -u root -p$MYSQL_PASSWORD

  # execute install scripts
  cd /vagrant/install
  ./permissions.sh
  ./create-db-tables.sh
  ./generate-db-classes.sh
  ./load-db-data.sh

fi

# Install git
if [ -z `which git` ];
then
  apt-get install -y git
fi
