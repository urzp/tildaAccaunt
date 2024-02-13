<?php
    $host = 'mysql';
    $db   = 'main_database';
    $user = 'root';
    $pass = 'parolik9182123';
    $charset = 'utf8';
    
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    $pdo = new PDO($dsn, $user, $pass, $opt);
?>