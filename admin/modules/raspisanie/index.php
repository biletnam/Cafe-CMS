<?php
defined('CAFE') or die (header ('Location: /'));


check_error ();



// Добавление маршрута
if ($_POST['add']) {

    $data = array(
        'appid'   => $_POST['appid'],
        'title'   => $_POST['title'],
        'period'  => $_POST['period'],
        'from_id' => $_POST['from-id'],
        'to_id'   => $_POST['to-id'],
        'type'    => $_POST['type']);

    $add_city = $db->query("INSERT " . DB_PREFIX . "_raspisanie SET ?u", $data);

    if ($add_city) {

        $message = 'Маршрут добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении маршрута';
    }
}



// Изменение маршрута
if ($_POST['update']) {

    $data = array(
        'appid'   => $_POST['appid'],
        'title'   => $_POST['title'],
        'period'  => $_POST['period'],
        'date'    => '0',
        'from_id' => $_POST['from-id'],
        'to_id'   => $_POST['to-id'],
        'type'    => $_POST['type']);

    // время последнего обновления кэша обнуляется (`date` = '0')
    $update_city = $db->query("UPDATE " . DB_PREFIX . "_raspisanie SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_city) {

        $message = 'Настройки маршрута обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении настроек маршрута';
    }
}



// удаление маршрута
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();



if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {

    if ($_GET['action'] == 'edit') {

        $row = $db->getRow("SELECT * FROM " . DB_PREFIX . "_raspisanie WHERE id=?i", $_GET['id']);
    }

    $tpl = "raspisanie_add_tpl.php";
}



// просмотр данных выбранного маршрута
if ($_GET['action'] == 'view') {

    get_raspisanie ($_GET['id']);

    $tpl = "raspisanie_view_tpl.php";
}




if ($_GET['action'] == 'list') {

    $limit = '10'; // количесвто результатов на страницу
    $sql_list = get_raspisanie_list($limit);
    $tpl = "raspisanie_list_tpl.php";
}



include "raspisanie_main_tpl.php";
?>
