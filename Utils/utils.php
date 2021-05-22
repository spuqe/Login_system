<?php

    function passwordHash($password, $salt) {
        return hash_hmac("sha256", $password, base64_decode($salt), false);
    }

    function passwordSalt() {
        return base64_encode(random_bytes(16));
    }


?>