<?php
// TODO убрать/переделать
// выбираем список категорий в зависимости от раздела
// используется для аякс-запроса при добавлении записи
if (!empty ($_POST['category_list'])) {

    include $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/functions.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/get_functions.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/mysql.class.php';

    check_install(); // проверяем, установлена ли cms
    $db = new SafeMySQL(array('user' => DB_LOGIN, 'pass' => DB_PASSWORD, 'db' => DB_NAME, 'charset' => 'utf8'));

    $rows = get_subcategories($_POST['category_list']);

    foreach ($rows as $rows2) {

        echo '<option value="' . $rows2['id'] . '">' . $rows2['title'] . '</option>';
    }

    exit;
}


defined('CAFE') or die (header ('Location: /'));

check_error ();


// Добавление новой записи
if (isset($_POST['add']) && empty ($error)) {

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $data = array(
        'date'        => timestamp($_POST['date']),
        'login'       => $_SESSION['login'],
        'status'      => $_POST['status'],
        'title'       => $_POST['title'],
        'text'        => $_POST['text'],
        'type'        => $_POST['type'],
        'category'    => $_POST['category'],
        'keywords'    => $_POST['keywords'],
        'description' => $_POST['description'],
        'url'         => preg_replace("/[^a-z0-9-]/", "", $url),
        'preview'     => $_POST['preview'],
        'source'      => $_POST['source']);

    $add_post = $db->query("INSERT " . DB_PREFIX . "_posts SET ?u", $data);

    if ($add_post) {

        $message = 'Запись добавлена';

    } else {

        $error = 'Возникла ошибка при сохранении записи';
    }
}



// Измененяем содержимое записи
if ($_POST['update'] && empty ($error)) {

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $data = array(
        'date'        => timestamp ($_POST['date']),
        'login'       => $_POST['login'],
        'status'      => $_POST['status'],
        'title'       => $_POST['title'],
        'text'        => $_POST['text'],
        'type'        => $_POST['type'],
        'category'    => $_POST['category'],
        'keywords'    => $_POST['keywords'],
        'description' => $_POST['description'],
        'url'         => preg_replace ("/[^a-z0-9-]/", "", $url),
        'preview'     => $_POST['preview'],
        'source'      => $_POST['source']);

    $update_post = $db->query("UPDATE " . DB_PREFIX . "_posts SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_post) {

        $message = 'Содержимое записи обновлено';

    } else {

        $error = 'Возникла ошибка при обновлении содержимого записи';
    }
}



// удаление записи
if ($_GET['action'] == 'delete') terminator ();


// Добавление нового раздела
if ($_POST['add_category'] && empty ($error)) {

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $data = array(
        'title' => $_POST['title'],
        'url'   => preg_replace ("/[^a-z0-9-]/", "", $url));

    $add_category = $db->query("INSERT " . DB_PREFIX . "_posts_categories SET ?u", $data);

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
        title => $_POST['title'],
        url   => preg_replace ("/[^a-z0-9-]/", "", $url));

    $update_category = $db->query("UPDATE " . DB_PREFIX . "_posts_categories SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_category) {

        $message = 'Раздел обновлен';

    } else {

        $error = 'Возникла ошибка при обновлении раздела';
    }
}



// удаление раздела TODO переделать
if ($_GET['action'] == 'delete_category') {

    if ($_SESSION['status'] == '1') {

        $delete = $db->query("DELETE FROM " . DB_PREFIX . "_posts_categories WHERE id=?i", $_GET['id']);


        if ($delete) {

            header ('Location: ?section=posts&action=category&msg=del');

        } else {

            $error = 'При удалении записи возникла ошибка: ' . mysql_errno() . ': ' . mysql_error();
        }

    } else {

        log_write ('Не удалось удалить запись: не достаточно прав', '0', '1');
        $error = 'Не достаточно прав для выполнения действия.';
    }
}



