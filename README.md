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
4. Create table users, add columns which in this case are ```username, email, passwd, salt, rip```

```sql
CREATE TABLE users (
    username varchar(255),
    email varchar(255),
    passwd varchar(255),
    salt varchar(255),
    rip varchar(255)
);
```

# Code examples for SQLFILL
## Example 1
Code:
```php
<?php

    require_once 'sqlfill/php/sqlfill.php';
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
SQL query:
```sql
INSERT INTO Customers (CustomerName,ContactName,Address,City,PostalCode,Country) VALUES ('Cardinal','Tom B. Erichsen','Skagen 21','Stavanger','4006','Norway'),('Cardinal','Tom B. Erichsen','Skagen 21','Stavanger','4006','Norway')
```

## Example 2
Code:
```php
<?php

    require_once 'sqlfill/php/sqlfill.php';
    $sql = new SQLFill($host, $user, $pass, $dbname, $port = 3306);

    $result = $sql->fill('SELECT & FROM $ WHERE Country = ?', 
        ['FirstName', 'LastName'], 'Customers', 'FI')->query(); /* --> SQLFillResult */

?>
```
SQL query:
```sql
SELECT FirstName, LastName FROM Customers WHERE Country = 'FI'
```
