
<!DOCTYPE html>
<html>
<body>



<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/Config/config.php';

    if (isset($_POST["formLogin"])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $result = $sql->fill("SELECT * FROM users WHERE username = ? LIMIT 1",
            $username)->query();

        $row = $result->fetch();
        
        $salt = $row["salt"];
        $hash = passwordHash($password, $salt);

        if ($hash === $row["passwd"])  {
            session_start();
            $_SESSION['loggedin'] = true;
            header("Location: /index.php");
            die();
        } else {
            echo "Not logged in";
        }

    }

    ?>
    <form method="POST">
    <table>
        <tbody>
            <tr>
                <td>Username:</td>
                <td><input type="username" name="username"></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="formLogin"></td>
            </tr>
        </tbody>
    </table>
</form>
<?php
?>



