<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Добавление города
if ($_POST['add']) {

    clear_html ($_POST, array ());

    $add_city = "INSERT `" . DB_PREFIX . "_weather` (
        `appid`,
        `period`,
        `units`,
        `title`,
        `city_id`,
        `template`)
    VALUES (    
        '" . $_POST['appid'] . "',
        '" . $_POST['period'] . "',
        '" . $_POST['units'] . "',
        '" . $_POST['title'] . "',
        '" . $_POST['city-id'] . "',
        '" . $_POST['template'] . "'
    )";


    if (mysql_query ($add_city)) {

        $message = 'Город добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении города';
    }
}


// Изменение города
if ($_POST['update']) {

    clear_html ($_POST, array ());

    $update_city = "UPDATE `" . DB_PREFIX . "_weather` SET
        `appid`     = '" . $_POST['appid'] . "',
        `period`    = '" . $_POST['period'] . "',
        `units`     = '" . $_POST['units'] . "',
        `title`     = '" . $_POST['title'] . "',
        `city_id`   = '" . $_POST['city-id'] . "',
        `template`  = '" . $_POST['template'] . "'
    WHERE `id`      = '" . $_POST['id'] . "'";


    if (mysql_query ($update_city)) {

        $message = 'Настройки города обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении настроек города';
    }
}
?>


    <div class="module-title">
        <h1>Погода</h1>
    </div>


    <div class="module-menu">
        <a class="button" href="?section=weather&amp;action=list">Просмотр</a>
        <a class="button" href="?section=weather&amp;action=add">Добавить город</a>
        <a class="button" href="?section=weather&amp;action=templates">Шаблоны</a>
    </div>


    <div class="module-messages">
        <?php print_message ($message, $error); ?>
    </div>


    <div class="module-main-block">

