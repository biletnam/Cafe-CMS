<?php
// TODO убрать/переделать
// выбираем список категорий в зависимости от раздела
// используется для аякс-запроса при добавлении записи
if (!empty ($_POST['category_list'])) {

    include $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/functions.php';

    check_install ();
    db_connect ();

    $rows = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_posts_subcategories` WHERE `pid` = '" . $_POST['category_list'] . "'");

    while ($rows2 = mysql_fetch_array ($rows)) {

        echo '<option value="' . $rows2['id'] . '">' . $rows2['title'] . '</option>';

    }

    exit;
}



defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Добавление новой записи
if (isset($_POST['add']) && empty ($error)) {

    clear_html ($_POST, array ($_POST['text']));

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $add_post = "INSERT `" . DB_PREFIX . "_posts` (
        `date`,
        `login`,
        `status`,
        `title`,
        `text`,
        `type`,
        `category`,
        `keywords`,
        `description`,
        `url`,
        `preview`,
        `source`)
    VALUES (
        '" . timestamp  ($_POST['date']) . "',
        '" . $_SESSION['login'] . "',
        '" . $_POST['status'] . "',
        '" . $_POST['title'] . "',
        '" . $_POST['text'] . "',
        '" . $_POST['type'] . "',
        '" . $_POST['category'] . "',
        '" . $_POST['keywords'] . "',
        '" . $_POST['description'] . "',
        '" . preg_replace ("/[^a-z0-9-]/", "", $url) . "',
        '" . $_POST['preview'] . "',
        '" . $_POST['source'] . "')";


    if (mysql_query ($add_post)) {

        $message = 'Запись добавлена';

    } else {

        $error = 'Возникла ошибка при сохранении записи';
    }
}



// Измененяем содержимое записи
if ($_POST['update'] && empty ($error)) {

    clear_html ($_POST, array ($_POST['text'], $_POST['preview']));

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $update_post      = "UPDATE `" . DB_PREFIX . "_posts` SET
        `date`        = '" . timestamp  ($_POST['date']) . "',
        `login`       = '" . $_POST['login'] . "',
        `status`      = '" . $_POST['status'] . "',
        `title`       = '" . $_POST['title'] . "',
        `text`        = '" . $_POST['text'] . "',
        `type`        = '" . $_POST['type'] . "',
        `category`    = '" . $_POST['category'] . "',
        `keywords`    = '" . $_POST['keywords'] . "',
        `description` = '" . $_POST['description'] . "',
        `url`         = '" . preg_replace ("/[^a-z0-9-]/", "", $url) . "',
        `preview`     = '" . $_POST['preview'] . "',
        `source`      = '" . $_POST['source'] . "'
    WHERE `id`        = '" . $_POST['id'] . "'";


    if (mysql_query ($update_post)) {
        $message = 'Содержимое записи обновлено';
    } else {
        $error = 'Возникла ошибка при обновлении содержимого записи';
    }
}



// удаление записи
if ($_GET['action'] == 'delete') terminator ();



// Добавление нового раздела
if ($_POST['add_category'] && empty ($error)) {

    clear_html ($_POST, array ());

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $add_category = "INSERT `" . DB_PREFIX . "_posts_categories` (
        `title`,
        `url`)
    VALUES (
        '" . $_POST['title'] . "',
        '" . preg_replace ("/[^a-z0-9-]/", "", $url) . "')";


    if (mysql_query ($add_category)) {

        $message = 'Раздел добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении раздела';
    }
}



// Измененяем раздел
if ($_POST['update_category'] && empty ($error)) {

    clear_html ($_POST, array ());

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $update_category = "
        UPDATE `" . DB_PREFIX . "_posts_categories`
        SET
            `title`      = '" . $_POST['title'] . "',
            `url`        = '" . preg_replace ("/[^a-z0-9-]/", "", $url) . "'
        WHERE
            `id`       = '" . $_POST['id'] . "'
    ";


    if (mysql_query ($update_category)) {

        $message = 'Раздел обновлен';

    } else {

        $error = 'Возникла ошибка при обновлении раздела';
    }
}



// удаление раздела TODO переделать
if ($_GET['action'] == 'delete_category') {

    if ($_SESSION['status'] == '1') {

        $delete = "DELETE FROM `" . DB_PREFIX . "_posts_categories` WHERE `id` = " . $_GET['id'] . " LIMIT 1";


        if (mysql_query ($delete)) {

            header ('Location: ?section=posts&action=category&msg=del');

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

    clear_html ($_POST, array ());

    $subcategory_count = count ($_POST['title']);

	for ($i=0; $i<=$subcategory_count-1; $i++) {

        (empty ($_POST['url'][$i])) ? ($url = translit ($_POST['title'][$i])) : ($url = translit ($_POST['url'][$i]));

		$add_subcategory = "INSERT `" . DB_PREFIX . "_posts_subcategories` (
            `pid`,
            `title`,
            `url`,
            `position`)

			VALUES (
            '" . $_POST['pid'] . "',
            '" . $_POST['title'][$i] . "',
            '" . preg_replace ("/[^a-z0-9-]/", "", $url) . "',
            '" . $_POST['position'][$i] . "')";


        if (mysql_query ($add_subcategory)) {

            $message = 'Категории добавлены'.mysql_error();

        } else {

            $error = 'Возникла ошибка при добавлении категорий';
        }
    }
}



// Измененяем содержимое комментария
if ($_POST['update_comment'] && empty ($error)) {

    clear_html ($_POST, array ());

    $update_comment = "
        UPDATE `" . DB_PREFIX . "_comments`
        SET
            `login`  = '" . $_POST['login'] . "',
            `email`  = '" . $_POST['email'] . "',
            `text`   = '" . $_POST['text'] . "',
         	`status` = '" . $_POST['status'] . "'
        WHERE
            `id`   = '" . $_POST['id'] . "'
    ";


    if (mysql_query ($update_comment)) {

        $message = 'Комментарий обновлен';

    } else {

        $error = 'Возникла ошибка при обновлении комментария';
    }
}



// удаление комментария TODO переделать
if ($_GET['action'] == 'delete_comment' && empty ($error)) {

    if ($_SESSION['status'] == '1') {

        $delete = "DELETE FROM `" . DB_PREFIX . "_comments` WHERE `id` = " . $_GET['id'] . " LIMIT 1";

        if (mysql_query ($delete)) {

            header ('Location: ?section=posts&action=comments&msg=del');

        } else {

            print_error ('При удалении записи возникла ошибка: ' . mysql_errno() . ': ' . mysql_error () . '.');
        }

    } else {

        log_write ('Не удалось удалить запись: не достаточно прав', '0', '1');

        print_error ('Не достаточно прав для выполнения действия.');
    }
}
?>



    <div class="module-title">

        <h1>Записи</h1>

    </div>


    <div class="module-menu">

        <a class="button" href="?section=posts&amp;action=list">Список записей</a>
        <a class="button" href="?section=posts&amp;action=add&amp;editor=1">Добавить запись</a>
        <a class="button" href="?section=posts&amp;action=category">Разделы и категории</a>
        <a class="button" href="?section=posts&amp;action=comments">Комментарии</a>

    </div>


    <div class="module-messages">

        <?php print_message ($message, $error); ?>

    </div>



<?php
// Вывод списка записей
if ($_GET['action'] == 'list' && empty ($error)) {
?>


    <div class="module-main-block">

        <div class="module-submenu span5">

            <form name="sort_categories" action="?section=posts&action=list&sort" method="post">

                <select size="1" name="type" class="sort" onChange="document.sort_categories.submit();return false;">

                    <option selected value="0">Все записи</option>

                    <?php
                    $type_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_posts_categories`");

                    while ($typerows = mysql_fetch_array ($type_list)) {

                        $type_array[$typerows['id']] = $typerows['title'];

                        echo '<option ';

                        if ($_POST['type'] == $typerows['id']) {echo 'selected ';}

                        echo 'value="' . $typerows['id'] . '">' . $typerows['title'] . '</option>';
                    }
                    ?>

                </select>

            </form>

        </div>

        <table>

            <thead>

                <tr>

                    <th class="span0">Раздел</th>
                    <th class="span3">Заголовок записи</th>
                    <th>Действие</th>
                    <th>Дата и время</th>

                </tr>

            </thead>

            <tbody>


    <?php
    if (isset ($_GET['sort']) && $_POST['type'] != "0" ) {$sort = "`type` = '" . $_POST['type'] . "'";}

    get_post_list ('10', $sort);

    foreach ($post_array as $post) {

        echo '
            <tr>

                <td>' . $type_array[$post['type']] . '</td>
                <td>
                    <a ';
                    if ($post['status'] < "1") {

                        echo 'style="color:#aaa"';
                    }
        echo ' href="?section=posts&action=view&id=' . $post['id'] . '">' . $post['title'] . '</a>
                </td>
                <td>
                    <a class="dashed" href="?section=posts&amp;action=delete&amp;id=' . $post['id'] . '">удалить</a>
                    <a class="dashed" href="?section=posts&amp;action=edit&amp;id=' . $post['id'] . '&amp;editor=1">изменить</a></td>
                <td>' . date ('d.m.Y H:i:s', $post['date']) . '</td>

            </tr>';
    }

    echo '
        </tbody>

    </table>


    <div class="pagination">

        <ul>';
            pager (ceil ($post_count / $end_page), "/admin/index.php?section=posts&amp;action=list");
    echo '
        </ul>

    </div>';
}



