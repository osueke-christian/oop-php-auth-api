# Vanilla PHP OOP Authentication API
> Author: [Osueke Christian](#)

## About Project
Here is a practice on setting up an authentication API with vanilla PHP (NO FRAMEWORK), using objected oriented programming in PHP.
A code challange by PATRICIA.COM.NG done in less than 2days.
My love for laravel informed my attempt to model the application using the MVC pattern and partially mirroring laravels way of doing things lol.

## Tools Used
1. **Composer:**
This was basically used to manage the few PHP dependencies used in the app (vlucas/phpdotenv), and to setup autoloads so we dont have to use includes().

2. **vlucas/phpdotenv:**
Used to load custom configuration variables that the application needs without having to modify .htaccess files or Apache/nginx virtual hosts (Basically anything that is likely to change between deployment environmen. In our case database credentials)

3. **PSR-4 Autoloader:**
Configured PSR-4 autoloader to automatically look for classes in the /app folder

4. **GIT:**
Basically helped me with versioning.

## Worflow
I tried to make the commit messages elaborate hence the commit history will throw some light on this.

## Steps to lunch and test the app
- Create a copy of ```.env.example``` and name is ```.env```
You can run this in terminal while in the project root folder 
```cp .env.example .env```

- Edit the ```.env``` file created from step 1 with your server and DB config parameters

- While in the _root_ directory of the project, run 
```_composer install_ ```
to install the dependencies (This step can be skipped since the vendor folder is not gitignored currently)

- Run the User table migration file located in ```./app/Database/Migration.php ```(cd into the path ```./app/Database/``` and run 
```php Migration.php```
in terminal). This will create the necessary table for this test.

- Start up your local server (XAMPP WAMP etc), OR in your terminal (still in the root directory of the application) run 
```php -S 127.0.0.1:9000 -t ./```

- You can now start testing the following endpoints:
    - **Account Creation** 
    ```
        Method: [POST]
        Endpoint: 127.0.0.1:9000/api/register
        Body params: {
            (String)name, (String)email, (String)password, (String)confirmed_password
        }
    ```
    - **User Login** 
    ```
        Method: [POST]
        Endpoint: 127.0.0.1:9000/api/login
        Body params: {
            (String)email, (String)password
        }
        > _Use the token in the returned response a Bearer token Authorization when making calls to authenticated endpoints like **_/user_**
    ```
    - **Get User** 
    ```
        Method: [GET]
        Endpoint: 127.0.0.1:9000/api/user
    ```
    - **User Logout** 
    ```
        Method: [GET]
        Endpoint: 127.0.0.1:9000/api/logout
    ``` 

- You can also use the ```**_postman.json_**``` collection inside the folder named ```_**test**_```, to easily test the endpoints with postman

**Note:** 
> When making POST requests via postman, make sure to **set the Body type to ```_raw_``` and from ```_Text_``` to ```_JSON_```**, then paste the payload in JSON format and set the content type to ```_JSON (application/json)_```.

## Suggestions for Continious Improvements
- Use JWT for token generation
- Create a validation trait for validation rules
- Force some model and controller specific implementations with interfaces
- Create a route class for managing all routes
- Setup proper route middlewares, as well as controller and method guards
- set email as a unique field in db
- Perform automated unit and e2e tests