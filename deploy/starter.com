<VirtualHost *:80>
	ServerName starter.com
	ServerAlias www.starter.com
	DocumentRoot /websites/starter.com/www

	<Directory /websites/starter.com/www>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride all
		Order allow,deny
		allow from all
	</Directory>
</VirtualHost>