<?php
defined('CAFE') or die (header ('Location: /'));


check_error ();


// Добавление нового счетчика
if ($_POST['add'] && empty ($error)) {

    $data = array(
        'title'  => $_POST['title'],
        'date'   => mktime (),
        'code'   => $_POST['code'],
        'status' => $_POST['status']);

    $add_counter = $db->query("INSERT " . DB_PREFIX . "_stats SET ?u", $data);

    if ($add_counter) {

        $message = 'Счетчик добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении счетчика';
    }
}



// Измененяем данные счетчика
if ($_POST['update'] && empty ($error)) {

    $data = array(
        'title'  => $_POST['title'],
        'code'   => $_POST['code'],
        'status' => $_POST['status']);

    $update_counter = $db->query("UPDATE " . DB_PREFIX . "_stats SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_counter) {

        $message = 'Счетчик обновлен';

    } else {

        $error = 'Возникла ошибка при обновлении счетчика';
    }
}



// удаление счетчика
if ($_GET['action'] == 'delete') terminator ();
?>





<?php
// Вывод списка счетчиков
    if ($_GET['action'] == 'list' && empty ($error)) {

    $limit = '15';
    $counter_list = get_counters($limit);
    $status = array (0 => 'отключен', 'активен');
    $tpl = "stats_list_tpl.php";
}



// Добавление нового или изменение существующего счетчика
if ($_GET['action'] == 'add' || $_GET['action'] == 'edit' && empty ($error)) {

    // если выбрано изменение счетчика, делаем дополнительный запрос
    if ($_GET['action'] == 'edit' && isset ($_GET['id'])) {

        $counter = get_counter($_GET['id']);
    }

    $tpl = "stats_add_tpl.php";
}


        
include "stats_main_tpl.php";
?>
