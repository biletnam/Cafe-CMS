<?php
defined('CAFE') or die (header ('Location: /'));


check_error ();


// Добавляем нового пользователя
if ($_POST['add'] && empty ($error)) {

    $login = translit ($_POST['login']);

    $data = array(
        'login'    => $login,
        'password' => md5 ($_POST['password']),
        'reg_date' => timestamp  ($_POST['date']),
        'status'   => $_POST['status']);

    $add_user = $db->query("INSERT " . DB_PREFIX . "_users SET ?u", $data);


    if ($add_user) {

        $message = 'Пользователь добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении пользователя';
    }
}



// Изменение информации о пользователе
if ($_POST['update'] && empty ($error)) {

    $login = translit ($_POST['login']);

    $sqlpart = '';
    if ($_POST['password'] != '') {

        $sqlpart = $db->parse(", password = ?s", md5($_POST['password']));
    }

    $data = array(
        'login'    => $login,
        'reg_date' => timestamp  ($_POST['date']),
        'status'   => $_POST['status']);

    $update_user = $db->query("UPDATE " . DB_PREFIX . "_users SET ?u ?p WHERE id=?i", $data, $sqlpart, $_POST['id']);

    if ($update_user) {

        $message = 'Информация о пользователе обновлена';

    } else {

        $error = 'Возникла ошибка при обновлении информации о пользователе';
    }
}



// удаление пользователя
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();



// Вывод списка пользователей
if ($_GET['action'] == 'list' && empty ($error)) {

    $limit = '15';
    $user_list = get_users($limit);
    $user_status_array = array (0 => "Не активирован", "Администратор", "Модератор", "Пользователь");
    $tpl = "users_list_tpl.php";
}



// Добавление нового или изменение информации о существующем пользователе
if (($_GET['action'] == 'add' || $_GET['action'] == 'edit') && empty ($error)) {

    if ($_GET['action'] == 'edit' && isset ($_GET['id'])) {

        $row = get_user($_GET['id']);
    }

    $tpl = "users_add_tpl.php";
}



// Просмотр информации о пользователе
if ($_GET['action'] == 'view' && empty ($error)) {

    $user_row = get_user($_GET['id']);
    $user_status_array = array (0 => "Не активирован", "Администратор", "Модератор", "Пользователь");
    $tpl = "users_view_tpl.php";
}


      
include "users_main_tpl.php";
?>
