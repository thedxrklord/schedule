University Schedule Management

This project is a mini-implementation of university management system.
<br>The goal of the project is to easily manage the university schedule.

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
After it is done run:
php artisan migrate
````

After installation you can register and create your university<br>
App has featured like 'University Shared Access', when users can create and delete classtimes, but can't control your university structure.


