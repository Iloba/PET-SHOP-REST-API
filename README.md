<h1>Pet Shop Rest Api</h1>
A restful Api for a petshop ecommerce store

<h2>Introduction</h2>
Petshop is an Ecommerce store where you can order for products and get them delivered to you instantly.

It consumes data from an API that was built with Laravel and PHP/Firebase-jwt to token signing and verification


<h2> Usage and Configuration </h2>
<h3>Database Configuration</h3>
Create a database (Sqlite, mysql or a database of your choice)

Then create a .env file, copy everything from the env.example file and then paste in the env file.
After that, update the .env file to correspond with the database credentials you just created

<pre>DB_DATABASE=name_of_database_you_created
DB_USERNAME=root</pre>



<h2>File Configuration </h2>


To install all the dependencies, run <pre>Composer install</pre> 


 To generate a new key for your project (this is usually stored in the .env file) run <pre>php artisan key:generate</pre>

<h2>Migrate your database and run seeders</h2>


To migrate your database and run all seeders, run <pre>php artisan migrate --seed</pre> 
The seeder will create an admin account with the following login credentials

<pre>
    Email: adminuser@example.com
    Password: password
</pre>

you can use this to signin as an admin

to start the app, run <pre>php artisan serve</pre> 


 
