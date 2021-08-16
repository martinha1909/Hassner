# Hassner

Hassner is a service that allows user to invest in their favourite artists and raise capital

Sources:
- landing page template (https://webresourcesdepot.com/freebie/knight/#download)


in APIs folder contains logic of all SQL query functions, their blueprints and definitions as well as handling data input and output.
frontend contains graphics and other display pages this project

To run this project:;
1. Download xampp at (suggested: https://www.apachefriends.org/download.html)
2. Install xampp (normally recommended default directory in C:/xampp)
3. choose any directory on computer, git clone https://github.com/martinha1909/Hassner.git
4. Copy the Hassner file and paste it in C:/xampp/htdocs
5. Run server by opening xampp control at C:/xampp/xampp-control. Once the console pops up, start Apache and MySQL
6. Start database by going to any web browser and type localhost:80/phpmyadmin
7. Create a database by importing the hassner.sql file in the repository
8. Run the project, starting the landing page by typing localhost:80/Hassner/frontend/credentials/index.php

The database found in hassner.sql is just an example database, feel free to add or remove any tuples in any tables

Notes on the database
1.  In column "Shares" of the "account" table, if

    a. account type is "user", then it means the total amount of shares that has been bought by that user       accross all artists.
    
    b. account type id "artist", then it means the total amount of shares owned by that artist that has been bought throughout all users in the platform. In other words, the amount of share available for purchase of that artist would be equivalent to the "Share_Distributed" column value minus "Shares" column value. (any artist Available Shares = Share_Distributed - Shares)