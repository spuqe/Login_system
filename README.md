# Login_system
Example of super secure login system using SQLFILL
Find SQLFILL documentation at: https://github.com/UnrealSecurity/SQLFill (now closed from public)

TLDR
SQLFill prevents SQL injection attacks by properly escaping user input.
See also https://github.com/UnrealSecurity/luxon-framework

# How to use? 
1. Change APP_SECRET from config.php
2. Add your server IP, name, password etc
3. Create SQL table
4. Add tables which in this case are ```username, email, passwd, salt, rip

# Code examples for SQLFILL
Example 1:
```php
<?php

    require_once 'sqlfill/sqlfill.php';
    $sql = new SQLFill($host, $user, $pass, $dbname, $port = 3306);

    $table  = 'Customers';
    $fields = ['CustomerName', 'ContactName', 'Address', 'City', 'PostalCode', 'Country'];
    $values = [
        ['Cardinal', 'Tom B. Erichsen', 'Skagen 21', 'Stavanger', '4006', 'Norway'],
        ['Cardinal', 'Tom B. Erichsen', 'Skagen 21', 'Stavanger', '4006', 'Norway']
    ];

    $result = $sql->fill('INSERT INTO $ $ VALUES ?', $table, $fields, $values)->query(); /* --> SQLFillResult */

?>
```
SQL query: ```sql
INSERT INTO Customers (CustomerName,ContactName,Address,City,PostalCode,Country) VALUES ('Cardinal','Tom B. Erichsen','Skagen 21','Stavanger','4006','Norway'),('Cardinal','Tom B. Erichsen','Skagen 21','Stavanger','4006','Norway')
```

Example 2:
```php
<?php

    require_once 'sqlfill/php/sqlfill.php';
    $sql = new SQLFill($host, $user, $pass, $dbname, $port = 3306);

    $result = $sql->fill('SELECT & FROM $ WHERE Country = ?', 
        ['FirstName', 'LastName'], 'Customers', 'FI')->query(); /* --> SQLFillResult */

?>
```
examples from https://github.com/UnrealSecurity/
SQL query: ```sql
SELECT FirstName, LastName FROM Customers WHERE Country = 'FI'
```
