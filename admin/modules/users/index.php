<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Добавляем нового пользователя
if ($_POST['add'] && empty ($error)) {

    clear_html ($_POST, array ());
    $login = translit ($_POST['login']);

    $add_user = "INSERT `" . DB_PREFIX . "_users` (
        `login`,
        `password`,
        `reg_date`,
        `status`
        )
    VALUES (
        '" . $login . "',
        '" . md5 ($_POST['password']) . "',
        '" . timestamp  ($_POST['date']) . "',
        '" . $_POST['status'] . "'
        )";


    if (mysql_query ($add_user)) {

        $message = 'Пользователь добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении пользователя';
    }
}



// Изменение информации о пользователе
if ($_POST['update'] && empty ($error)) {

    clear_html ($_POST, array ());
    $login = translit ($_POST['login']);

    $update_user = "UPDATE `" . DB_PREFIX . "_users` SET
        `login`    = '" . $login . "',
        `password` = '" . md5 ($_POST['password']) . "',
        `reg_date` = '" . timestamp  ($_POST['date']) . "',
        `status`   = '" . $_POST['status'] . "'
    WHERE `id`     = '" . $_POST['id'] . "'";


    if (mysql_query ($update_user)) {

        $message = 'Информация о пользователе обновлена';

    } else {

        $error = 'Возникла ошибка при обновлении информации о пользователе';
    }
}



// удаление пользователя
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();
?>



    <div class="module-title">

        <h1>Пользователи</h1>

    </div>


    <div class="module-menu">

        <a class="button" href="?section=users&amp;action=list">Список пользователей</a>
        <a class="button" href="?section=users&amp;action=add">Добавить пользователя</a>

    </div>


    <div class="module-messages">

        <?php print_message ($message, $error); ?>

    </div>



<?php
// Вывод списка пользователей
if ($_GET['action'] == 'list' && empty ($error)) {

    page_limit ('15');

    echo '
    <table class="module-main-block bottom20">

        <thead>

            <tr>

                <th>#</th>
                <th>Логин</th>
                <th>Статус</th>
                <th>Дата регистрации</th>
                <th>Действия</th>

            </tr>

        </thead>

        <tbody>';


    $user_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_users` ORDER BY `id` ASC LIMIT " . $start_page . ", " . $end_page);

    $user_status_array = array (0 => "Не активирован", "Администратор", "Модератор", "Пользователь");

    while ($user_row = mysql_fetch_array ($user_list, MYSQL_ASSOC)) {

        echo '
            <tr>

                <td>' . $user_row['id'] . '</td>
                <td><a href="/admin/index.php?section=users&action=view&id=' . $user_row['id'] . '">' . $user_row['login'] . '</a></td>
                <td>' . $user_status_array[$user_row['status']] . '</td>
                <td>' . date ('d.m.Y', $user_row['reg_date']) . '</td>
                <td>
                    <a class="dashed" href="?section=users&amp;action=delete&amp;id=' . $user_row['id'] . '">удалить</a>
                    <a class="dashed" href="?section=users&amp;action=edit&amp;id=' . $user_row['id'] . '">изменить</a>
                </td>

            </tr>
        ';
    }

    echo '
        </tbody>

    </table>

    <div class="pagination">

        <ul>';
            pager (ceil (mysql_num_rows (mysql_query ("SELECT id FROM `" . DB_PREFIX . "_users`")) / $end_page));
    echo '
        </ul>

    </div>';
}



// Добавление нового или изменение информации о существующем пользователе
if (($_GET['action'] == 'add' || $_GET['action'] == 'edit') && empty ($error)) {


    // если выбрано изменение страницы, делаем дополнительный запрос
    if ($_GET['action'] == 'edit' && isset ($_GET['id'])) {

        $sql = @mysql_query ("SELECT * FROM `" . DB_PREFIX . "_users` WHERE `id` = " . $_GET['id'] . " LIMIT 1");

        $row = mysql_fetch_array ($sql, MYSQL_ASSOC);
    }
?>

    <div class="module-main-block">

        <form class="form-block" action="?section=users&action=list" method="post">

            <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение ";} else {echo "Добавление";}?> пользователя</legend>

            <input type="hidden" name="id"<?php if (isset ($row['id'])) {echo ' value="' . $row['id'] . '"';} ?>>

            <div class="form-group">

                <label class="form-label" for="login">Имя пользователя:</label>

                <div class="form-input">

                    <input class="span1" type="text" id="login" name="login" <?php if (isset ($row['login'])) {echo ' value="' . $row['login'] . '"';} ?>>

                </div>
            </div>

            <div class="form-group span6">

                <label class="form-label" for="pass">Пароль:</label>

                <div class="form-input">

                    <input class="span1" type="password" name="password" id="pass">

                    <a class="dashed" id="pass-view"
                    onclick="if (document.getElementById ('pass').type == 'password'){
                        document.getElementById ('pass').type = 'text';
                        document.getElementById ('pass-view').innerHTML = 'скрыть';}
                        else{document.getElementById ('pass').type = 'password';
                        document.getElementById ('pass-view').innerHTML = 'показать';
                    }">показать</a>

                </div>

            </div>

            <div class="form-group">

                <label class="form-label" for="status">Статус:</label>

                <div class="form-input">

                    <select style="width:100%" size="1" name="status">

                        <option <?php if ($row['status'] == '0') {echo "selected";}?> value="0">Не активирован</option>
                        <option <?php if ($row['status'] == '1') {echo "selected";}?> value="1">Администратор</option>
                        <option <?php if ($row['status'] == '2') {echo "selected";}?> value="2">Модератор</option>
                        <option <?php if ($row['status'] == '3') {echo "selected";}?> value="3">Пользователь</option>

                    </select>

                </div>

            </div>

            <div class="form-group">

                <label class="form-label" for="date">Дата регистрации:</label>

                <div class="form-input">

                    <input class="span1" type="text" name="date"

                    <?php
                    if (isset ($row['reg_date'])) {

                        echo ' value="' . date('H:i:s d.m.Y', $row['reg_date']) . '"';

                    } else {

                        echo ' value="' . date ('H:i:s d.m.Y') . '"';
                    }
                    ?>>

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



// Просмотр информации о пользователе
if ($_GET['action'] == 'view' && empty ($error)) {
?>

    <div class="module-main-block">

    <?php
    $user_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_users` WHERE `id` = " . $_GET['id'] . " LIMIT 1");

    $user_status_array = array (0 => "Не активирован", "Администратор", "Модератор", "Пользователь");

    $user_row = mysql_fetch_array ($user_list, MYSQL_ASSOC);

    echo '<h2 class="bottom20">' . $user_row['login'] . '</h2>

          <p class="bottom20"><strong>Статус</strong>: ' . $user_status_array[$user_row['status']] . '</p>

          <p class="bottom20"><strong>Дата регистрации</strong>: ' . date ('d.m.Y', $user_row['reg_date']) . '</p>

          <p class="bottom20"><strong>E-mail</strong>: ' . $user_row['email'] . '</p>';

    if ($_SESSION['status'] == '1') {

        echo '<a class="dashed" href="?section=users&amp;action=delete&amp;id=' . $user_row['id'] . '">удалить</a> ';
    }

    echo '<a class="dashed" href="?section=users&amp;action=edit&amp;id=' . $user_row['id'] . '">изменить</a>';

    echo '</div>';
}
?>
