<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Добавление новой фотографии
if (isset($_POST['add']) && empty ($error)) {

    clear_html ($_POST, array ());
    timestamp  ($_POST['date']);

    $add_photo = "INSERT `" . DB_PREFIX . "_photos` (
        `title`,
        `description`,
        `date`,
        `album`)
    VALUES (
        '" . $_POST['title'] . "',
        '" . $_POST['description'] . "',
        '" . timestamp  ($_POST['date']) . "',
        '" . $_POST['album'] . "'
        )";


    // Загружаем фотографию, уменьшаем и делаем квадратное превью
    file_upload  (array("jpeg","jpg","png"), "image/jpeg", "../upload/photo/original/" . timestamp  ($_POST['date']) . ".jpg");
    resize_pic   ($_FILES["file"]["tmp_name"], "800", "600", "../upload/photo/800-600/". timestamp  ($_POST['date']) .".jpg", "75");
    crop_preview ($_FILES["file"]["tmp_name"], "200", "../upload/photo/200-200/". timestamp  ($_POST['date']) .".jpg", "75");


    if (mysql_query ($add_photo)) {

        $message = 'Фотография успешно добавлена';

    } else {

        $error = 'Возникла ошибка при добавлении фотографии';
    }
}



// Добавление нового альбома
if (isset($_POST['add_album']) && empty ($error)) {

    clear_html ($_POST, array ());
    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $add_album = "INSERT `" . DB_PREFIX . "_albums` (
        `title`,
        `description`,
        `url`)
    VALUES (
        '" . $_POST['title'] . "',
        '" . $_POST['description'] . "',
        '" . preg_replace ("/[^a-z0-9-]/", "", $url) . "')";


    if (mysql_query ($add_album)) {

        $message = 'Альбом успешно добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении альбома';
    }
}



// Обновление фото при изменении
if (isset($_POST['update']) && empty ($error)) {

    clear_html ($_POST, array ());

    $update_photo = "UPDATE `" . DB_PREFIX . "_photos` SET
        `title`       = '" . $_POST['title'] . "',
        `description` = '" . $_POST['description'] . "',
        `album`       = '" . $_POST['album'] . "'
    WHERE `id`        = '" . $_POST['id'] . "'";


    if (mysql_query ($update_photo)) {

        $message = 'Фотография успешно изменена';

    } else {

        $error = 'Возникла ошибка при изменении фотографии';
    }
}



// Обновление альбом при изменении
if (isset($_POST['update_album']) && empty ($error)) {

    clear_html ($_POST, array ());
    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $update_album = "UPDATE `" . DB_PREFIX . "_albums` SET
        `url`         = '" . preg_replace ("/[^a-z0-9-]/", "", $url) . "',
        `title`       = '" . $_POST['title'] . "',
        `description` = '" . $_POST['description'] . "'
    WHERE `id`        = '" . $_POST['id'] . "'";


    if (mysql_query ($update_album)) {

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

        $get_photo = mysql_query ("SELECT id FROM `" . DB_PREFIX . "_photos` WHERE `album` = " . $_GET['id']);

        if (mysql_num_rows ($get_photo) >= '1') {

            $error = 'Этот альбом содержит фотографии. Удаление невозможно.';

        } else {

            $delete = "DELETE FROM `" . DB_PREFIX . "_albums` WHERE `id` = " . $_GET['id'] . " LIMIT 1";

            if (mysql_query ($delete)) {

                header ('Location: ?section=photos&action=album_list&del=ok');
            }
        }

    } else {

        log_write ('Не удалось удалить альбом: не достаточно прав', '0', '1');
        print_error ('Не достаточно прав для выполнения действия.');
    }
}
?>



    <div class="module-title">

        <h1>Фотографии</h1>

    </div>


    <div class="module-menu">

        <a class="button" href="?section=photos&amp;action=list">Список фотографий</a></li>
        <a class="button" href="?section=photos&amp;action=add">Добавить фотографии</a>
        <a class="button" href="?section=photos&amp;action=album_list">Альбомы</a>
        <a class="button" href="?section=photos&amp;action=album_add">Добавить альбом</a>

    </div>


    <div class="module-messages">

        <?php print_message ($message, $error);?>

    </div>



