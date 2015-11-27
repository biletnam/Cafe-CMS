<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Добавление валюты
if ($_POST['add']) {

    clear_html ($_POST, array ());

    $add_currency = "INSERT `" . DB_PREFIX . "_currency` (
        `title`,
        `code`,
        `currency`,
        `period`,
        `date`,
        `cur_date`,
        `nominal`,
        `rate`)
    VALUES (
        '" . $_POST['title'] . "',
        '" . $_POST['code'] . "',
        '" . $_POST['currency'] . "',
        '" . $_POST['period'] . "',
        '',
        '" . $date . "',
        '" . $nominal . "',
        '" . $rate . "'
    )";


    if (mysql_query ($add_currency)) {

        $message = 'Валюта добавлена';

    } else {

        $error = 'Возникла ошибка при добавлении валюты';
    }
}



// удаление валюты
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();
?>



    <div class="module-title">
        <h1>Курсы валют</h1>
    </div>


    <div class="module-menu">
        <a class="button" href="?section=currency&amp;action=list">Список</a>
        <a class="button" href="?section=currency&amp;action=add">Добавить валюту</a>
    </div>


    <div class="module-messages">
        <?php print_message ($message, $error); ?>
    </div>


    <div class="module-main-block">


<?php
    if ($_GET['action'] == 'add') {
?>
        <form class="form-block" action="?section=currency&action=list" method="post">

            <legend>Добавление валюты</legend>

            <div class="form-group">

                <label class="form-label" for="title">Название:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="title" name="title">

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="code">Буквенный код:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="code" name="code">

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="currency">Выберите валюту:</label>

                <div class="form-input">

                    <select style="width:100%" size="1" name="currency" id="currency">

                        <option value="R01235">Доллар США</option>
                        <option value="R01239">Евро</option>
                        <option value="R01775">Швейцарский франк</option>
                        <option value="R01035">Фунт стерлингов Соединенного королевства</option>
                        <option value="R01820">Японских иен</option>
                        <option value="R01720">Украинских гривен</option>
                        <option value="R01335">Казахстанских тенге</option>
                        <option value="R01090">Белорусских рублей</option>

                    </select>

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

                <div class="form-input">

                    <input class="button" type="submit" name="add" value="Сохранить">

                </div>

            </div>

        </form>
<?php
    }



    if ($_GET['action'] == 'list') {

        if ($_GET['id']) {

            get_currency ($_GET['id']);
        }

        $limit = '10'; // количесвто результатов на страницу
        page_limit ($limit); // считаем количество страниц


        make_select (("`id`, `title`, `code`, `date`, `period`, `cur_date`, `nominal`, `rate`"), "currency", $where, "ORDER BY `id`", ("LIMIT " . $start_page . ", " . $end_page));

        if ($current_count < '1') {

            echo '<p style="margin-right:20px"><a href="/admin/index.php?section=currency&amp;action=add">Добавить?</a></p>';

    } else {
    ?>



        <div class="module-main-block">

            <table class="module-main-block bottom20">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Валюта</th>
                        <th>Обновлено</th>
                        <th>Период</th>
                        <th>Курс на дату</th>
                        <th>Номинал</th>
                        <th>Курс</th>
                        <th>Действия</th>

                    </tr>

                </thead>

                <tbody>


                <?php
                foreach ($sql_array as $row) {

                if ($row['date']=="0") {$row['date'] = 'Не обновлялось';}

                echo '
                <tr>

                    <td>' . $row['id'] . '</td>

                    <td>' . $row['title'] . '</td>

                    <td>' . date ("H:i d.m.Y", $row['date']) . '</td>

                    <td>' . $row['period']/60/60 . ' час.</td>

                    <td>' . date ("d.m.Y", $row['cur_date']) . '</td>

                    <td>' . $row['nominal'] . ' ' . $row['code'] . '</td>

                    <td>' . $row['rate'] . ' руб.</td>

                    <td>

                        <a class="dashed" href="?section=currency&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a>
                        <a class="dashed" href="?section=currency&amp;action=list&amp;id=' . $row['id'] . '">обновить</a>

                    </td>

                </tr>';
                }
            }


        echo '

                </tbody>

            </table>

            <div class="pagination">

                <ul>';
                    pager (ceil ($total_count) / $limit, '/admin/index.php?section=currency&action=list');
            echo '
                </ul>

            </div>
        </div>';

    }
?>

    </div>
