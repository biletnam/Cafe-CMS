<?php
defined('CAFE') or die (header ('Location: /'));


check_error ();


// Добавление новой фотографии
if (isset($_POST['add']) && empty ($error)) {

    $data = array(
        'title'       => $_POST['title'],
        'description' => $_POST['description'],
        'date'        => timestamp  ($_POST['date']),
        'album'       => $_POST['album']);

    // Загружаем фотографию, уменьшаем и делаем квадратное превью
    file_upload  (array("jpeg","jpg","png"), "image/jpeg", "../upload/photo/original/" . timestamp  ($_POST['date']) . ".jpg");
    resize_pic   ($_FILES["file"]["tmp_name"], "800", "600", "../upload/photo/800-600/". timestamp  ($_POST['date']) .".jpg", "75");
    crop_preview ($_FILES["file"]["tmp_name"], "200", "../upload/photo/200-200/". timestamp  ($_POST['date']) .".jpg", "75");


    $add_photo = $db->query("INSERT " . DB_PREFIX . "_photos SET ?u", $data);

    if ($add_photo && $_FILES["file"]["error"] == 0) {

        $message = 'Фотография успешно добавлена';

    } else {

        $error = 'Возникла ошибка при добавлении фотографии';
    }
}



// Добавление нового альбома
if (isset($_POST['add_album']) && empty ($error)) {

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $data = array(
        'title'       => $_POST['title'],
        'description' => $_POST['description'],
        'url'         => preg_replace ("/[^a-z0-9-]/", "", $url));

    $add_album = $db->query("INSERT " . DB_PREFIX . "_albums SET ?u", $data);

    if ($add_album) {

        $message = 'Альбом успешно добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении альбома';
    }
}



// Обновление фото при изменении
if (isset($_POST['update']) && empty ($error)) {

    $data = array(
        'title'       => $_POST['title'],
        'description' => $_POST['description'],
        'album'       => $_POST['album']);

    $update_photo = $db->query("UPDATE " . DB_PREFIX . "_photos SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_photo) {

        $message = 'Фотография успешно изменена';

    } else {

        $error = 'Возникла ошибка при изменении фотографии';
    }
}



// Обновление альбом при изменении
if (isset($_POST['update_album']) && empty ($error)) {

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $data = array(
        'url'         => preg_replace ("/[^a-z0-9-]/", "", $url),
        'description' => $_POST['description'],
        'title'     => $_POST['title']);
    
    $update_album = $db->query("UPDATE " . DB_PREFIX . "_albums SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_album) {

        $message = 'Альбом успешно изменен';

    } else {

        $error = 'Возникла ошибка при изменении альбома';
    }
}



// удаление фотографии
if ($_GET['action'] == 'delete') terminator ();



// удаление альбома
if ($_GET['action'] == 'album_delete') {

    if ($_SESSION['status'] == '1') {

        $get_photo = $db->getAll("SELECT id FROM " . DB_PREFIX . "_photos WHERE album=?i", $_GET['id']);

        if ($get_photo) {

            $error = 'Этот альбом содержит фотографии. Удаление невозможно.';

        } else {

            $delete = $db->query("DELETE FROM " . DB_PREFIX . "_albums WHERE id=?i", $_GET['id']);

            if ($delete) {

                header ('Location: ?section=photos&action=album_list&del=ok');
            }
        }

    } else {

        log_write ('Не удалось удалить альбом: не достаточно прав', '0', '1');
        print_error ('Не достаточно прав для выполнения действия.');
    }
}

if ($_GET['del'] == 'ok') {$message = "Альбом удален";}



// выводим список фотографий
if ($_GET['action'] == 'list') {

    $limit = "12";
    $photo_list = get_photos($limit);
    $tpl = "photos_list_tpl.php";
}



// просмотр содержимого альбома (фотографий)
if ($_GET['action'] == 'album_view' && isset ($_GET['id']) && empty ($error)) {

    $limit = "12";
    $photo_list = get_album_photos ($_GET['id'], $limit);
    $tpl = "photos_album_view_tpl.php";
}



// Просмотр фотографии
if ($_GET['action'] == 'view' && empty ($error)) {

    $view_photo = get_photo ($_GET['id']);
    $tpl = "photos_view_tpl.php";
}



// добавление или изменение фотографии
if (($_GET['action'] == 'add' || $_GET['action'] == 'edit') && empty ($error)) {

    if ($_GET['action'] == 'edit' && isset ($_GET['id']) && empty ($error)) {

        $photo_list = get_photo ($_GET['id']);
    }

    $album_list = get_albums();
    $tpl = "photos_add_tpl.php";
}



// выводим список альбомов
if ($_GET['action'] == 'album_list') {

    $albums_list = get_albums();
    $root_album  = get_album_photos ('0');

    $tpl = "photos_album_list_tpl.php";
}



// добавление или изменение альбома
if (($_GET['action'] == 'album_edit' && isset ($_GET['id'])) || $_GET['action'] == 'album_add' && empty ($error)) {

    if ($_GET['action'] == 'album_edit') {

        $album_edit = get_album ($_GET['id']);
    }

    $tpl = "photos_album_add_tpl.php";
}



include "photos_main_tpl.php";
?>