// Просмотр записи
if ($_GET['action'] == 'view' && empty ($error)) {

    $post_view = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_posts`
        WHERE `id` = '" . $_GET['id'] . "'
        LIMIT 1
    ");

    $row = mysql_fetch_array ($post_view, MYSQL_ASSOC);

    echo '
    <div class="module-main-block">

        <h1 class="bottom20">' . $row['title'] . '</h1>

        <div class="bottom20">' . $row['text'] . '</div>

        <div class="module-footer">

            <p><strong>Дата размещения</strong>: ' . date ("j.m.Y H:i", $row['date']) . '</p>
            <p><strong>Ключевые слова</strong>: ' . $row['keywords'] . '</p>
            <p><strong>Описание страницы</strong>: ' . $row['description'] . '</p>

            <div>

                <a class="dashed" href="?section=posts&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a>
                <a class="dashed" href="?section=posts&amp;action=edit&amp;id=' . $row['id'] . '&amp;editor=1">изменить</a>

            </div>

        </div>

    </div>

    <h2>Комментарии:</h2>';

    // комментарии к этой записи
    $comments_view = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_comments`
        WHERE `tid` = '" . $_GET['id'] . "'
        ORDER BY `date` ASC
    ");


    while ($row = mysql_fetch_array ($comments_view, MYSQL_ASSOC)) {

        echo '
            <div class="post-view-comment">

                <p class="post-view-comment-login">' . $row['login'] . '

                <span class="post-view-comment-date"> (' . date ('H:i:s d.m.Y', $row['date']) . ')</span></p>

                <p class="post-view-comment-text">' . $row['text'] . '<p>

            </div>
        ';
    }
}


