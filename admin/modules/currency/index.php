<?php
defined('CAFE') or die (header ('Location: /'));


check_error ();


// Добавление валюты
if ($_POST['add']) {

    $data = array(
        'title'    => $_POST['title'],
        'code'     => $_POST['code'],
        'currency' => $_POST['currency'],
        'period'   => $_POST['period'],
        'cur_date' => $date,
        'nominal'  => $nominal,
        'rate'     => $rate);

    $add_currency = $db->query("INSERT INTO " . DB_PREFIX . "_currency SET ?u", $data);


    if ($add_currency) {

        $message = 'Валюта добавлена';

    } else {

        $error = 'Возникла ошибка при добавлении валюты';
    }
}



// удаление валюты
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();



if ($_GET['action'] == 'add') {

    $tpl = "currency_add_tpl.php";
}



if ($_GET['action'] == 'list') {

    if ($_GET['id']) {

        get_currency ($_GET['id']);
    }

    $limit = '10';
    $currency_list = get_currency_list($limit);
    $tpl = "currency_list_tpl.php";
}



include "currency_main_tpl.php";
?>
