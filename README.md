
# User Management Assessment - AnalyzenBD

Installation process & other necessary requirements for run the project.




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
- update the .env file
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

    
## Running Tests

Before running the tests, update the phpunit.xml file 'DB_DATABASE' value to created DATABASE_NAME & then To run tests, run the following command

```bash
  php artisan test
```
## License
The MIT License (MIT). Please see
[MIT](https://choosealicense.com/licenses/mit/)
