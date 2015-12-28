<?php
defined('CAFE') or die (header ('Location: /'));


check_error ();


// Изменение позиций
if ($_POST['save_position'] && empty ($error)) {

    for ($i = 0; $i <= count($_POST['id']); $i++) {

        if (empty($_POST['position'][$i])){$_POST['position'][$i] = 0;}

        $update = $db->query ("UPDATE " . DB_PREFIX . "_pages SET position=?i WHERE id=?i", $_POST['position'][$i], $_POST['id'][$i]);

        if ($update) {

            $message = 'Позиции страниц успешно обновлены';

        } else {

            $error = 'Возникла ошибка при обновлении позиций';
        }
    }
}



// Добавление новой страницы
if (isset($_POST['add']) && empty ($error)) {

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $data = array (
        'date'          => timestamp  ($_POST['date']),
        'url'           => preg_replace ("/[^a-z0-9-]/", "", $url),
        'title'         => $_POST['title'],
        'text'          => $_POST['text'],
        'keywords'      => $_POST['keywords'],
        'description'   => $_POST['description'],
        'pid'           => $_POST['pid']);

    $add_page = $db->query ("INSERT INTO " . DB_PREFIX . "_pages SET ?u", $data);

    if ($add_page) {

        $message = 'Страница добавлена';

    } else {

        $error = 'Возникла ошибка при добавлении страницы';
    }
}



// Обновление содержимого страницы
if (isset($_POST['update']) && empty ($error)) {

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $data = array (
        'date'        => timestamp  ($_POST['date']),
        'url'         => preg_replace ("/[^a-z0-9-]/", "", $url),
        'title'       => $_POST['title'],
        'text'        => $_POST['text'],
        'keywords'    => $_POST['keywords'],
        'description' => $_POST['description'],
        'pid'         => $_POST['pid']);

    $update_page = $db->query ("UPDATE " . DB_PREFIX . "_pages SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_page) {

        $message = 'Содержимое страницы обновлено';

    } else {

        $error = 'Возникла ошибка при обновлении содержимого страницы';
    }
}



// удаление страницы
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();



// Просмотр страницы
if ($_GET['action'] == 'view' && isset ($_GET['id']) && empty ($error)) {

    $page = get_page ($_GET['id']);
    $tpl  = "page_view_tpl.php";
}



// Вывод списка страниц
if ($_GET['action'] == 'list' && empty ($error)) {

    $limit = "10";
    $page_list = get_page_list ($limit, $order);
    $tpl = "page_list_tpl.php";
}



// Добавление новой или изменение существующей страницы
if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {

    $action_title = "Добавление новой ";

    if ($_GET['action'] == 'edit' && isset ($_GET['id'])) {

        $edit_page = get_page ($_GET['id']);
        $action_title = "Изменение ";
    }

    $tpl = "page_add_tpl.php";
}



include "page_main_tpl.php";
?>
