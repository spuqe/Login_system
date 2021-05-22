<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/Config/config.php';

    if (isset($_POST["formRegister"])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $username = $_POST['username'];

        $salt = passwordSalt();
        $hash = passwordHash($password, $salt);
        $rip = $_SERVER["REMOTE_ADDR"];

        $result = $sql->fill("INSERT INTO users $ VALUES ?",
            ["username", "email", "passwd", "salt", "rip"],
            [$username, $email, $hash, $salt, $rip])->query();
    }
?>
<title>Register</title>
<form method="POST">
    <table>
        <tbody>

           <tr>
                <td>Username:</td>
                <td><input type="username" name="username"></td>
            </tr>

            <tr>
                <td>Email:</td>
                <td><input type="email" name="email"></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="formRegister"></td>
            </tr>
        </tbody>
    </table>
</form>
