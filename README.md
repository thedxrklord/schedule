<h1 align="center">University Schedule Management</h1>
<p align="center">
This project is a mini-implementation of university management system.
<br>The goal of the project is to easily manage the university schedule.
</p>

<h1 align="center"><a href="https://github.com/thedxrklord/schedule/wiki">WIKI</a></h1>
<br>

Server requirements:
````
MySQL
PHP 7.4+
Apache2 (for nginx you need to change .htaccess)
````
Installation:
````
git clone https://github.com/thedxrklord/schedule.git
cd schedule
composer install
npm install && npm run production
copy .env.example .env
php artisan key:generate

Now, you need to replace db connection in .env file
Also, replace ADMINISTRATOR_EMAIL in the same file. 
It gives you some administrator functions
After it is done run:
php artisan migrate
````

After installation you can register and create your university.<br>
App has featured like 'University Shared Access', when users can create and delete classtimes, but can't control your university structure.


