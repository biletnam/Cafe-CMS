<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Добавление организации
if ($_POST['add']) {

    clear_html ($_POST, array ());

    $add_catalog = "INSERT `" . DB_PREFIX . "_catalog` (
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


    if (mysql_query ($add_catalog)) {

        $message = 'Организация добавлена';

    } else {

        $error = 'Возникла ошибка при добавлении организации';
    }
}


// Изменение организации
if ($_POST['update']) {

    clear_html ($_POST, array ());

    $update_catalog = "UPDATE `" . DB_PREFIX . "_catalog` SET
        `appid`   = '" . $_POST['appid'] . "',
        `title`   = '" . $_POST['title'] . "',
        `period`  = '" . $_POST['period'] . "',
        `date`    = '0',
        `from_id` = '" . $_POST['from-id'] . "',
        `to_id`   = '" . $_POST['to-id'] . "',
        `type`    = '" . $_POST['type'] . "'
    WHERE `id`    = '" . $_POST['id'] . "'";


    if (mysql_query ($update_catalog)) {

        $message = 'Данные организации обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении данных организации';
    }
}



// удаление организации
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();
?>


    <div class="module-title">
        <h1>Каталог организаций</h1>
    </div>


    <div class="module-menu">
        <a class="button" href="?section=catalog&amp;action=list">Список</a>
        <a class="button" href="?section=catalog&amp;action=add">Добавить организацию</a>
    </div>


    <div class="module-messages">
        <?php print_message ($message, $error); ?>
    </div>


    <div class="module-main-block">

