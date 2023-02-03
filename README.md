# Sdui News Task


### Installation (first time)

1. After cloning the repo
2. Open in cmd or terminal app and navigate to project folder
3. Run following commands

    ```
    composer install
    cp .env.example .env
    ```

4. Set your database information in `.env` & `.env.testing`, by adding the absolute path for `database\sdui_database.sqlite` & `database\sdui_test_database.sqlite`, and replace the values like this:
    ```
    DB_DATABASE=C:\laragon\www\sdui_news_task\database\sdui_database.sqlite
    ```

5. Run following commands

    ```
    php artisan key:generate
    php artisan migrate:fresh --seed
    ```

### Postman APIs
You can import the file (`Sdui_News_Task.postman_collection.json`) to postman and test the APIs,
but don't forget to change the variables [`baseUrl`, `token`] according to your information

To run the APIs, just make sure that you configured the `.env`, then you can either use the data that I already seed to it, or run a new migration and seed:
```
php artisan migrate:fresh --seed
```

### Auth
You can get any user email from the database, or import the postman file and execute `All Users` API.
Get any email, and use `password: password`, like this:

- email: `some@email.com` // which is from the above API
- password: `password`


### Delete Old news cron
I have added a jon in the task scheduling, so you need to run the worker for that, which you can see the list by executing:
    
```
php artisan schedule:list
```

OR, you can execute the command for deleting old news by:

```
php artisan delete:old-news
```

### Testing
First of all you have to be sure that you configured the `.env.testing`, then you can either use the data that I already seed to it, or run a new migration and seed:

```
php artisan --env=testing migrate:fresh --seed
```

Then, you can run tests by:

```
php artisan test
```
