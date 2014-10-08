### set install path
SITENAME=starter.com
INSTALLDIR=/websites/${SITENAME}

echo "###############################"
echo "### Updating and installing ###"
echo "###############################"

apt-get update

### install the basics
export DEBIAN_FRONTEND=noninteractive
apt-get install -y php5 php5-cli apache2 git curl mysql-server mysql-client php5-mysql vim memcached libapache2-mod-php5

### composer

echo "########################"
echo "### Composer install ###"
echo "########################"

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# clean up existing composer files
if [ -f ${INSTALLDIR}/composer.lock ]
	then
		rm ${INSTALLDIR}/composer.lock
fi
if [ -f ${INSTALLDIR}/vendor ]
	then
		rm -rf ${INSTALLDIR}/vendor
fi

cd ${INSTALLDIR}
composer install

### bower

echo "#####################"
echo "### Bower install ###"
echo "#####################"

apt-get install -y python-software-properties python g++ make
add-apt-repository ppa:chris-lea/node.js
apt-get update
apt-get install -y nodejs
apt-get autoremove

npm install -g bower
if [ -f ${INSTALLDIR}/www/static/vendor ]
	then
		rm -rf ${INSTALLDIR}/www/static/vendor
fi
cd ${INSTALLDIR}
bower --allow-root install

### mysql db setup

echo "########################"
echo "### Database install ###"
echo "########################"

if [ -f ${INSTALLDIR}/deploy/init.sql ]
	then
		mysql < ${INSTALLDIR}/deploy/init.sql
fi

### apache stuff

echo "######################"
echo "### Apache install ###"
echo "######################"

cp ${INSTALLDIR}/deploy/${SITENAME} /etc/apache2/sites-available/.
a2ensite ${SITENAME}
a2enmod rewrite
service apache2 restart