<?php
// TODO убрать/переделать
// выбираем список категорий в зависимости от раздела
// используется для аякс-запроса при добавлении записи
if (!empty ($_POST['category_list'])) {

    include $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/functions.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/mysql.class.php';

    check_install(); // проверяем, установлена ли cms
    $db = new SafeMySQL(array('user' => DB_LOGIN, 'pass' => DB_PASSWORD, 'db' => DB_NAME, 'charset' => 'utf8'));

    $rows = $db->getAll('SELECT * FROM ' . DB_PREFIX . '_catalog_subcategories WHERE pid=?i', $_POST['category_list']);

    foreach ($rows as $rows2) {

        echo '<option value="' . $rows2['id'] . '">' . $rows2['title'] . '</option>';
    }

    exit;
}



defined('CAFE') or die (header ('Location: /'));


check_error ();


include 'config.php';



// сохранение настроек
if ($_POST['update-settings']) {

    $w_string = '<?php
define ("DEFAULT_CITY",   "' . $_POST['default-city'] . '"); // город по умолчанию
define ("DEFAULT_COORD",  "' . $_POST['default-coord'] . '"); // начальные координаты
?>';


    $fop = fopen ($_SERVER["DOCUMENT_ROOT"] . 'admin/modules/catalog/config.php', 'w');

    if ($fwr = fwrite ($fop, $w_string)) {

        fclose ($fop);
        $message = 'Настройки обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении настроек';
    }
}



// Добавление организации
if ($_POST['add']) {

    $filename = mktime () . '.jpg';

    $data = array(
        'title'       => $_POST['title'],
        'url'         => translit ($_POST['title']),
        'form'        => $_POST['form'],
        'type'        => $_POST['type'],
        'category'    => $_POST['category'],
        'boss'        => $_POST['boss'],
        'description' => $_POST['description'],
        'file'        => $filename,
        'phone'       => $_POST['phone'],
        'phone2'      => $_POST['phone2'],
        'fax'         => $_POST['fax'],
        'email'       => $_POST['email'],
        'www'         => $_POST['www'],
        'city'        => $_POST['city'],
        'street'      => $_POST['street'],
        'build'       => $_POST['build'],
        'lat'         => explode(', ', $_POST['coord'])['0'],
        'lon'         => explode(', ', $_POST['coord'])['1']);

    if (!empty ($_FILES["file"]["name"])) {

        file_upload  (array("jpeg","jpg"), "image/jpeg", "../upload/catalog/original/" . $filename);
        resize_pic   ($_FILES["file"]["tmp_name"], "800", "600", "../upload/catalog/800-600/". $filename);
        crop_preview ($_FILES["file"]["tmp_name"], "200", "../upload/catalog/200-200/". $filename);
    }


    $add_catalog = $db->query("INSERT INTO " . DB_PREFIX . "_catalog SET ?u", $data);

    if ($add_catalog && $_FILES["file"]["error"] == 0) {

        $message = 'Организация добавлена';

    } else {

        $error = 'Возникла ошибка при добавлении организации';
    }
}



// Изменение организации
if ($_POST['update']) {

    $filename = mktime () . '.jpg';
    $sqlpart  = '';

    if (!empty($_FILES["file"]["name"])) {

        $sqlpart = $db->parse(" file='" . $filename."',");
    }

    $data = array(
        'title'       => $_POST['title'],
        'url'         => translit ($_POST['title']),
        'form'        => $_POST['form'],
        'type'        => $_POST['type'],
        'category'    => $_POST['category'],
        'boss'        => $_POST['boss'],
        'description' => $_POST['description'],
        'phone'       => $_POST['phone'],
        'phone2'      => $_POST['phone2'],
        'fax'         => $_POST['fax'],
        'email'       => $_POST['fax'],
        'www'         => $_POST['www'],
        'city'        => $_POST['city'],
        'street'      => $_POST['street'],
        'build'       => $_POST['build'],
        'lat'         => explode(', ', $_POST['coord'])['0'],
        'lon'         => explode(', ', $_POST['coord'])['1']);

    $update_catalog = $db->query("UPDATE " . DB_PREFIX . "_catalog SET ?p ?u WHERE id=?i", $sqlpart, $data, $_POST['id']);

    if (!empty ($_FILES["file"]["name"])) {

        file_upload  (array("jpeg","jpg"), "image/jpeg", "../upload/catalog/original/" . mktime () . ".jpg");
        resize_pic   ($_FILES["file"]["tmp_name"], "800", "600", "../upload/catalog/800-600/". mktime () .".jpg", "75");
        crop_preview ($_FILES["file"]["tmp_name"], "200", "../upload/catalog/200-200/". mktime () .".jpg", "75");
    }


    if ($update_catalog) {

        $message = 'Данные организации обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении данных организации';
    }
}



