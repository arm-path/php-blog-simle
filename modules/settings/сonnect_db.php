<?php
require_once('modules/settings/settings.php');
$mysqli = new mysqli(
    $conf['settings-db']['hostname'],
    $conf['settings-db']['username'],
    $conf['settings-db']['password'],
    $conf['settings-db']['database']
);

if ($mysqli->connect_errno){
    echo 'Ошибка подключения к базе данных!';
    exit;
}