// Добавление новой или изменение существующей записи
if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {


    /* Выводим заполненную форму
     * Если выбрано изменение записи, делаем дополнительный запрос, для
     * получения данных из БД для заполнения формы.
     */
    if ($_GET['action'] == 'edit' && isset ($_GET['id'])) {

        $sql_list = @mysql_query ("
            SELECT *
            FROM `" . DB_PREFIX . "_posts`
            WHERE `id` = '" . $_GET['id'] . "'
            LIMIT 1
        ");

        $row = mysql_fetch_array ($sql_list, MYSQL_ASSOC);

    }
?>

    <div class="module-main-block">

        <!-- функции для обновления значений в списке "Категории"
        в зависимости от значения списка "Раздел" -->
        <script>
        var req=false;
        function Load() {

            try {

                req=new ActiveXObject('Msxml2.XMLHTTP');

            } catch (e) {

                try {

                    req=newActiveXObject('Microsoft.XMLHTTP');

                } catch (e) {

                    if (window.XMLHttpRequest) {

                        req=new XMLHttpRequest();
                    }
                }
            }

            if (req) {

                req.onreadystatechange=receive;
                req.open("POST", "/admin/modules/posts/index.php", true);
                req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

                var data="category_list="+ document.getElementById('type').value;

                req.send(data);

            } else {

                alert("Объект не поддерживается!");
            }
        }

        function receive() {

            if (req.readyState==4) {

                if (req.status==200) {

                    document.getElementById('category').innerHTML=(req.responseText);

                } else {

                    alert("Ошибка "+ req.status+": " + req.statustext);
                }
            }
        }
        </script>


        <form class="form-block" name="new_post" action="?section=posts&amp;action=list" method="post">

            <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение ";} else {echo "Добавление новой";}?> записи</legend>

            <input type="hidden" name="id"<?php if (isset ($row['id'])) {echo ' value="' . $row['id'] . '"';} ?>>


            <div class="form-group-vertical">

                <label class="form-label-vertical" for="title">Заголовок записи:</label>

                <div class="form-input-vertical span5">

                    <input type="text" name="title" id="title"

                    <?php
                    if (isset ($row['title'])) {

                        echo ' value="' . $row['title'] . '"';
                    }
                    ?>>

                </div>

            </div>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="type">Раздел:</label>

                <div class="form-input-vertical span2">

                    <select size="1" name="type" id="type" onchange="Load(); return false">

                        <option selected value="0"></option>

                        <?php
                        $type_list = mysql_query ("SELECT title, id FROM `" . DB_PREFIX . "_posts_categories`");

                        while ($typerows = mysql_fetch_array ($type_list)) {

                            echo '<option ';

                            if ($row['type'] == $typerows['id']) {

                                echo 'selected ';$current_id = $typerows['id'];
                            }

                            echo 'value="' . $typerows['id'] . '">' . $typerows['title'] . '</option>';
                        }
                        ?>
                    </select>

                </div>

            </div>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="category">Категория:</label>

                <div class="form-input-vertical span2">

                    <select size="1" name="category" id="category">

                    <?php
                    if ($_GET['action'] == "add") {

                        echo '<option value="0">Выберите раздел</option>';

                    } else {

                        $category_list = mysql_query ("
                            SELECT *
                            FROM `" . DB_PREFIX . "_posts_subcategories`
                            WHERE `pid` = '" . $current_id . "'
                        ");


                        while ($categories_rows = mysql_fetch_array ($category_list)) {

                            echo '<option ';

                            if ($row['category'] == $categories_rows['id']) {echo 'selected ';}

                            echo 'value="' . $categories_rows['id'] . '">' . $categories_rows['title'] . '</option>';
                        }
                    }
                    ?>

                    </select>

                </div>

            </div>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="editor">Содержимое страницы:</label>

                <div class="form-input-vertical">

                    <textarea name="text" id="editor"><?php echo $row['text']; ?></textarea>

                    <script type="text/javascript">
                        var ckeditor = CKEDITOR.replace('editor');

                        DjenxExplorer.init({

	                        returnTo: ckeditor,
	                        lang : 'ru'

                        });

                        //	for Input fields
                        DjenxExplorer.init({returnTo: 'function'});
                    </script>

                </div>

            </div>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="preview">Превью:</label>

                <div class="form-input-vertical span5">

                    <input type="text" id="preview" name="preview"

                    <?php
                    if (isset ($row['preview'])) {

                        echo ' value="' . $row['preview'] . '"';
                    }
                    ?>

                    onclick="DjenxExplorer.open({returnTo: '$preview'});">

                </div>

            </div>

            <div class="form-group-vertical">

                <div class="form-input-vertical">

                    <a class="dashed" id="view"
                    onclick="if (document.getElementById ('additional-fields').style.display == 'none'){
                        document.getElementById ('additional-fields').style.display = 'block';
                        document.getElementById ('view').innerHTML = 'скрыть дополнительные поля';}
                        else{document.getElementById ('additional-fields').style.display = 'none';
                        document.getElementById ('view').innerHTML = 'показать дополнительные поля';
                    }">показать дополнительные поля</a>

                </div>

            </div>

            <div style="display:none" id="additional-fields">

                <div class="form-group-vertical">

                    <div class="form-input-vertical span5">

                        <label>

                            <input type="radio" name="status" <?php if ($row['status'] == "1") {echo 'checked ';} ?>value="1"> опубликовать

                        </label>

                        <label>

                            <input type="radio" name="status" <?php if ($row['status'] == "0") {echo 'checked ';} ?>value="0"> в черновик

                        </label>

                    </div>

                </div>

                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="source">Источник информации:</label>

                    <div class="form-input-vertical span5">

                        <input type="text" name="source" id="source" size="60"

                        <?php
                        if (isset ($row['source'])) {

                            echo ' value="' . $row['source'] . '"';
                        }
                        ?>>

                    </div>

                </div>

                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="url">Адрес страницы: (<a class="dashed" onclick="translit()">автозаполнение</a>)</label>

                    <div class="form-input-vertical span5">

                        <input type="text" name="url" id="url" size="60"

                        <?php
                        if (isset ($row['url'])) {

                            echo ' value="' . $row['url'] . '"';
                        }
                        ?>>

                    </div>

                </div>

                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="date">Дата добавления:</label>

                    <div class="form-input-vertical span5">

                        <input type="text" name="date" id="date"

                        <?php
                        if (isset ($row['date'])) {

                            echo ' value="' . date('H:i:s d.m.Y', $row['date']) . '"';

                        } else {

                            echo ' value="' . date ('H:i:s d.m.Y') . '"';
                        }
                        ?>>

                    </div>

                </div>

                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="keywords">Ключевые слова:</label>

                    <div class="form-input-vertical span5">

                        <input type="text" name="keywords" id="keywords"

                        <?php
                        if (isset ($row['keywords'])) {

                            echo ' value="' . $row['keywords'] . '"';
                        }
                        ?>>

                    </div>

                </div>

                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="description">Описание страницы:</label>

                    <div class="form-input-vertical span5">

                        <input type="text" name="description" id="description"

                        <?php
                        if (isset ($row['description'])) {

                            echo ' value="' . $row['description'] . '"';
                        }
                        ?>>

                    </div>

                </div>

            </div>

            <div class="form-group-vertical">

                <div class="form-input-vertical span5">

                    <?php
                    ($_GET['action'] == 'edit') ? $name="update" : $name="add";

                    echo '<input class="button" type="submit" name="' . $name . '" value="сохранить">';
                    ?>

                </div>

            </div>

        </form>

    </div>

<?php
}



// форма добавления и редактирования раздела
if ($_GET['id'] && $_GET['action'] == 'edit_category' || $_GET['action'] == 'add_category') {

    $category_list = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_posts_categories`
        WHERE `id` = '" . $_GET['id'] . "'
    ");


    $row = mysql_fetch_array ($category_list, MYSQL_ASSOC);
?>

        <form class="form-block module-main-block" action="?section=posts&action=category" method="post">

            <legend><?php if ($_GET['action'] == 'edit_category') {echo "Изменение ";} else {echo "Добавление нового";}?> раздела</legend>

            <input type="hidden" name="id"<?php if (isset ($row['id'])) {echo ' value="' . $row['id'] . '"';} ?>>

            <div class="form-group">

                <label class="form-label" for="title">Название раздела:</label>

                <div class="form-input span2">

                    <input type="text" name="title" id="title"

                    <?php
                    if (isset ($row['title'])) {

                        echo ' value="' . $row['title'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="url">Адрес раздела:</label>

                <div class="form-input span2">

                    <input type="text" name="url" id="url"

                    <?php
                    if (isset ($row['url'])) {

                        echo ' value="' . $row['url'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <div class="form-input">

                    <?php
                    ($_GET['action'] == 'edit_category') ? $name="update_category" : $name="add_category";

                    echo '<input class="button" type="submit" name="' . $name . '" value="сохранить">';
                    ?>

                </div>

            </div>

        </form>

    </div>

<?php
}



// Вывод списка разделов и категорий
if ($_GET['action'] == 'category') {
?>


    <div class="module-submenu">

        <a class="dashed" href="/admin/index.php?section=posts&amp;action=add_category">Добавить новый раздел</a>

    </div>


    <div class="module-main-block">

        <table class="module-main-block bottom20">

            <thead>

                <tr>

                    <th>Раздел</th>
                    <th>Категории</th>

                </tr>

            </thead>

            <tbody>


        <?php
        $category_list = mysql_query ("
            SELECT *
            FROM `" . DB_PREFIX . "_posts_categories`
            ORDER BY `id`
        ");


        if (mysql_num_rows ($category_list) < '1') {

            echo '<p style="margin-right:20px">Не создано ни одного раздела. Вы можете сделать это
            прямо сейчас.</p>';

        } else {

            while ($row = mysql_fetch_array ($category_list, MYSQL_ASSOC)) {

                $sid = $row['id'];
                echo '
                <tr>

                    <td class="span1"><strong>' . $row['title'] . '</strong><br>
                        <a class="dashed" href="?section=posts&amp;action=delete_category&amp;id=' . $row['id'] . '">удалить</a>
                        <a class="dashed" href="?section=posts&amp;action=edit_category&amp;id=' . $row['id'] . '">изменить</a></td>
                    <td>';

                    $subcategory_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_posts_subcategories` WHERE `pid` = '".$row['id']."'");

                    while ($subrow = mysql_fetch_array ($subcategory_list, MYSQL_ASSOC)) {

                        echo '<span class="subcategory">' . $subrow['title'] . '</span>';
                    }

                    echo '<a class="dashed" href="?section=posts&amp;action=add_subcategory&amp;id=' . $row['id'] . '">добавить</a>

                    </td>

                </tr>';
            }
        }
        ?>


            </tbody>

        </table>

    </div>

