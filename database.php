<?php
$dsn = 'mysql:localhost;port=3306;dbname=register';
$username = 'root';
$password = '';

try{
    $db = new PDO($dsn, $username, $password);
}catch(Exception $e){
    echo $e->getMessage();
}
?>