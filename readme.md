## Strucko - Open, Free, Comunity Driven, English-Croatian and Croatian-English IT Dictionary

The idea behind Strucko is to create open, free and 
community driven English-Croatian and Croatian-English IT dictionary. 
Anyone can register and contribute to the dictionary by suggesting new terms, 
translations and definitions for existing terms. 
While suggestions are visible to registered (and logged in) users, the 
user with administrator role can approve suggestions, which makes them publicly 
visible.

Terms can be entered in two languages - Croatian and English.
User chooses the language and part of speech for the term.
Administrator checks the suggestion, and if everything is OK, he approves the term.

In addition to English and Croatian language, Strucko has the ability to include
many more languages, which requires some source code modification. Strucko
can also include many more scientific fields, in addition to IT.

## Official Live Strucko Web App

Official Strucko web app is available here:  <http://strucko.com/>

## Installation and Usage
Although Strucko is not built in order to be installed, you can still 
run it on your own if you wish. Keep reading if you are interested...

Strucko is built using [Laravel 5.1 PHP Framework](http://laravel.com/docs/5.1/).
We used [MySQL](https://www.mysql.com/) for database storage, [Twitter Bootstrap](http://getbootstrap.com/)
as HTML, CSS, and JS framework and [jQuery](https://jquery.com/) for some specific JavaScript tasks.

To start using Strucko you'll have to have installed php >=5.5.9, mysql, composer, and npm.
Check [Laravel Homestead](http://laravel.com/docs/5.1/homestead) documentation on how to run Laravel apps or 
simply run your app on LAMP stack, or use PHP builtin web server.
If you use Netbeans IDE, take a look at [this article on how to easily run Laravel app in it](http://www.markoivancic.from.hr/2015/03/running-laravel-5-in-netbeans-8-locally-using-php-built-in-server.html).

In short, do this to get your copy of Strucko ready:
* Clone this repository: 
  * *git clone https://github.com/cicnavi/strucko.git some_name*
* In your shell/cmd go to your new repo folder and run commands to get all dependencies:
  * *composer install*
  * *npm install*
* In *public* folder, copy the 'htaccess-backup-laravel' file to '.htaccess' file (it is used by Apache web server.)
* Create a MySQL database which will be used by your new app (or you can use different database, 
check [Laravel database documentation](http://laravel.com/docs/5.1/database)).
* Create a .env file in your root folder (make a new copy from .env.example) and enter username and pass for your database. Reffer to 
[Environment Configuration](http://laravel.com/docs/5.1/installation#environment-configuration)
on how to do that. For example, in your .env file you should have the following:
  * *DB_HOST=localhost*
  * *DB_CONNECTION=mysql*
  * *DB_DATABASE=your_database_name*
  * *DB_USERNAME=your_database_username*
  * *DB_PASSWORD=your_database_password*
* Run command to generate your app key:
  * *php artisan key:generate*
* Run command to create needed tables in your database:
  * *php artisan migrate*
* Run command to seed your database with default languages,
scientific fields, part of speeches and default admin user 
(you'll define email and password for admin user in the next step).
  * *php artisan db:seed*
* The predefined email for admin user is 'admin@example.com'. You can change email and password for the admin user
by entering commands:
  * *php artisan tinker*
  * *$user = App\User::where('email', 'admin@example.com')->firstOrFail();*
  * *$user->email = 'your_email@domain.com';*
  * *$user->password = bcrypt('your_password');*
  * *$user->verified = 1;*
  * *$user->save();*
  * *exit*
* You can now run your new Strucko Dictionary. Make sure you review code in resources/views/layouts/master.blade.php, 
resources/views/layouts/right.blade.php and resources/views/layouts/header.blade.php (by default, we show Google ads on http://strucko.com).

## Contributing

Thank you for considering contributing to the Expert Dictionary project!
Consider using "Issues" and/or "Pull requests" feature on GitHub or 
send an e-mail to admin@strucko.com and tell us about your ideas.

## Security Vulnerabilities

If you discover a security vulnerability within Strucko, please use "Issues" and/or "Pull requests" 
feature on GitHub, or send an e-mail to admin@strucko.com and tell us about the problem.

## License

Strucko IT Dictionary is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)