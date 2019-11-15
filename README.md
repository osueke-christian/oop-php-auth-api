# About Project
Here is a practice on setting up an authentication API with vanilla PHP (NO FRAMEWORK), using objected oriented programming in PHP.
A code challange by PATRICIA.COM.NG done in less than 2days.

# Tools Used
- Composer
This was basically used to manage the few PHP dependencies used in the app (vlucas/phpdotenv), and to setup autoloads so we dont have to use includes().

- vlucas/phpdotenv
Used to load custom configuration variables that the application needs without having to modify .htaccess files or Apache/nginx virtual hosts (Basically anything that is likely to change between deployment environments – In our case database credentials)

- PSR-4 Autoloader
Configured PSR-4 autoloader to automatically look for classes in the /app folder

- GIT
Basically helped me with versioning.

# Worflow
I tried to make the commit messages elaborate hence the commit history will throw some light on this.

# Steps to lunch and test the app
- Create a copy of .env.example and name is .env 
    (You can run this in terminal while in the project root folder _cp .env.example .env_)
- Edit the .env file created from step 1 with your server and DB config parameters
- While in the root directory of the project, run _composer install_ to install the dependencies (Be sure you have composer installed on your system before doing this)
- Run the User table migration file located in ./app/Database/Migration.php (cd into the path _./app/Database/_ and run _php Migration.php_ in terminal). This will create the necessary table for this test.
- Start up your local server (XAMPP WAMP etc), or in your terminal run _php -S 127.0.0.1:9000 -t ./_ (still in the project root folder)
- You can now start testing the following endpoints:
    - *Account creation* [POST] - '127.0.0.1:9000/api/register' with payload {(String)name, (String)email, (String)password, (String)confirmed_password}
    - *Login* [POST] - '127.0.0.1:9000/api/login' with payload {(String)email, (String)password} (*Use the token returned as Authorization Bearer when making calls to authenticated endpoints like /user*)
    - *Get User* [GET] - '127.0.0.1:9000/api/user' 
- You can also use the postman.json collection inside the folder name test, to test the endpoints with postman

*Note:* _When making POST requests via postman, make sure to set the Body type to raw, then paste the payload in JSON format and set the content type to JSON (application/json)._

# Suggestions for Continious Improvements
- Use JWT for token generation
- Create a validation trait for validation rules
- Force some model and controller specific implementations with interfaces
- Create a route class for managing all routes
- Setup proper route middlewares, as well as controller and method guards
- set email as a unique field in db
- Perform automated unit and e2e tests