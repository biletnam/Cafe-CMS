<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();


// Добавление нового счетчика
if ($_POST['add'] && empty ($error)) {

    clear_html ($_POST, array ($_POST['code']));

    $add_counter = "INSERT `" . DB_PREFIX . "_stats` (
        `title`,
        `date`,
        `code`,
        `status`)
    VALUES (
        '" . $_POST['title'] . "',
        '" . mktime () . "',
        '" . $_POST['code'] . "',
        '" . $_POST['status'] . "')";


    if (mysql_query ($add_counter)) {

        $message = 'Счетчик добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении счетчика';
    }
}



// Измененяем данные счетчика
if ($_POST['update'] && empty ($error)) {

    clear_html ($_POST, array ($_POST['code']));

    $update_counter = "UPDATE `" . DB_PREFIX . "_stats` SET
        `title`         = '" . $_POST['title'] . "',
        `code`          = '" . $_POST['code'] . "',
        `status`        = '" . $_POST['status'] . "'
    WHERE `id`          = '" . $_POST['id'] . "'";


    if (mysql_query ($update_counter)) {

        $message = 'Счетчик обновлен';

    } else {

        $error = 'Возникла ошибка при обновлении счетчика';
    }
}



// удаление счетчика
if ($_GET['action'] == 'delete') terminator ();
?>



    <div class="module-title">

        <h1>Статистика</h1>

    </div>


    <div class="module-menu">

        <a class="button" href="/admin/index.php?section=stats&amp;action=list" title="Список счетчиков">Список счетчиков</a>
        <a class="button" href="/admin/index.php?section=stats&amp;action=add" title="Добавить счетчик">Добавить счетчик</a>

    </div>


    <div class="module-messages">

        <?php print_message ($message, $error); ?>

    </div>


<?php
// Вывод списка счетчиков
    if ($_GET['action'] == 'list' && empty ($error)) {

    page_limit ('15');

    echo '
    <table class="module-main-block bottom20">

        <thead>

            <tr>

                <th>#</th>
                <th>Название</th>
                <th>Статус</th>
                <th>Дата установки</th>
                <th>Действия</th>

            </tr>

        </thead>

        <tbody>';

        $counter_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_stats` ORDER BY `id` ASC LIMIT " . $start_page . ", " . $end_page);

        $status = array (0 => 'отключен', 'активен');

        while ($row = mysql_fetch_array ($counter_list, MYSQL_ASSOC)) {

            echo '
            <tr>

                <td>' . $row['id'] . '</td>
                <td>' . $row['title'] . '</td>
                <td>' . $status[$row['status']] . '</td>
                <td>' . date ('d.m.Y H:i:s', $row['date']) . '</td>
                <td>';
                    if ($_SESSION['status'] == '1') {

                        echo '<a class="dashed" href="/admin/index.php?section=stats&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a> ';
                    }

                    echo '<a class="dashed" href="/admin/index.php?section=stats&amp;action=edit&amp;id=' . $row['id'] . '">изменить</a>
                </td>

            </tr>';
        }

        echo '
        </tbody>

    </table>

    <div class="pagination">

        <ul>';
            pager (ceil (mysql_num_rows (mysql_query ("SELECT id FROM `" . DB_PREFIX . "_users`")) / $end_page),'/admin/index.php?section=stat&action=list');
    echo '
        </ul>

    </div>';
}



// Добавление нового или изменение существующего счетчика
if ($_GET['action'] == 'add' || $_GET['action'] == 'edit' && empty ($error)) {

    // если выбрано изменение счетчика, делаем дополнительный запрос
    if ($_GET['action'] == 'edit' && isset ($_GET['id'])) {

        $sql_stats = @mysql_query ("SELECT * FROM `" . DB_PREFIX . "_stats` WHERE `id` = " . $_GET['id']);

        while ($row = mysql_fetch_array ($sql_stats, MYSQL_ASSOC)) {

            $id     = $row['id'];
            $title  = $row['title'];
            $date   = $row['date'];
            $code   = htmlspecialchars ($row['code']);
            $status = $row['status'];
        }
    }
?>


    <div class="module-main-block">

        <form class="form-block" name="new_counter" action="?section=stats&action=list" method="post">

            <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение ";} else {echo "Добавление нового";}?> счетчика</legend>

            <input type="hidden" name="id"<?php if (isset ($id)) {echo ' value="' . $id . '"';} ?>>

            <div class="form-group">

                <label class="form-label" for="title">Название счетчика:</label>

                <div class="form-input">

                    <input class="span3" type="text" name="title" id="title"
                    <?php if (isset ($title)) {echo ' value="' . $title . '"';} ?>>

                </div>

            </div>

            <div class="form-group">

                <label class="form-label" for="code">Код счетчика:</label>

                <div class="form-input">

                    <textarea class="span3" name="code" cols=10 rows=10 id="code"><?php echo $code; ?></textarea>

                </div>

            </div>

            <div class="form-group">

                <label class="form-label" for="login">Статус счетчика:</label>

                <div class="form-input radio"">

                    <input type="radio" name="status" <?php if ($status == "0") {echo 'checked ';} ?>value="0"> отключен
                    <input type="radio" name="status" <?php if ($status == "1") {echo 'checked ';} ?>value="1"> активен

                </div>

            </div>

            <div class="form-group">

                <div class="form-input">

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
?>
