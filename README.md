
# User Management Assessment - AnalyzenBD

Installation process & other necessary requirements for run the project.

## Requirements
- Need PHP 8.2 or above
- Need Composer 2


## Installation

Install project with composer

Clone the project
```bash
    git@github.com:Sultanul-Arefin/user-management-assessment-analyzenbd.git
```
For Running the project
```bash
  cd project-directory
  composer install
```
Next
- copy the .env file from .env.example
- generate the application key by
```bash
    php artisan key:generate
```
- setup the database
- run the migration by
```bash
    php artisan migrate
```
- run the seeder by
```bash
    php artisan db:seed
```
- If you want to skip above 2 commands, just run
```bash
    php artisan migrate:fresh --seed
```
- Finally, create a symlink to storage folder by running
```bash
    php artisan storage:link
```

    
## Running Tests

Before running the tests, update the phpunit.xml file 'DB_DATABASE' value to created DATABASE_NAME & then To run tests, run the following command

```bash
  php artisan test
```
## License
The MIT License (MIT). Please see
[MIT](https://choosealicense.com/licenses/mit/)
