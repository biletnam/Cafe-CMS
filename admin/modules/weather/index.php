<?php
defined('CAFE') or die (header ('Location: /'));


check_error ();



// Добавление города
if ($_POST['add']) {

    $data = array(
        'appid'   => $_POST['appid'],
        'period'  => $_POST['period'],
        'units'   => $_POST['units'],
        'title'   => $_POST['title'],
        'city_id' => $_POST['city-id']);

    $add_city = $db->query("INSERT " . DB_PREFIX . "_weather SET ?u", $data);

    if ($add_city) {

        $message = 'Город добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении города';
    }
}


// Изменение города
if ($_POST['update']) {

    $data = array(
        'appid'   => $_POST['appid'],
        'period'  => $_POST['period'],
        'units'   => $_POST['units'],
        'title'   => $_POST['title'],
        'city_id' => $_POST['city-id']);

    $update_city = $db->query("UPDATE " . DB_PREFIX . "_weather SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_city) {

        $message = 'Настройки города обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении настроек города';
    }
}



// удаление города
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();



if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {

    if ($_GET['action'] == 'edit') {

        $sql_list = $db->getRow("SELECT * FROM " . DB_PREFIX . "_weather WHERE id=?i", $_GET['id']);
    }

    $tpl = "weather_add_tpl.php";
}



// просмотр данных выбранного города
if ($_GET['action'] == 'view') {

    get_weather($_GET['id']);
    $tpl = "weather_view_tpl.php";
}



if ($_GET['action'] == 'list') {

    $limit = '10';
    $sql_array = get_weather_list($limit);
    $tpl = "weather_list_tpl.php";
}


      
include "weather_main_tpl.php";
?>
