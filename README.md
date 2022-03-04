# Hassner

Hassner is a service that allows user to invest in their favourite artists and raise capital

`/backend` contains logic of all SQL query functions, their blueprints and definitions as well as handling data input and output.

`/frontend` contains graphics and other display pages this project

`/database` contains all database content files and scripts

To run this project:;
1. Download xampp at (suggested: https://www.apachefriends.org/download.html)
2. Install xampp (normally recommended default directory in C:/xampp)
3. choose any directory on computer, git clone https://github.com/martinha1909/Hassner.git
4. Copy the Hassner file and paste it in C:/xampp/htdocs
5. Run server by opening xampp control at C:/xampp/xampp-control. Once the console pops up, start Apache and MySQL
6. Start database by going to any web browser and type localhost:80/phpmyadmin
7. On the left column select "New" option and create a database, name it "hassner"
8. Click on the newly created hassner database, then click import, select `database/hassner.sql`then select "Go"
9. Run the project, starting the landing page by typing localhost:80/Hassner/frontend/credentials/index.php

The database found in hassner.sql is just an example database, feel free to add or remove any tuples in any tables

Notes on the database
1.  In column "Shares" of the "account" table, if

    a. account type is "user", then it means the total amount of shares that has been bought by that user       accross all artists.
    
    b. account type id "artist", then it means the total amount of shares owned by that artist that has been bought throughout all users in the platform. In other words, the amount of share available for purchase of that artist would be equivalent to the "Share_Distributed" column value minus "Shares" column value. (any artist Available Shares = Share_Distributed - Shares)

2. In `sell_order` and `buy_order` tables, the following columns have these meanings:
    - `sell_limit` in `sell_order` will represent the limit of the sell order, which means the order will be automatically sold if the price hits <b>AT LEAST</b> the limit value
    -  `sell_stop` in `sell_order` will represent the stop of the sell order, which means the order will be automatically sold if the price is <b>AT MOST</b> the stop value
    -  `buy_limit` in `buy_order` will represent the limit of the buy order, which means the order will be automatically bought if the price is <b>AT MOST</b> the limit value
    -  `buy_stop` in `buy_order` will represent the stop of the buy order, which means the order will be automatically bought as soon as the price hits <b>AT LEAST</b> the limit value
    - limit and stop of `buy_order` and `sell_order` are switched, this helps execution a little bit easier to understand since only limit buy orders could match with limit sell orders and only stop buy orders could match with stop sell orders.

3. The balance `column` under `account` table in the database indicate a users' account balance, in USD. 

4. To delete database (for importing purposes): go to web browser and run localhost:80/Hassner/database/        deleteDatabase.php

5. To clean database (resetting values to 0): go to web browser and run localhost:80/Hassner/database/        CleanDatabase.php


## Live page
Currently on https://35.89.4.89/ soon to be https://hassner.ca/

To access the server as dev (recommend using putty):
- under Connection/SSH/auth. add your private key, which can be done by doing the following to generate ssh key pair
    - in any terminal, do `ssh-keygen -t -ecdsa -b 384` (384 bits encryption key) (Note: this only works if you have a Linux shell terminal or a MacOS shell terminal)
    - Enter location where the keys are saved (can leave empty and use the suggested path (`~/. ssh`))
    - Enter passphrase when prompted (can be empty)
    - `cat ~/.ssh/id_rsa` to access private key
- then under Connection/SSH/tunnels, add the following
    - Source port: 8888
    - Destination: localhost:80
- Under Session tab on the left, Host name: 35.89.4.89 
- Click open then a log in window will appear, type bitnami. 
- Navigate to your browser, then put http://127.0.0.1:8888/phpmyadmin 
- Contact me for server's credentials after navigating to that link

## Unit test
Currently `PHPUnit` is used to run the unit tests on this project. `PHPUnit` parameters and test runners are specified in `phpunit.xml`
</br>
To run the unit tests, do `./vendor/bin/phpunit`
