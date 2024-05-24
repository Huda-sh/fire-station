# Fire Station Problem 🚒👩🏻‍🚒📞

This project simulates a fire station through a Command-Line Interface (CLI). It features incoming calls with varying priorities, and employees with different levels of experience who handle calls based on their urgency.

## Technologies used 
this project was achieved using the **Laravel** Framework while leveraging laravel command through the implementation of Laravel **Actions**.

## Folder Structure 📁
in the `app` directory there are the following folders:

- `Actions`: which has all of our logic
- `Models`: which has our `Call` and `Employee` models
- `Enums`: Which has our `CallPriority` and `EmploymentLevel` enums

## Data Base Structure 🗃️
The project uses two database tables:

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

> _Using numeric values for the level enum improves efficiency. I
made the level enum has numeric values to make the code to resolve which employee should handle the call more performant, this way I can get the free employees ordered by their level, makes resolving the suitable employee to take the call be achieved through only one database call._

## Running the Project 🚀
### Prerequisites

PHP environment with Xampp and Composer installed

### Steps
1. Clone the Project:
```shell
git clone https://github.com/Huda-sh/fire-station.git
```

2. Navigate to Project Directory:
```shell
cd fire-station
```

3. Install Dependencies:
```shell
composer install
```

4. Run Database Migrations:
```shell
php artisan migrate
```

### Testing 🧪
To run the provided unit tests:
```shell
./vendor/bin/phpunit --testdox
```

### Starting the project

_A sample JSON file containing employee data is included in the project root. You can modify the data within the file **(without changing the JSON structure).**_

The project provides two commands:

1. **Dispatch Calls (every second):**
```shell
php artisan call:dispatch
```
2. **Simulate and Display Updates:**
```shell
php artisan call:simulate
```
**Recommendation:** Run both commands in separate terminal windows for a clearer experience.