<?php
    if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {

    define ("COORD", "56.182234, 50.888916");
?>

        <script src="http://yandex.st/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
        <script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
        <script src="/admin/modules/catalog/location-tool.js" type="text/javascript"></script>
        <script src="/admin/modules/catalog/cross-control.js" type="text/javascript"></script>
        <script src="/admin/modules/catalog/geolocation-button.js" type="text/javascript"></script>
        <script type="text/javascript">
        ymaps.ready(function () {
            var myMap = new ymaps.Map('YMapsID', {
                    center: [<?php echo COORD; ?>],
                    zoom: 12,
                    behaviors: ['default', 'scrollZoom']
                }),
                // Создание кнопки определения местоположения
                geolocationButton = new GeolocationButton({

                });

            myMap.controls
                .add(new CrossControl)
                .add('typeSelector', { top: 5, right: 5 });

            new LocationTool(myMap);
        });
        </script>

        <form class="form-block" action="?section=catalog&action=list" method="post">

            <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение данных";} else {echo "Добавление";}?> организации</legend>

            <?php
            if ($_GET['action'] == 'edit') {

                $sql_list = mysql_query ("
                    SELECT `id`, `appid`, `title`, `period`, `type`, `from_id`, `to_id`
                    FROM `" . DB_PREFIX . "_catalog`
                    WHERE `id` = '" . $_GET['id'] . "'
                    LIMIT 1
                ");

                $row = mysql_fetch_array ($sql_list, MYSQL_ASSOC);
            }
            ?>

            <input type="hidden" name="id"<?php if (isset ($row['id'])) {echo ' value="' . $row['id'] . '"';} ?>>

            <div class="form-group">

                <label class="form-label" for="title">Название:</label>

                <div class="form-input span6">

                    <input type="text" id="title" name="title"

                    <?php
                    if (isset ($row['title'])) {

                        echo ' value="' . $row['title'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="form" title="Организационно-правовая форма">ОПФ:</label>

                <div class="form-input">

                    <select style="width:100%" size="1" name="form" id="form">

                        <option <?php if ($row['form'] == 'tra') {echo "selected";}?> value="tra">ООО</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">ОАО</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">ЗАО</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">индивидуальный предприниматель</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">представительство</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">филиал</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">товарищество собственников жилья</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">государственное учреждение</option>
                        <option <?php if ($row['form'] == 'sub') {echo "selected";}?> value="sub">товарищество</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">фермерское хозяйство</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">религиозная организация</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">коммерческий фонд</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">некоммерческий фонд</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">общественная организация</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">общественное движение</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">унитарное предприятие</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">производственный кооператив</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">потребительский кооператив</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">учреждение</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">государтсвенная корпорация</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">некоммерческое партнерство</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">автономная некоммерческая организация</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">казачье общество</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">ассоциация</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">союз</option>
                        <option <?php if ($row['form'] == 'bus') {echo "selected";}?> value="bus">садоводческое товарищества</option>

                    </select>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="type">Раздел:</label>

                <div class="form-input">

                    <select style="width:100%" size="1" name="type" id="type">

                        <option <?php if ($row['type'] == 'bus') {echo "selected";}?> value="bus">берем из бд</option>

                    </select>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="category">Категория:</label>

                <div class="form-input">

                    <select style="width:100%" size="1" name="category" id="category">

                        <option <?php if ($row['category'] == 'helicopter') {echo "selected";}?> value="helicopter">зависит от пред селекта</option>

                    </select>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="boss">Руководитель:</label>

                <div class="form-input">

                    <input type="text" id="boss" name="boss"

                    <?php
                    if (isset ($row['boss'])) {

                        echo ' value="' . $row['boss'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="description">Описание:</label>

                <div class="form-input">

                    <textarea class="span6" cols=10 rows=10 id="description" name="description"><?php echo $row['description'];?></textarea>

                </div>

            </div>


            <div class="form-group-vertical">


                <div class="form-input span3" style="position:relative;text-align:center">

                        <input class="span3 file-select" type="file" id="files" name="file">

                        <a type="button" class="button span2">Выберите фотографию</a>

                        <div class="fileDisplayArea" id="fileDisplayArea"></div>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="phone">Телефон:</label>

                <div class="form-input span3">

                    <input type="text" id="phone" name="phone"

                    <?php
                    if (isset ($row['phone'])) {

                        echo ' value="' . $row['phone'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="phone2">Дополнительный:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="phone2" name="phone2"

                    <?php
                    if (isset ($row['phone2'])) {

                        echo ' value="' . $row['phone2'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="fax">Факс:</label>

                <div class="form-input span3">

                    <input type="text" id="fax" name="fax"

                    <?php
                    if (isset ($row['fax'])) {

                        echo ' value="' . $row['fax'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="email">E-mail:</label>

                <div class="form-input span3">

                    <input type="text" id="email" name="email"

                    <?php
                    if (isset ($row['email'])) {

                        echo ' value="' . $row['email'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="www">Сайт:</label>

                <div class="form-input span3">

                    <input type="text" id="www" name="www"

                    <?php
                    if (isset ($row['www'])) {

                        echo ' value="http://' . $row['www'] . '"';
                    }

                    else {
                        echo ' value="http://"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="city">Город:</label>

                <div class="form-input span3">

                    <input type="text" id="city" name="city"

                    <?php
                    if (isset ($row['city'])) {

                        echo ' value="' . $row['city'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="street">Улица:</label>

                <div class="form-input span3">

                    <input type="text" id="street" name="street"

                    <?php
                    if (isset ($row['street'])) {

                        echo ' value="' . $row['street'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="build">Дом:</label>

                <div class="form-input span3">

                    <input type="text" id="build" name="build"

                    <?php
                    if (isset ($row['build'])) {

                        echo ' value="' . $row['build'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="mapCenter">Координаты:</label>
                

                <div class="form-input span3">

                    <input type="text" id="mapCenter" name="coord"

                    <?php
                    if (isset ($row['coord'])) {

                        echo ' value="' . $row['coord'] . '"';
                    }
                    ?>><small>переместите объект в центр карты для получения его координат</small>

                	<div id="YMapsID" class="mapbox"></div>

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

                <script type="text/javascript">
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
<?php
    }



    // просмотр данных выбранной организации
    if ($_GET['action'] == 'view') {

        

    }

    if ($_GET['action'] == 'list') {

        $limit = '10'; // количесвто результатов на страницу
        page_limit ($limit); // считаем количество страниц


        make_select (("`id`, `title`, `date`, `from_id`, `to_id`, `period`"), "catalog", $where, "ORDER BY `id`", ("LIMIT " . $start_page . ", " . $end_page));

        if ($current_count < '1') {

            echo '<p style="margin-right:20px">Не добавлено ни одной организации. <a href="/admin/index.php?section=catalog&amp;action=add">
            Добавить?</a></p>';

    } else {
    ?>



            <table class="bottom20">

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

                    <td><a href="?section=catalog&amp;action=view&amp;id=' . $row['id'] . '">' . $row['title'] . '</a></td>

                    <td>' . date ("H:i d.m.Y", $row['date']) . '</td>

                    <td>' . $row['period']/60/60 . ' час.</td>

                    <td>

                        <a class="dashed" href="?section=catalog&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a>
                        <a class="dashed" href="?section=catalog&amp;action=edit&amp;id=' . $row['id'] . '">изменить</a>

                    </td>

                </tr>';
                }
            }


        echo '

                </tbody>

            </table>

            <div class="pagination">

                <ul>';
                    pager (ceil ($total_count) / $limit, '/admin/index.php?section=catalog&action=list');
            echo '
                </ul>

            </div>';
    }
?>

    </div>