<?php
// выводим список фотографий
if ($_GET['action'] == 'list') {

    page_limit ('3');

    echo '<div class="module-main-block">';

    $photo_list = mysql_query ("SELECT id, title, album, date, description FROM `" . DB_PREFIX . "_photos` ORDER BY `id` DESC LIMIT " . $start_page . ", " . $end_page);

    if (mysql_num_rows ($photo_list) < '1') {

        echo '<p>Не загружено ни одной фотографии. Вы можете сделать это
        прямо сейчас. <a href="/admin/index.php?section=photos&amp;action=add">
        Загрузить фото?</a></p>';

    } else {

        echo '<ul>';

        while ($row = mysql_fetch_array ($photo_list, MYSQL_ASSOC)) {

            echo '
            <li class="photo-thumb">

                <div class="thumb">

                    <a href="/admin/index.php?section=photos&amp;action=view&amp;id=' . $row['id'] . '">

                        <img src="/upload/photo/200-200/' . $row['date'] . '.jpg" width="200" height="200">

                    </a>

                    <div class="photo-caption">

                        <p>' . $row['title'] . '</p>

                        <a class="button" href="/admin/index.php?section=photos&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a>
                        <a class="button" href="/admin/index.php?section=photos&amp;action=edit&amp;id=' . $row['id'] . '&amp;album=' . $row['album'] . '">изменить</a>

                    </div>

                </div>

            </li>';
        }

    echo '</ul>

    <div class="pagination both">

        <ul>';
            pager (ceil (mysql_num_rows (mysql_query ("SELECT id FROM `" . DB_PREFIX . "_photos`")) / $end_page),'/admin/index.php?section=photos&action=list');
    echo '
        </ul>

    </div>';

    }
    echo '</div>';
}



// просмотр содержимого альбома (фотографий)
if ($_GET['action'] == 'album_view' && isset ($_GET['id']) && empty ($error)) {

    page_limit ('12');

    echo '<div class="module-main-block">';

    $photo_list = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_photos`
        WHERE `album` =" . $_GET['id'] . "
        ORDER BY `id` DESC
        LIMIT " . $start_page . ", " . $end_page
    );


    if (mysql_num_rows ($photo_list) < '1') {

        echo '<p>В этом альбоме нет фотографий. <a href="/admin/index.php?section=photos&amp;action=add&album=' . $_GET['id'] . '">Загрузить фото?</a></p>';

    } else {

        echo '
        <div class="module-submenu span5">

            <a class="dashed" href="?section=photos&amp;action=add&album=' . $_GET['id'] . '">Добавить фотографии в этот альбом</a>

        </div>

        <ul>';

        while ($row = mysql_fetch_array ($photo_list, MYSQL_ASSOC)) {

            echo '
            <li class="photo-thumb">

                <div class="thumb">

                    <a href="/admin/index.php?section=photos&amp;action=view&amp;id=' . $row['id'] . '">

                        <img src="/upload/photo/200-200/' . $row['date'] . '.jpg" width="200" height="200">

                    </a>

                    <div class="photo-caption">

                        <p>' . $row['title'] . '</p>

                        <a class="button" href="/admin/index.php?section=photos&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a>
                        <a class="button" href="/admin/index.php?section=photos&amp;action=edit&amp;id=' . $row['id'] . '&amp;album=' . $row['album'] . '">изменить</a>

                    </div>

                </div>

            </li>';
        }

    echo '</ul>

    <div class="pagination both">

        <ul>';
            pager (ceil (mysql_num_rows (mysql_query ("SELECT id FROM `" . DB_PREFIX . "_photos`")) / $end_page),'/admin/index.php?section=photos&action=list');

    echo '

        </ul>
    </div>';

    }

    echo '</div>';
}



/* Просмотр фотографии
 * Если параметр action равен view, выводим фото для просмотра. Выводится
 * изображение с размерами 800 на 600 пикселей.
 */
if ($_GET['action'] == 'view' && empty ($error)) {

    $view_photo = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_photos` WHERE `id` = " . $_GET['id']);
    while ($row = mysql_fetch_array ($view_photo, MYSQL_ASSOC)) {

        echo '
    <div class="module-main-block">

        <div class="page-content">

            <h1 class="bottom20">' . $row['title'] . '</h1>

            <div class="bottom20">' . $row['description'] . '

                <img class="big-photo" src="/upload/photo/800-600/' . $row['date'] . '.jpg">

            </div>

            <p><strong>Дата размещения</strong>: ' . date ("j.m.Y H:i", $row['date']) . '</p>

            <div>

                <a class="dashed" href="?section=photos&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a>
                <a class="dashed" href="?section=photos&amp;action=edit&amp;id=' . $row['id'] . '&amp;album=' . $row['album'] . '">изменить</a>

            </div>

        </div>

    </div>';
    }
}