// Добавление категорий разделу
if ($_POST['add_subcategory'] && empty ($error)) {

	for ($i=0; $i<=(count ($_POST['title']))-1; $i++) {

        (empty ($_POST['url'][$i])) ? ($url = translit ($_POST['title'][$i])) : ($url = translit ($_POST['url'][$i]));

        $data = array(
            'pid'      => $_POST['pid'],
            'title'    => $_POST['title'][$i],
            'url'      => preg_replace ("/[^a-z0-9-]/", "", $url),
            'position' => $_POST['position'][$i]);

		$add_subcategory = $db->query("INSERT " . DB_PREFIX . "_posts_subcategories SET ?u", $data);

        if ($add_subcategory) {

            $message = 'Категории добавлены';

        } else {

            $error = 'Возникла ошибка при добавлении категорий';
        }
    }
}



// Измененяем содержимое комментария
if ($_POST['update_comment'] && empty ($error)) {

    $data = array(
        'login'  => $_POST['login'],
        'email'  => $_POST['email'],
        'text'   => $_POST['text'],
     	'status' => $_POST['status']);

    $update_comment = $db->query("UPDATE " . DB_PREFIX . "_comments SET ?u WHERE id=?i", $data, $_POST['id']);

    if ($update_comment) {

        $message = 'Комментарий обновлен';

    } else {

        $error = 'Возникла ошибка при обновлении комментария';
    }
}



// удаление комментария TODO переделать
if ($_GET['action'] == 'delete_comment' && empty ($error)) {

    if ($_SESSION['status'] == '1') {

        $delete = $db->query("DELETE FROM " . DB_PREFIX . "_comments WHERE id=?i", $_GET['id']);

        if ($delete) {

            header ('Location: ?section=posts&action=comments');

        } else {

            print_error ('При удалении записи возникла ошибка: ' . mysql_errno() . ': ' . mysql_error () . '.');
        }

    } else {

        log_write ('Не удалось удалить запись: не достаточно прав', '0', '1');

        print_error ('Не достаточно прав для выполнения действия');
    }
}




// Вывод списка записей
if ($_GET['action'] == 'list' && empty ($error)) {

    $limit = "10";
    $category_title = get_categories_title();
    $post_list = get_posts($limit);
    $tpl = "posts_list_tpl.php";
}



// Просмотр записи
if ($_GET['action'] == 'view' && empty ($error)) {

    $post_view = get_post ($_GET['id']);
    $comments_view = get_post_comments ($_GET['id']);
    $tpl = "posts_view_tpl.php";
}


// Добавление новой или изменение существующей записи
if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {

    if ($_GET['action'] == 'edit' && isset ($_GET['id'])) {

        $post_edit = get_post($_GET['id']);
        $subcategory_list = get_subcategories($post_edit['type']);
    }

    $category_list = get_categories();
    $tpl = "posts_add_tpl.php";
}



// форма добавления и редактирования раздела
if ($_GET['id'] && $_GET['action'] == 'edit_category' || $_GET['action'] == 'add_category') {

    $category_edit = get_category ($_GET['id']);
    $tpl = "posts_edit_category_tpl.php";
}



// Вывод списка разделов и категорий
if ($_GET['action'] == 'category') {

    $category_list = get_categories();
    $subcategory_list = get_subcategories();

    $tpl = "posts_list_category_tpl.php";
}



// Форма добавления новой категории
if ($_GET['action'] == 'add_subcategory') {

    $tpl = "posts_add_subcategory_tpl.php";
}



// Вывод комментариев
if ($_GET['action'] == 'comments') {
    $limit = '15';
    page_limit ($limit);

    $comments_list = get_comments_list($limit);
    $tpl = "posts_list_comments_tpl.php";
}



// Форма изменения комментария
if ($_GET['action'] == 'edit_comment') {

    $comment = get_comment($_GET['id']);
    $tpl = "posts_edit_comments_tpl.php";
}



include "posts_main_tpl.php";
?>
