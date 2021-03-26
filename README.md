# BileMo

[![Maintainability](https://api.codeclimate.com/v1/badges/c8a8c98216e32f2bb1c8/maintainability)](https://codeclimate.com/github/Eredost/BileMo/maintainability)

Seventh project of the OpenClassrooms PHP application developer path

This project consists of setting up an API in order to provide all platforms
that wish it with access to the BileMo product catalog.
The API must be made in PHP using the Symfony framework and must follow the rules of Level 1, 2 and 3 of the Richardson Maturity Model.

## Installation

Before you can download the project you must first have a PHP version
at least >=7.4 with openssl extension, a recent version of Composer and the
[Symfony CLI](https://symfony.com/download).

To set up the project, follow the steps below:

1. Clone the repository
2. Move your current directory to the root of the project
3. Perform the command:

   ```shell
   composer install
   ```

4. Create a new file ``.env.local`` in order to configure the DSN for the database.

   ```
   DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
   ```

5. Then you have to set up the database, associated tables and fixtures
   with the following commands:

   ```shell
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:fixtures:load
   ```

6. Henceforth, you need to generate the SSL keys for JWT authentication:

   ```shell
   php bin/console lexik:jwt:generate-keypair
   ```

7. Finally, you can launch the Symfony server with the following command:

   ```shell
   symfony serve
   ```

**And it's done !**

## Additional docs

- [UML diagrams](diagrams)