// удаление организации
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();



// Добавление нового раздела
if ($_POST['add_category'] && empty ($error)) {

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $data = array(
        'title' => $_POST['title'],
        'url'   => preg_replace ("/[^a-z0-9-]/", "", $url));

    $add_category = $db->query("INSERT INTO " . DB_PREFIX . "_catalog_categories SET ?u", $data);

    if ($add_category) {

        $message = 'Раздел добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении раздела';
    }
}



// Измененяем раздел
if ($_POST['update_category'] && empty ($error)) {

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $data = array(
        'title' => $_POST['title'],
        'url'   => preg_replace ("/[^a-z0-9-]/", "", $url));

    $update_category = $db->query("UPDATE " . DB_PREFIX . "_catalog_categories SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_category) {

        $message = 'Раздел обновлен';

    } else {

        $error = 'Возникла ошибка при обновлении раздела';
    }
}



// удаление раздела TODO переделать
if ($_GET['action'] == 'delete_category') {

    if ($_SESSION['status'] == '1') {

        $delete = $db->query("DELETE FROM " . DB_PREFIX . "_catalog_categories WHERE id=?i", $_GET['id']);

        if ($delete) {

            header ('Location: ?section=catalog&action=category&msg=del');

        } else {

            print_error ('При удалении записи возникла ошибка: ' . mysql_errno() . ': ' . mysql_error () . '.');
        }

    } else {

        log_write ('Не удалось удалить запись: не достаточно прав', '0', '1');
        print_error ('Не достаточно прав для выполнения действия.');
    }
}



// Добавление категорий разделу
if ($_POST['add_subcategory'] && empty ($error)) {

	for ($i=0; $i<=(count($_POST['title'])-1); $i++) {

        (empty ($_POST['url'][$i])) ? ($url = translit ($_POST['title'][$i])) : ($url = translit ($_POST['url'][$i]));

        $data = array(
            'pid'      => $_POST['pid'],
            'title'    => $_POST['title'][$i],
            'url'      => preg_replace ("/[^a-z0-9-]/", "", $url),
            'position' => $_POST['position'][$i]);

        $add_subcategory = $db->query("INSERT INTO " . DB_PREFIX . "_catalog_subcategories SET ?u", $data);

        if ($add_subcategory) {

            $message = 'Категории добавлены';

        } else {

            $error = 'Возникла ошибка при добавлении категорий';
        }
    }
}



// выводим форму добавления/изменения организации
if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {

    // если изменение - делаем запрос в бд
    if ($_GET['action'] == 'edit') {

        $row = get_catalog_item($_GET['id']);
    }

    $tpl = "catalog_add_tpl.php";
}



// просмотр данных выбранной организации
if ($_GET['action'] == 'view') {

    $catalog_view = get_catalog_item($_GET['id']);
    
    $tpl = "catalog_view_tpl.php";
}



// вывод списка организаций
if ($_GET['action'] == 'list') {

    $limit = '10';
    $catalog_list = get_catalog_list($limit);
    $tpl = "catalog_list_tpl.php";
}



// форма добавления или редактирования раздела
if ($_GET['id'] && $_GET['action'] == 'edit_category' || $_GET['action'] == 'add_category') {

    $category_edit = get_catalog_category_item($_GET['id']);
    $tpl = "catalog_add_category_tpl.php";
}



// Вывод списка разделов и категорий
if ($_GET['action'] == 'category') {

    $category_list = get_catalog_category_item();
    $tpl = "catalog_category_list_tpl.php";
}



// Форма добавления новой категории
if ($_GET['action'] == 'add_subcategory') {

    $tpl = "catalog_add_subcategory_tpl.php";
}



// настройки
if ($_GET['action'] == 'settings') {

    $tpl = "catalog_settings_tpl.php";
}



include "catalog_main_tpl.php";
?>