<?php
}



// Форма добавления новой категории
if ($_GET['action'] == 'add_subcategory') {
?>

    <div>

        <form class="form-block module-main-block" action="?section=posts&action=category" method="post">

            <legend>Добавление новой категории</legend>

            <input name="pid" value="<?php echo $_GET['id'];?>" type="hidden">

            <div class="form-group">

                <label class="form-label" for="title">Название категории:</label>

                <div class="form-input">

                    <input class="span1" name="title[]" type="text" id="title">

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="url">Адрес:</label>

                <div class="form-input">

                    <input class="span1" name="url[]" type="text" id="url">

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="position">Порядковый номер:</label>

                <div class="form-input">

                    <input name="position[]" type="text" id="position">

                </div>

            </div>

        <div id="new-subcat"></div>

            <div class="form-group">

                <div class="form-input">

                    <input class="button" type="submit" name="add-fields" value="Еще одну" onclick="newField(); return false">
                    <input class="button" type="submit" name="add_subcategory" value="Сохранить">

                </div>

            </div>

        </form>

    </div>


	<script type="text/javascript">
	function newField() {

		document.getElementById('new-subcat').outerHTML='<div class="form-group" style="padding-top:20px;border-top:1px #ccc dashed"><label class="form-label" for="title">Название категории:</label><div class="form-input"><input class="span1" name="title[]" type="text" id="title"></div></div><div class="form-group"><label class="form-label" for="url">Адрес:</label><div class="form-input"><input class="span1" name="url[]" type="text" id="url"></div></div><div class="form-group"><label class="form-label" for="position">Порядковый номер:</label><div class="form-input"><input name="position[]" type="text" id="position"></div></div><div id="new-subcat"></div>';
	}
	</script>
	
<?php
}