<?php
    if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {
?>
        <form class="form-block" action="?section=weather&action=list" method="post">

            <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение параметров";} else {echo "Добавление";}?> города</legend>

            <?php
            if ($_GET['action'] == 'edit') {

                $sql_list = mysql_query ("
                    SELECT `id`, `appid`, `title`, `city_id`, `period`, `units`, `template`
                    FROM `" . DB_PREFIX . "_weather`
                    WHERE `id` = '" . $_GET['id'] . "'
                    LIMIT 1
                ");

                $row = mysql_fetch_array ($sql_list, MYSQL_ASSOC);
            }
            ?>

            <input type="hidden" name="id"<?php if (isset ($row['id'])) {echo ' value="' . $row['id'] . '"';} ?>>

            <div class="form-group">

                <label class="form-label" for="api-key">Ваш API-ключ:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="api-key" name="appid"

                    <?php
                    if (isset ($row['appid'])) {

                        echo ' value="' . $row['appid'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="title">Город:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="title" name="title"

                    <?php
                    if (isset ($row['title'])) {

                        echo ' value="' . $row['title'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="city-id">id города:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="city-id" name="city-id"

                    <?php
                    if (isset ($row['city_id'])) {

                        echo ' value="' . $row['city_id'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="period">Как часто обновлять:</label>

                <div class="form-input">

                    <select style="width:100%" size="1" name="period" id="period">

                        <option <?php if ($row['period'] == '3600') {echo "selected";}?> value="3600">Каждый час</option>
                        <option <?php if ($row['period'] == '10800') {echo "selected";}?> value="10800">Каждые 3 часа</option>
                        <option <?php if ($row['period'] == '21600') {echo "selected";}?> value="21600">Каждые 6 часов</option>
                        <option <?php if ($row['period'] == '43200') {echo "selected";}?> value="43200">Каждые 12 часов</option>
                        <option <?php if ($row['period'] == '86400') {echo "selected";}?> value="86400">Каждые 24 часа</option>

                    </select>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="template">Шаблон:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="template" name="template"

                    <?php
                    if (isset ($row['template'])) {

                        echo ' value="' . $row['template'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="textarea">Система мер:</label>

                <div class="form-input radio">

                    <label><input type="radio" name="units" <?php if ($row['units'] == "metric") {echo 'checked ';} ?> value="metric"> Метрическая</label>
                    <label><input type="radio" name="units" <?php if ($row['units'] == "imperial") {echo 'checked ';} ?> value="imperial"> Британская</label>

                </div>

            </div>


            <div class="form-group">

                <div class="form-input">

                    <?php
                    ($_GET['action'] == 'edit') ? $name="update" : $name="add";
                    echo '<input class="button" type="submit" name="' . $name . '" value="Сохранить">';
                    ?>

                </div>

            </div>

        </form>
<?php        
    }


    if ($_GET['action'] == 'templates') {
?>
        <form class="form-block" action="?section=empty&action=form-g" method="post">

            <legend>Вертикальная формы</legend>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="text-input">Поле ввода:</label>

                <div class="form-input-vertical">

                    <input class="span4" type="text" id="text-input" name="login">

                </div>

            </div>


            <div class="form-group-vertical">

                <label class="form-label-vertical" for="password">Поле для пароля:</label>

                <div class="form-input-vertical">

                    <input class="span4" type="password" name="password" id="password">

                </div>

            </div>


            <div class="form-group-vertical">

                <label class="form-label-vertical" for="select">Выпадающий список:</label>

                <div class="form-input-vertical">

                    <select size="1" name="status" id="select">

                        <option "selected" value="0">Первый</option>
                        <option value="1">Второй</option>
                        <option value="2">Третий</option>
                        <option value="3">Четвертый</option>

                    </select>

                </div>

            </div>


            <div class="form-group-vertical">

                <label class="form-label-vertical" for="textarea">Область ввода:</label>

                <div class="form-input-vertical">

                    <textarea class="span4" name="text" rows="4" id="textarea"></textarea>

                </div>
            </div>


            <div class="form-group-vertical">

                <div class="form-input-vertical radio">

                    <label><input type="radio" name="radio" value="radio"> Переключатель</label>
                    <label><input type="checkbox" name="checkbox" value="checkbox"> Флажок</label>

                </div>

            </div>


            <div class="form-group-vertical">

                <div class="form-input-vertical">

                    <input class="button" type="submit" name="button" value="Кнопка">

                </div>
            </div>
        </form>
<?php
    }

    if ($_GET['action'] == 'list') {

        $limit = '10'; // количесвто результатов на страницу
        page_limit ($limit); // считаем количество страниц


        make_select (("`id`, `title`, `date`, `period`"), "weather", $where, "ORDER BY `id`", ("LIMIT " . $start_page . ", " . $end_page));

        if ($current_count < '1') {

            echo '<p style="margin-right:20px">Не добавлено ни одного города. <a href="/admin/index.php?section=weather&amp;action=add">
            Добавить?</a></p>';

        } else {
    ?>



            <div class="module-main-block">

                <table class="module-main-block bottom20">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Город</th>
                            <th>Обновлено</th>
                            <th>Период</th>
                            <th>Действия</th>

                        </tr>

                    </thead>

                    <tbody>


                    <?php
                    foreach ($sql_array as $row) {

                    if ($row['date']=="0") {$row['date'] = 'Не обновлялось';}

                    echo '
                    <tr>

                        <td>' . $row['id'] . '</a></td>

                        <td><a href="?section=weather&amp;action=view&amp;id=' . $row['id'] . '">' . $row['title'] . '</a></td>

                        <td>' . $row['date'] . '</td>

                        <td>' . $row['period']/60/60 . ' час.</td>

                        <td>

                            <a class="dashed" href="?section=weather&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a>
                            <a class="dashed" href="?section=weather&amp;action=edit&amp;id=' . $row['id'] . '">изменить</a>

                        </td>

                    </tr>';
                    }
                }


            echo '

                    </tbody>

                </table>

                <div class="pagination">

                    <ul>';
                        pager (ceil ($total_count) / $limit, '/admin/index.php?section=pages&action=list');
                echo '
                    </ul>

                </div>
            </div>';

    }
?>

    </div>