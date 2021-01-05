<?php
require_once('settings.php');
$mysqli = new mysqli(
    $conf['conf-db']['hostname'],
    $conf['conf-db']['username'],
    $conf['conf-db']['password'],
    $conf['conf-db']['database']
);

if ($mysqli->connect_errno){
    echo 'Ошибка подключения к базе данных!';
    exit;
}

