# Fire Station Problem 🚒👩🏻‍🚒📞

this project is a CLI Simulation of a fire station, where there is incoming calls that are handles by the suitable employee according to the call priority.

## Technologies used 
this project was achieved using the **Laravel** Framework while leveraging laravel command through the implementation of Laravel **Actions**.

## Folder Structure 📁
in the `app` directory there are the following folders:

- `Actions`: which has all of our logic
- `Models`: which has our `Call` and `Employee` models
- `Enums`: Which has our `CallPriority` and `EmploymentLevel` enums

## Data Base Structure 🗃️
To implement the project I needed to create two tables:
### Calls 📞
```
 +--------------+-------------------+-------------------------------------------+
 |  Column Name |   Column Type     |   Description                             |
 +--------------+-------------------+-------------------------------------------+
 |  id          |   Primary Key     |   the identifier of the call              |
 |  name        |   varchar         |   Name of the caller                      |
 |  priority    |   enum(low,high)  |   the priority of the call                |
 |  duration    |   integer         |   the duration that the call will take    |
 +--------------+-------------------+-------------------------------------------+
```
### Employees 👩🏻‍🚒
```
 +--------------+-------------------+-------------------------------------------+
 |  Column Name |   Column Type     |   Description                             |
 +--------------+-------------------+-------------------------------------------+
 |  id          |   Primary Key     |   the identifier of the call              |
 |              |                   |                                           |
 |  name        |   varchar         |   Name of the caller                      |
 |              |                   |                                           |
 |  level       |   enum            |   the level of the employee               |
 |              |                   |   it contains the values (1 -> 4)         |
 |              |                   |   from junior -> director                 |
 |              |                   |                                           |
 |  duration    |   integer         |   the duration that the call will take    |
 |              |                   |                                           |
 |  call_id     |   Foriegn Key     |   the id of the call that the employee is |
 |              |                   |   currently handling, it is a nullable    |
 |              |                   |   and it indicates that the employee is   |
 |              |                   |   free when null and busy when not null   |
 +--------------+-------------------+-------------------------------------------+
```
<br>

> _I
made the level enum has numeric values to make the code to resolve which employee should handle the call more performant, this way I can get the free employees ordered by their level, makes resolving the suitable employee to take the call be achieved through only one database call._

## How to run the project 🚀
assuming you already have the php environment setup, including Xammp and Composer:

first clone the project
```shell
git clone https://github.com/Huda-sh/fire-station.git
```

navigate to the project root directory:
```shell
cd fire-station
```

install the dependencies:
```shell
composer install
```

run the database migrations:
```shell
php artisan migrate
```

### Running the test 🧪
to run the supplied tests for the project, in the root directory of the project run the following command:
```shell
./vendor/bin/phpunit --testdox
```

### Starting the project

_I have supplied a json file at the root of the project that contains the data of the employees, feel free to change the data in the file but without changing the json structure._

there are two commands to use when running the project:

1. one for dispatching new calls every second
```shell
php artisan call:dispatch
```
2. another one for displaying the simulation and the updates:
```shell
php artisan call:simulate
```
I recommend to open both commands in separate windows to have a more clear experience.
