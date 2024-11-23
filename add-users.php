<?php
$username = 'admin';
$password = "maihu";
list($password, $error) = createUser($pdo, $username, $password);
        
$username = 'subodh';
$password = "randi";
list($password, $error) = createUser($pdo, $username, $password);
?>