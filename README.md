## About the project

A php artisan command for migrating data from a CSV file into a customers table.

## Installation
- Clone a repository `git clone git@github.com:bakhtiyor/obalor-test-assignment.git obalor-test-assignment` 
- Go to the folder where cloned the git repository `cd obalor-test-assignment`
- Install laravel project `composer install`
- Copy .env.example file and create a new .env file (`cp .env.example .env`)
- Set `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` parameters in a .env file
- Run `php artisan migrate` to create necessary tables
- Run `php artisan migrate:customer CSV_FILE_NAME` command where `CSV_FILE_NAME` is a path to your CSV file. You can find a sample CSV file (random.csv) ready to import in a public directory of the application.
- If some data in the CSV file doesn't match validation rules all rows containing errors are stored in a Excel file in a `public/migration-reports` folder

