<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Добавление маршрута
if ($_POST['add']) {

    clear_html ($_POST, array ());

    $add_city = "INSERT `" . DB_PREFIX . "_raspisanie` (
        `appid`,
        `title`,
        `period`,
        `from_id`,
        `to_id`,
        `type`)
    VALUES (    
        '" . $_POST['appid'] . "',
        '" . $_POST['title'] . "',
        '" . $_POST['period'] . "',
        '" . $_POST['from-id'] . "',
        '" . $_POST['to-id'] . "',
        '" . $_POST['type'] . "'
    )";


    if (mysql_query ($add_city)) {

        $message = 'Маршрут добавлен';

    } else {

        $error = 'Возникла ошибка при добавлении маршрута';
    }
}


// Изменение маршрута
if ($_POST['update']) {

    clear_html ($_POST, array ());

    $update_city = "UPDATE `" . DB_PREFIX . "_raspisanie` SET
        `appid`   = '" . $_POST['appid'] . "',
        `title`   = '" . $_POST['title'] . "',
        `period`  = '" . $_POST['period'] . "',
        `date`    = '0',
        `from_id` = '" . $_POST['from-id'] . "',
        `to_id`   = '" . $_POST['to-id'] . "',
        `type`    = '" . $_POST['type'] . "'
    WHERE `id`    = '" . $_POST['id'] . "'";


    if (mysql_query ($update_city)) {

        $message = 'Настройки маршрута обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении настроек маршрута';
    }
}



// удаление маршрута
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();
?>


    <div class="module-title">
        <h1>Расписания</h1>
    </div>


    <div class="module-menu">
        <a class="button" href="?section=raspisanie&amp;action=list">Маршруты</a>
        <a class="button" href="?section=raspisanie&amp;action=add">Добавить маршрут</a>
    </div>


    <div class="module-messages">
        <?php print_message ($message, $error); ?>
    </div>


    <div class="module-main-block">

<?php
    if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {
?>
        <form class="form-block" action="?section=raspisanie&action=list" method="post">

            <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение параметров";} else {echo "Добавление";}?> маршрута</legend>

            <?php
            if ($_GET['action'] == 'edit') {

                $sql_list = mysql_query ("
                    SELECT `id`, `appid`, `title`, `period`, `type`, `from_id`, `to_id`
                    FROM `" . DB_PREFIX . "_raspisanie`
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

                <label class="form-label" for="title">Название маршрута:</label>

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

                <label class="form-label" for="from-id">Пункт отправления:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="from-id" name="from-id"

                    <?php
                    if (isset ($row['from_id'])) {

                        echo ' value="' . $row['from_id'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="to_id">Пункт прибытия:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="to-id" name="to-id"

                    <?php
                    if (isset ($row['to_id'])) {

                        echo ' value="' . $row['to_id'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="type">Тип транспорта:</label>

                <div class="form-input">

                    <select style="width:100%" size="1" name="type" id="type">

                        <option <?php if ($row['type'] == 'suburban') {echo "selected";}?> value="suburban">электричка</option>
                        <option <?php if ($row['type'] == 'train') {echo "selected";}?> value="train">поезд</option>
                        <option <?php if ($row['type'] == 'bus') {echo "selected";}?> value="bus">автобус</option>
                        <option <?php if ($row['type'] == 'plane') {echo "selected";}?> value="plane">самолет</option>
                        <option <?php if ($row['type'] == 'helicopter') {echo "selected";}?> value="helicopter">вертолет</option>
                        <option <?php if ($row['type'] == 'sea') {echo "selected";}?> value="plane">морской транспорт</option>
                        <option <?php if ($row['type'] == 'river') {echo "selected";}?> value="plane">речной транспорт</option>

                    </select>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="period">Как часто обновлять:</label>

                <div class="form-input">

                    <select style="width:100%" size="1" name="period" id="period">

                        <option <?php if ($row['period'] == '10800') {echo "selected";}?> value="10800">Каждые 3 часа</option>
                        <option <?php if ($row['period'] == '21600') {echo "selected";}?> value="21600">Каждые 6 часов</option>
                        <option <?php if ($row['period'] == '43200') {echo "selected";}?> value="43200">Каждые 12 часов</option>
                        <option <?php if ($row['period'] == '86400') {echo "selected";}?> value="86400">Каждые 24 часа</option>

                    </select>

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



    // просмотр данных выбранного маршрута
    if ($_GET['action'] == 'view') {
    
    
        get_raspisanie ($_GET['id']);
$raspisanieData = $raspisanie;


        echo '<div class="module-main-block">

            <table class="module-main-block bottom20">

                <caption>' . $row['title'] . ' (' . $row['type'] . ')</caption>
                <thead>

                    <tr>

                        <th>Маршрут</th>
                        <th>Отправление</th>
                        <th>Прибытие</th>
                        <th>Дни</th>
                        <th>Остановки</th>

                    </tr>

                </thead>

                <tbody>';


    for ($i=0; $i<$raspisanieData['pagination']['total']; $i++){
		echo "
<tr>
	    <td>" . $raspisanieData['threads'][$i]['thread']['short_title'] . "</td>
		<td>" . substr($raspisanieData['threads'][$i]['departure'], 0, 5) . "</td>
		<td>" . substr($raspisanieData['threads'][$i]['arrival'], 0, 5) ."</td>
		<td>" . $raspisanieData['threads'][$i]['days'] . "</td>
		<td>" . $raspisanieData['threads'][$i]['stops'] . "</td>
	</tr>";
	}
echo '

                </tbody>

            </table>
        </div>';

    }

    if ($_GET['action'] == 'list') {

        $limit = '10'; // количесвто результатов на страницу
        page_limit ($limit); // считаем количество страниц


        make_select (("`id`, `title`, `date`, `from_id`, `to_id`, `period`"), "raspisanie", $where, "ORDER BY `id`", ("LIMIT " . $start_page . ", " . $end_page));

        if ($current_count < '1') {

            echo '<p style="margin-right:20px">Не добавлено ни одного маршрута. <a href="/admin/index.php?section=raspisanie&amp;action=add">
            Добавить?</a></p>';

    } else {
    ?>



        <div class="module-main-block">

            <table class="module-main-block bottom20">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Маршрут</th>
                        <th>Обновлено</th>
                        <th>Период</th>
                        <th>Действия</th>

                    </tr>

                </thead>

                <tbody>


                <?php
                foreach ($sql_array as $row) {

                echo '
                <tr>

                    <td>' . $row['id'] . '</td>

                    <td><a href="?section=raspisanie&amp;action=view&amp;id=' . $row['id'] . '">' . $row['title'] . '</a></td>

                    <td>' . date ("H:i d.m.Y", $row['date']) . '</td>

                    <td>' . $row['period']/60/60 . ' час.</td>

                    <td>

                        <a class="dashed" href="?section=raspisanie&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a>
                        <a class="dashed" href="?section=raspisanie&amp;action=edit&amp;id=' . $row['id'] . '">изменить</a>

                    </td>

                </tr>';
                }
            }


        echo '

                </tbody>

            </table>

            <div class="pagination">

                <ul>';
                    pager (ceil ($total_count) / $limit, '/admin/index.php?section=raspisanie&action=list');
            echo '
                </ul>

            </div>
        </div>';

    }
?>

    </div>
