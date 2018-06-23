<?php
//use testdb
// tworzymy tabele users jesli nie istnieje
//CREATE TABLE IF NOT EXISTS `users` (`id` int(11) NOT NULL AUTO_INCREMENT,`username` varchar(32) NOT NULL,`password` varchar(32) NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `username` (`username`));
//jakis tam user adam adam
//INSERT INTO users SET users.username = 'adam', users.password = 'adam';
// tworzymy usera tylko do selecta i insera
//CREATE USER 'regLog'@'%' IDENTIFIED BY 'zaq12wsx';
//GRANT INSERT ON DATABASE.* TO 'regLog'@'%';
//GRANT SELECT ON DATABASE.* TO 'regLog'@'%';
//mysql -u tester -p

// jak sprawdzic port
// SHOW GLOBAL VARIABLES LIKE 'PORT';

    define('REDIS_SERVER','192.168.100.10');
    define('REDIS_PORT', 6379);
    define('REDIS_PASSWORD','zaq12wsx');

    define('DB_SERVER_MASTER', '192.168.100.10:3306');
    define('DB_SERVER_SLAVE', 'localhost:3306');
    define('DB_USERNAME', 'regLog');
    define('DB_PASSWORD', 'zaq12wsx');
    define('DB_DATABASE', 'testdb');
    
    
    $dbMaster = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if($dbMaster === false){
        die("ERROR(dbMaster): Could not connect. " . mysqli_connect_error());
    }

    $dbSlave = mysqli_connect(DB_SERVER_SLAVE,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    // Check connection
    if($dbSlave === false){
        die("ERROR(dbSlave): Could not connect. " . mysqli_connect_error());
    }





?>

