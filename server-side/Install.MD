# Install server-side, Step by step

## PHP packages

Install php packages as below (developed on ubuntu 18.04) 

apt -y install software-properties-common
add-apt-repository ppa:ondrej/php
apt update
apt install php7.4 php7.4-mysql php7.4-mbstring php7.4-xml php7.4-intl php7.4-gd php7.4-curl php7.4-imagick php7.4-gmp php7.4-curl php7.4-soap php7.4-bcmath\
    php7.4-xmlrpc php7.4-cli php7.4-zip
    
## MySQL 

you can create a new database and import the tables structures from the /server-side/sql/sql.sql

It's also recommended to create a mysql user for the database. You can use these commands on MySQL CLI: 

``` 
create database jobsity;
CREATE USER jobsity@jobsity IDENTIFIED BY 'jobsity1234';
GRANT ALL ON jobsity.* TO jobsity@localhost IDENTIFIED BY 'jobsity1234';
``` 

## Apache 

For slim framework used on this project, it's important to set this configuration on apache

``` 
<Directory /var/www/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
``` 

I also configured a virtual host for the server

``` 
<VirtualHost vm-jobsity:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/public
        ErrorLog ${APACHE_LOG_DIR}/js-error.log
        CustomLog ${APACHE_LOG_DIR}/js-access.log combined
</VirtualHost>
``` 

It's nothing out of the ordinary, but it's important to point out that DocumentRoot is supposed to point to this directory */var/www/html/public*

Slim framework also requires you to create a .htaccess on your public folder

````
# Redirect to front controller
RewriteEngine On
# RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
````
And also on the /var/www/html folder

````
RewriteEngine on
RewriteRule ^$ public/ [L]
RewriteRule (.*) public/$1 [L]
````
The idea behind those commands is that only the public folder is public on the webserver  :)

## composer libraries

You should run composer to install the following libraries

````
composer require slim/slim:"4.*"
composer require slim/psr7
composer require php-di/php-di
composer require phpunit/phpunit --dev

````

I didn't add the libraries above to the repository... 


## api

Clone the software from this repository to /var/www/html directory on your apache server.
Point server-side/public as the root of your webserver folder (see step above again).

The folder structure should look like this (https://odan.github.io/2019/11/05/slim4-tutorial.html#directory-structure)

````
/var/www/html
.
├── config/             Configuration files
├── public/             Web server files (DocumentRoot)
│   └── .htaccess       Apache redirect rules for the front controller
│   └── index.php       The front controller
├── templates/          Twig templates
├── src/                PHP source code (The App namespace)
├── tmp/                Temporary files (cache and logfiles)
├── vendor/             Reserved for composer
├── .htaccess           Internal redirect to the public/ directory
└── composer.json       Project dependencies and autoloader
````

Take a look into the config/settings.php for configuration of your database, in this section

````
// Database settings
$settings['db'] = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'username' => 'jobsity',
    'database' => 'jobsity',
    'password' => 'jobsity1234',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
````

You can also replace the API key for the currency conversors in the same file 

````
// Powered by Amdoren
$settings['amdoren_apikey'] = "cA6FwWkrAzb2D8HtRwMyjDXyae";

// Powered by Fixer.io
$settings['fixer_apikey'] = "81166643793e55ba840ccabbaf8";  
````
Amdoren only allowed me to use its API 10 times and then it locked me out  :(
I used fixer IO. It allows me 1000 requests per month on the free account