// Вывод комментариев
if ($_GET['action'] == 'comments') {
?>


    <div class="module-submenu span1">

        <form name="sort_comments" action="?section=posts&action=comments&sort" method="post">

            <select size="1" name="type" class="sort" onChange="document.sort_comments.submit();return false;">
                <option selected value="0">Все разделы</option>

                <?php
                $type_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_posts_categories`");

                while ($typerows = mysql_fetch_array ($type_list)) {

                    $type_array[$typerows['id']] = $typerows['title'];

                    echo '<option ';

                    if ($_POST['type'] == $typerows['url']) {echo 'selected ';}

                    echo 'value="' . $typerows['url'] . '">' . $typerows['title'] . '</option>';
                }
                ?>
            </select>

        </form>

    </div>


    <table class="module-main-block bottom20">

        <thead>

            <tr>

                <th>Пользователь</th>
                <th>Текст комментария</th>
                <th>Действия</th>
                <th class="span1">Дата и время</th>

            </tr>

        </thead>

        <tbody>


    <?php
    page_limit ('15');


    if (isset ($_GET['sort']) && $_POST['type'] != "0" ) {$sort = "WHERE `type` = '" . $_POST['type'] . "'";}


    $comments_list = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_comments` " . $sort . "
        ORDER BY `date` DESC
        LIMIT " . $start_page . ", " . $end_page . "
    ");


     if (@mysql_num_rows ($comments_list) < '1') {

        echo '<p style="margin-right:20px">Еще никто не оставил комментарий.</p>';

     } else {

        while ($comments_row = mysql_fetch_array ($comments_list, MYSQL_ASSOC)) {

            $category_list = mysql_query ("
                SELECT title
                FROM `" . DB_PREFIX . "_posts_categories`
                WHERE `url` = '" . $comments_row['type'] . "'
            ");

            $category_row  = mysql_fetch_array ($category_list, MYSQL_ASSOC);

            echo '
            <tr>

                <td>' . $comments_row['login'] . '<br><small>' . $comments_row['ip'] . '<br>' . $comments_row['email'] . '</small></td>
                <td style="height:55px;display:block;overflow:hidden" ';
                    if ($comments_row['status'] < "1") {echo ' style="color:#aaa"';} echo '>' . $comments_row['text'];
               echo '</td>
                <td>
                    <a class="dashed" href="?section=posts&amp;action=delete_comment&amp;id=' . $comments_row['id'] . '">удалить</a><br>
                    <a class="dashed" href="?section=posts&amp;action=edit_comment&amp;id=' . $comments_row['id'] . '&amp;editor=1">изменить</a></td>
                <td>' . date ('d.m.Y', $comments_row['date']) . ' ' . date ('H:i:s', $comments_row['date']) . '</td>

            </tr>';
        }

        echo '
            </tbody>

        </table>


        <div class="pagination">

            <ul>';
                pager (ceil (mysql_num_rows (mysql_query ("SELECT id FROM `" . DB_PREFIX . "_comments` " . $sort)) / $end_page), "/admin/index.php?section=posts&amp;action=comments");
        echo '
            </ul>

        </div>';
    }
}



// Форма изменения комментария
if ($_GET['action'] == 'edit_comment') {

    $sql = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_comments`
        WHERE `id` = '" . $_GET['id'] . "'
        LIMIT 1
    ");


    while ($row = mysql_fetch_array ($sql, MYSQL_ASSOC)) {

        $id     = $row['id'];
        $login  = $row['login'];
        $email  = $row['email'];
        $text   = $row['text'];
     	$date   = $row['date'];
     	$status = $row['status'];
    }
?>


        <form class="form-block module-main-block" name="edit_comment" action="/admin/index.php?section=posts&action=comments" method="post">

            <legend>Редактирование комментария</legend>

            <input type="hidden" name="id"<?php if (isset ($id)) {echo ' value="' . $id . '"';} ?>>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="login">Имя:</label>

                <div class="form-input-vertical span1">

                    <input type="text" name="login" id="login"

                    <?php
                    if (isset ($login)) {

                        echo ' value="' . $login . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group-vertical">

                <label class="form-label-vertical" for="email">E-mail:</label>

                <div class="form-input-vertical span1">

                    <input type="text" name="email" id="email"

                    <?php
                    if (isset ($email)) {

                        echo ' value="' . $email . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group-vertical">

                <label class="form-label-vertical" for="editor">Комментарий:</label>

                <div class="form-input-vertical">

                    <textarea class="span7" name="text" id="editor" rows="8"><?php echo $text; ?></textarea>

                </div>
            </div>


            <div class="form-group-vertical">

                <div class="form-input-vertical radio">

                    <label><input type="radio" name="status" <?php if ($status == "1") {echo 'checked ';} ?>value="1"> опубликовать</label>
                    <label><input type="radio" name="status" <?php if ($status == "0") {echo 'checked ';} ?>value="0"> в черновик</label>

                </div>

            </div>


            <div class="form-group-vertical">

                <div class="form-input-vertical">

                    <input class="button" type="submit" name="update_comment" value="Обновить">

                </div>

            </div>

        </form>

<?php
}
?>