// добавление или изменение фотографии
if (($_GET['action'] == 'add' || $_GET['action'] == 'edit') && empty ($error)) {


    // изменение фотографии
    if ($_GET['action'] == 'edit' && isset ($_GET['id']) && empty ($error)) {

        $photo_list = @mysql_query ("SELECT * FROM `" . DB_PREFIX . "_photos` WHERE `id` = " . $_GET['id'] . " LIMIT 1");
        $row = mysql_fetch_array ($photo_list, MYSQL_ASSOC);

    }

?>


    <div class="module-main-block">

        <form class="form-block photo-left-block" enctype="multipart/form-data" name="new-photo" action="?section=photos&action=list" method="post">

            <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение ";} else {echo "Добавление новой";}?> фотографии</legend>

            <input type="hidden" name="id"

            <?php
            if (isset ($row['id'])) {

                echo ' value="' . $row['id'] . '"';
            }
            ?>>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="album">Альбом:</label>

                <div class="form-input-vertical">

                <select size="1" name="album" id="album">

                    <option selected value="0">/</option>

                    <?php
                    $sql_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_albums`");

                    while ($row_album = mysql_fetch_array ($sql_list, MYSQL_ASSOC)) {

                        echo '<option ';

                        if ($_GET['album'] == $row_album['id']) {echo 'selected ';}

                        echo 'value="' . $row_album['id'] . '">' . $row_album['title'] . '</option>';
                    }
                    ?>

                </select>

                </div>

            </div>


            <div class="form-group-vertical">

                <label class="form-label-vertical" for="title">Название фотографии:</label>

                <div class="form-input-vertical">

                    <input type="text" name="title" id="title"

                    <?php
                    if (isset ($row['title'])) {

                        echo ' value="' . htmlspecialchars($row['title']) . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group-vertical">

                <label class="form-label-vertical" for="description">Описание фотографии:</label>

                <div class="form-input-vertical">

                    <textarea class="span3" name="description" id="description" rows="5"><?php echo $row['description']; ?></textarea>

                </div>
            </div>


            <?php
            if ($_GET['action'] == 'add') {
            ?>

            <div class="form-group-vertical">

                <div class="form-input-vertical span3" style="position:relative;text-align:center">

                        <input class="span3" type="file" id="files" name="file" style="cursor:pointer;position:absolute;font-size:24px;right:0;opacity:0;-moz-opacity:0;filter:alpha(opacity=0)">

                        <a type="button" class="button span2">Выберите фотографию</a>

                </div>

            </div>

            <?php
            }

            if ($_GET['action'] != 'edit') {
            ?>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="date">Дата добавления:</label>

                <div class="form-input-vertical span3">

                    <input class="span1" type="text" name="date" id="date"
                    <?php
                    if (isset ($row['date'])) {

                        echo ' value="' . date('H:i:s d.m.Y', $row['date']) . '"';

                    } else {

                        echo ' value="' . date ('H:i:s d.m.Y') . '"';
                    }

               echo '>
                </div>

            </div>';
            }?>



            <div class="form-group-vertical">

                <div class="form-input-vertical">
                    <?php
                    ($_GET['action'] == 'edit') ? $name="update" : $name="add";
                    echo '<input class="button" style="float:right" type="submit" name="' . $name . '" value="сохранить">';
                    ?>

                </div>
            </div>
        </form>


        <div class="fileDisplayArea" id="fileDisplayArea"></div>

    </div>

        <script>
        function handleFileSelect(evt) {
            var files = evt.target.files; // FileList object

            // Loop through the FileList and render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {

                // Только если файл является изображением
                if (!f.type.match('image.*')) {
                    document.getElementById('fileDisplayArea').innerHTML = '<div class="print_error">Это не фотография. Выберите файл с расширением jpg или jpeg.</div>';
                    continue;
                }

                var reader = new FileReader();

                // Closure to capture the file information.
                reader.onload = (function(theFile) {

                    return function(e) {

                        document.getElementById('fileDisplayArea').innerHTML = ['<img src="', e.target.result,'">'].join('');
                    };
                })(f);

                // Read in the image file as a data URL.
                reader.readAsDataURL(f);
            }
        }

        document.getElementById('files').addEventListener('change', handleFileSelect, false);
        </script>

    </div>


<?php
}



// выводим список альбомов
if ($_GET['action'] == 'album_list' && empty ($error)) {

    $albums_list = mysql_query ("SELECT id, title, description FROM `" . DB_PREFIX . "_albums`");

    if (mysql_num_rows ($albums_list) < '1') {
        echo '<p>Не создано ни одного альбома. Вы можете сделать это
        прямо сейчас. <a href="/admin/index.php?section=photos&amp;action=album_add">
        Создать альбом?</a></p>';

    } else {
?>
        <table class="module-main-block">

            <thead>

                <tr>

                    <th>Альбом</th>
                    <th>Описание</th>
                    <th>Фото</th>
                    <th>Действия</th>

                </tr>

            </thead>

            <tbody>


            <tr>

                <td>

                    <a href="?section=photos&amp;action=album_view&amp;id=0">Вне альбомов</a>

                </td>

                <td>Корневой каталог</td>

                <td>

                    <?php
                    $photo_list = mysql_query ("SELECT id FROM `" . DB_PREFIX . "_photos` WHERE `album` = '0'");

                    echo mysql_num_rows ($photo_list);
                    ?>

                </td>

                <td> </td>
            </tr>


        <?php
        while ($row = mysql_fetch_array ($albums_list, MYSQL_ASSOC)) {

            echo '
            <tr>

                <td>

                    <a href="?section=photos&amp;action=album_view&amp;id=' . $row['id'] . '">' . $row['title'] . '</a>

                </td>

                <td>' . $row['description'] . '</td>

                <td>';
                    $photo_list = mysql_query ("SELECT id FROM `" . DB_PREFIX . "_photos` WHERE `album` =" . $row['id']);

                    echo mysql_num_rows ($photo_list) .'
                </td>

                <td>

                    <a class="dashed" href="?section=photos&amp;action=album_delete&amp;id=' . $row['id'] . '">удалить</a>
                    <a class="dashed" href="?section=photos&amp;action=album_edit&amp;id=' . $row['id'] . '">изменить</a>

                </td>

            </tr>';
        }

        echo '
        </tbody>

    </table>

    <div class="pagination">

        <ul>';
            pager (ceil (mysql_num_rows (mysql_query ("SELECT id FROM `" . DB_PREFIX . "_posts`")) / $end_page));
            //page_counter('posts', $post_count, $end_page);
    echo '
        </ul>

    </div>';
    }
}



// добавление или изменение альбома
if (($_GET['action'] == 'album_edit' && isset ($_GET['id'])) || $_GET['action'] == 'album_add' && empty ($error)) {

    if ($_GET['action'] == 'album_edit') {

        $album_list = @mysql_query ("
            SELECT *
            FROM `" . DB_PREFIX . "_albums`
            WHERE `id` = " . $_GET['id']
        );


        while ($row = mysql_fetch_array ($album_list, MYSQL_ASSOC)) {

            $id    = $row['id'];
            $title = $row['title'];
            $description = $row['description'];
            $url   = $row['url'];
        }
    }
?>


        <div>

            <form class="form-block module-main-block" name="new_album" action="?section=photos&amp;action=album_list" method="post">

                <legend><?php if ($_GET['action'] == 'album_edit') {echo "Изменение ";} else {echo "Добавление нового";}?> альбома</legend>

                <input type="hidden" name="id"<?php if (isset ($id)) {echo ' value="' . $id . '"';} ?>>

                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="title">Название альбома:</label>

                    <div class="form-input-vertical">

                        <input type="text" name="title" id="title"

                        <?php
                        if (isset ($title)) {

                            echo ' value="' . $title . '"';
                        }
                        ?>>

                    </div>

                </div>


                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="url">Адрес альбома:</label>

                    <div class="form-input-vertical">

                        <input type="text" name="url" id="url"

                        <?php
                        if (isset ($url)) {

                            echo ' value="' . $url . '"';
                        }
                        ?>>

                    </div>

                </div>


                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="description">Описание альбома:</label>

                    <div class="form-input-vertical">

                        <textarea name="description" class="span3" rows="3"><?php echo $description; ?></textarea>

                    </div>

                </div>


                <div class="form-group-vertical">

                    <div class="form-input-vertical">

                        <?php
                        ($_GET['action'] == 'album_edit') ? $name="update_album" : $name="add_album";
                        echo '<input class="button" type="submit" name="' . $name . '" value="сохранить">';
                        ?>

                    </div>
                </div>
            </form>
        </div>
<?php
}

?>
