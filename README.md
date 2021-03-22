# BileMo

[![Maintainability](https://api.codeclimate.com/v1/badges/c8a8c98216e32f2bb1c8/maintainability)](https://codeclimate.com/github/Eredost/BileMo/maintainability)

Seventh project of the OpenClassrooms PHP application developer path

This project consists of setting up an API in order to provide all platforms that wish it with access to the BileMo product catalog.
The application must be made in PHP using the Symfony framework.

## Installation

Before you can download the project you must first have a PHP version at least >=7.4, a recent version of Composer and the [Symfony CLI](https://symfony.com/download).

To set up the project, follow the steps below:

1. Clone the repository
2. Move your current directory to the root of the project
3. Perform the command:

        composer install
   
4. Create a new file ``.env.local`` in order to configure the DSN for the database.

        DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
   
5. Then you have to set up the database, associated tables and fixtures with the following commands:

        php bin/console doctrine:database:create
        php bin/console doctrine:migrations:migrate
        php bin/console doctrine:fixtures:load
   
6. Finally, you can launch the Symfony server with the following command:

        symfony serve

**And it's done !**

## Additional docs

-   [UML diagrams](diagrams)
