## Description

"Skindividual" is a fictional online shop created for a university team project. The shop sells tickets for events and individual appointments dealing with skincare and wellness in Germany. The website's backend is written in php using the Laravel framework and is built as a REST-API. <br><br>The frontend of the website and further information about the project can be found in the [skindividual-frontend repository](https://github.com/alexandrawaas/Skindividual-frontend).
<br>
<br>

## Database Model

The diagram below shows the relations between the model classes used in the project. 

<p align="center">
<img src="https://github.com/alexandrawaas/Skindividual-backend/blob/main/screenshots/Database.png" width="70%" padding="10px 10px 10px 10px">
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<br>

## How to use

1. Execute command `docker compose up`
2. Connect database:
    Username: skindividual      Password: 1234
3. Install php and composer if necessary
4. Execute command `composer install`
5. Execute command `php artisan migrate`
6. Start server by executing `php artisan serve`
<br>

By default, the database will be empty. Before starting the server, it is therefore recommended to execute these [example data INSERT statements](https://github.com/alexandrawaas/Skindividual-backend/blob/main/sql%20inserts/skindividual%20sql%20example%20data.txt) in the database's SQL console:
<br>
<br>

## Developers

This website was developed as a team project. I would like to say thanks to my fellow team members:

- Mai Linh Phung
- [Lia Schaarschmidt](https://github.com/Auriko10)
- [Marina Waller](https://github.com/marinaWaller)
<br>

## Contact

If you have any questions about this project, feel free to contact me. I will answer as fast as I can.
