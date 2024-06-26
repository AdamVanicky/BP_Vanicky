<?php
$db = new PDO('mysql:host=host;dbname=dbname;charset=utf8', 'username', 'heslo do databáze');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


