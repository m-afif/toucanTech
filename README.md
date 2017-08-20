Information      
========================

PHP version: PHP 5.6.25
Symfony version: Symfony 2.8.26

Pre-requisites
--------------

Before running this bundle : 

	1)	Symfony has to be installed on the server
	2)	Intall composer (https://getcomposer.org/download/)
	3)  Install de dependencies using the command:
			php composer.phar install
	3) 	Check that the database configuration in the file app/config/parameters.yml
	4)	Create the database using the following command:
			php app/console doctrine:database:create
	5)	Create the entities using the following commands:
			php app/console doctrine:schema:update --dump-sql
			php app/console doctrine:schema:update --force
