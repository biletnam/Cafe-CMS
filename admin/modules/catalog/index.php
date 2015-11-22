<?php
// TODO убрать/переделать
// выбираем список категорий в зависимости от раздела
// используется для аякс-запроса при добавлении записи
if (!empty ($_POST['category_list'])) {

    include $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/functions.php';

    check_install ();
    db_connect ();

    $rows = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_catalog_subcategories` WHERE `pid` = '" . $_POST['category_list'] . "'");

    while ($rows2 = mysql_fetch_array ($rows)) {

        echo '<option value="' . $rows2['id'] . '">' . $rows2['title'] . '</option>';

    }

    exit;
}



defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


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

    clear_html ($_POST, array ());

    $lat = explode(', ', $_POST['coord'])['0'];
    $lon = explode(', ', $_POST['coord'])['1'];

    $filename = mktime ();

    $add_catalog = "INSERT `" . DB_PREFIX . "_catalog` (
        `id`,
        `title`,
        `url`,
        `form`,
        `type`,
        `category`,
        `boss`,
        `description`,
        `file`,
        `phone`,
        `phone2`,
        `fax`,
        `email`,
        `www`,
        `city`,
        `street`,
        `build`,
        `lat`,
        `lon`
    )
    VALUES (
        '" . $_POST['id'] . "',
        '" . $_POST['title'] . "',
        '" . translit ($_POST['title']) . "',
        '" . $_POST['form'] . "',
        '" . $_POST['type'] . "',
        '" . $_POST['category'] . "',
        '" . $_POST['boss'] . "',
        '" . $_POST['description'] . "',
        '" . $filename . ".jpg',
        '" . $_POST['phone'] . "',
        '" . $_POST['phone2'] . "',
        '" . $_POST['fax'] . "',
        '" . $_POST['email'] . "',
        '" . $_POST['www'] . "',
        '" . $_POST['city'] . "',
        '" . $_POST['street'] . "',
        '" . $_POST['build'] . "',
        '" . $lat . "',
        '" . $lon . "')";

    if (!empty ($_FILES["file"]["name"])) {

        // Загружаем фотографию, уменьшаем и делаем квадратное превью
        file_upload  (array("jpeg","jpg"), "image/jpeg", "../upload/catalog/original/" . $filename . ".jpg");
        resize_pic   ($_FILES["file"]["tmp_name"], "800", "600", "../upload/catalog/800-600/". $filename .".jpg", "75");
        crop_preview ($_FILES["file"]["tmp_name"], "200", "../upload/catalog/200-200/". $filename .".jpg", "75");
    }


    if (mysql_query ($add_catalog)) {

        $message = 'Организация добавлена';

    } else {

        $error = 'Возникла ошибка при добавлении организации' . mysql_error();
    }
}


// Изменение организации
if ($_POST['update']) {

    clear_html ($_POST, array ());

    $lat = explode(', ', $_POST['coord'])['0'];
    $lon = explode(', ', $_POST['coord'])['1'];

    $filename = mktime ();

    $update_catalog   = "UPDATE `" . DB_PREFIX . "_catalog` SET
        `title`       = '" . $_POST['title'] . "',
        `url`         = '" . translit ($_POST['title']) . "',
        `form`        = '" . $_POST['form'] . "',
        `type`        = '" . $_POST['type'] . "',
        `category`    = '" . $_POST['category'] . "',
        `boss`        = '" . $_POST['boss'] . "',
        `description` = '" . $_POST['description'] . "',";

    if (!empty ($_FILES["file"]["name"])) {

        $update_catalog .= "`file` = '" . $filename . ".jpg',";
    }

        $update_catalog .= "
        `phone`       = '" . $_POST['phone'] . "',
        `phone2`      = '" . $_POST['phone2'] . "',
        `fax`         = '" . $_POST['fax'] . "',
        `email`       = '" . $_POST['email'] . "',
        `www`         = '" . $_POST['www'] . "',
        `city`        = '" . $_POST['city'] . "',
        `street`      = '" . $_POST['street'] . "',
        `build`       = '" . $_POST['build'] . "',
        `lat`         = '" . $lat . "',
        `lon`         = '" . $lon . "'
    WHERE `id`        = '" . $_POST['id'] . "'";


    if (!empty ($_FILES["file"]["name"])) {

        // Загружаем фотографию, уменьшаем и делаем квадратное превью
        file_upload  (array("jpeg","jpg"), "image/jpeg", "../upload/catalog/original/" . $filename . ".jpg");
        resize_pic   ($_FILES["file"]["tmp_name"], "800", "600", "../upload/catalog/800-600/". $filename .".jpg", "75");
        crop_preview ($_FILES["file"]["tmp_name"], "200", "../upload/catalog/200-200/". $filename .".jpg", "75");
    }


    if (mysql_query ($update_catalog)) {

        $message = 'Данные организации обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении данных организации' . mysql_error();
    }
}



// удаление организации
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();



// Добавление нового раздела
if ($_POST['add_category'] && empty ($error)) {

    clear_html ($_POST, array ());

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $add_category = "INSERT `" . DB_PREFIX . "_catalog_categories` (
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
        UPDATE `" . DB_PREFIX . "_catalog_categories`
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

        $delete = "DELETE FROM `" . DB_PREFIX . "_catalog_categories` WHERE `id` = " . $_GET['id'] . " LIMIT 1";


        if (mysql_query ($delete)) {

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

    clear_html ($_POST, array ());

    $subcategory_count = count ($_POST['title']);

	for ($i=0; $i<=$subcategory_count-1; $i++) {

        (empty ($_POST['url'][$i])) ? ($url = translit ($_POST['title'][$i])) : ($url = translit ($_POST['url'][$i]));

		$add_subcategory = "INSERT `" . DB_PREFIX . "_catalog_subcategories` (
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
?>


    <div class="module-title">
        <h1>Каталог организаций</h1>
    </div>


    <div class="module-menu">
        <a class="button" href="?section=catalog&amp;action=list">Список</a>
        <a class="button" href="?section=catalog&amp;action=add">Добавить организацию</a>
        <a class="button" href="?section=catalog&amp;action=category">Разделы и категории</a>
        <a class="button" href="?section=catalog&amp;action=settings">Настройки</a>
    </div>


    <div class="module-messages">
        <?php print_message ($message, $error); ?>
    </div>


    <div class="module-main-block">

<?php
// выводим форму добавления/изменения организации
if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {

    // если изменение - делаем запрос в ббд
    if ($_GET['action'] == 'edit') {

        $sql_list = mysql_query ("
            SELECT *
            FROM `" . DB_PREFIX . "_catalog`
            WHERE `id` = '" . $_GET['id'] . "'
            LIMIT 1
        ");

        $row = mysql_fetch_array ($sql_list, MYSQL_ASSOC);
    }
?>

    <!-- для вывода карты -->
    <script type="text/javascript" src="http://yandex.st/jquery/1.8.0/jquery.min.js"></script>
    <script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
    <script type="text/javascript" src="/admin/modules/catalog/location-tool.js"></script>
    <script type="text/javascript" src="/admin/modules/catalog/cross-control.js"></script>
    <script type="text/javascript" src="/admin/modules/catalog/geolocation-button.js"></script>
    <script type="text/javascript">

    ymaps.ready(function () {

        var myMap = new ymaps.Map('YMapsID', {

                center: [
                <?php
                if (isset ($row['lat']) && isset ($row['lon'])) {

                    echo $row['lat'] .', ' .$row['lon'];
                    $zoom='17';

                }

                else {

                    echo DEFAULT_COORD;
                }
                ?>
                ],

                zoom: <?php
                if (isset ($zoom)) {

                    echo $zoom;
                }

                else {

                    echo '9';
                }
                ?>,
                behaviors: ['default', 'scrollZoom']
            }),

            geolocationButton = new GeolocationButton({});

        myMap.controls
            .add(new CrossControl)
            .add('typeSelector', { top: 5, right: 5 });

        new LocationTool(myMap);
    });
    </script>


    <!-- функции для обновления значений в списке "Категории"
    в зависимости от значения списка "Раздел" -->
    <script type="text/javascript">
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
            req.open("POST", "/admin/modules/catalog/index.php", true);
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


    <form class="form-block" action="?section=catalog&action=list" enctype="multipart/form-data" method="post">

        <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение данных";} else {echo "Добавление";}?> организации</legend>

        <input type="hidden" name="id"<?php if (isset ($row['id'])) {echo ' value="' . $row['id'] . '"';} ?>>


        <div class="form-group">

            <label class="form-label" for="title">Название:</label>

            <div class="form-input span6">

                <input type="text" id="title" name="title"

                <?php
                if (isset ($row['title'])) {

                    echo ' value="' . htmlspecialchars($row['title']) . '"';
                }
                ?>>

            </div>

        </div>


        <div class="form-group">

            <label class="form-label" for="form" title="Организационно-правовая форма">ОПФ:</label>

            <div class="form-input">

                <select style="width:100%" size="1" name="form" id="form">

                    <option <?php if ($row['form'] == 'ooo') {echo "selected";}?> value="ooo">ООО</option>
                    <option <?php if ($row['form'] == 'oao') {echo "selected";}?> value="oao">ОАО</option>
                    <option <?php if ($row['form'] == 'zao') {echo "selected";}?> value="zao">ЗАО</option>
                    <option <?php if ($row['form'] == 'ip') {echo "selected";}?> value="ip">индивидуальный предприниматель</option>
                    <option <?php if ($row['form'] == 'p') {echo "selected";}?> value="p">представительство</option>
                    <option <?php if ($row['form'] == 'f') {echo "selected";}?> value="f">филиал</option>
                    <option <?php if ($row['form'] == 'tsj') {echo "selected";}?> value="tsj">товарищество собственников жилья</option>
                    <option <?php if ($row['form'] == 'gu') {echo "selected";}?> value="gu">государственное учреждение</option>
                    <option <?php if ($row['form'] == 't') {echo "selected";}?> value="t">товарищество</option>
                    <option <?php if ($row['form'] == 'fh') {echo "selected";}?> value="fh">фермерское хозяйство</option>
                    <option <?php if ($row['form'] == 'ro') {echo "selected";}?> value="ro">религиозная организация</option>
                    <option <?php if ($row['form'] == 'kf') {echo "selected";}?> value="kf">коммерческий фонд</option>
                    <option <?php if ($row['form'] == 'nkf') {echo "selected";}?> value="nkf">некоммерческий фонд</option>
                    <option <?php if ($row['form'] == 'oo') {echo "selected";}?> value="oo">общественная организация</option>
                    <option <?php if ($row['form'] == 'od') {echo "selected";}?> value="od">общественное движение</option>
                    <option <?php if ($row['form'] == 'up') {echo "selected";}?> value="up">унитарное предприятие</option>
                    <option <?php if ($row['form'] == 'prk') {echo "selected";}?> value="prk">производственный кооператив</option>
                    <option <?php if ($row['form'] == 'pok') {echo "selected";}?> value="pok">потребительский кооператив</option>
                    <option <?php if ($row['form'] == 'u') {echo "selected";}?> value="u">учреждение</option>
                    <option <?php if ($row['form'] == 'gk') {echo "selected";}?> value="gk">государтсвенная корпорация</option>
                    <option <?php if ($row['form'] == 'nkp') {echo "selected";}?> value="nkp">некоммерческое партнерство</option>
                    <option <?php if ($row['form'] == 'ano') {echo "selected";}?> value="ano">автономная некоммерческая организация</option>
                    <option <?php if ($row['form'] == 'ko') {echo "selected";}?> value="ko">казачье общество</option>
                    <option <?php if ($row['form'] == 'a') {echo "selected";}?> value="a">ассоциация</option>
                    <option <?php if ($row['form'] == 's') {echo "selected";}?> value="s">союз</option>
                    <option <?php if ($row['form'] == 'st') {echo "selected";}?> value="st">садоводческое товарищества</option>

                </select>

            </div>

        </div>


        <div class="form-group">

            <label class="form-label" for="type">Раздел:</label>

            <div class="form-input">

                <select size="1" name="type" id="type" onchange="Load(); return false">

                    <option selected value="0"></option>

                    <?php
                    $type_list = mysql_query ("SELECT title, id FROM `" . DB_PREFIX . "_catalog_categories`");

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


        <div class="form-group">

            <label class="form-label" for="category">Категория:</label>

            <div class="form-input">

                <select size="1" name="category" id="category">

                <?php
                if ($_GET['action'] == "add") {

                    echo '<option value="0">Выберите раздел</option>';

                } else {

                    $category_list = mysql_query ("
                        SELECT *
                        FROM `" . DB_PREFIX . "_catalog_subcategories`
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

                    <div class="fileDisplayArea" id="fileDisplayArea">
                        <?php
                        if (isset($row['file'])) {
                            echo '<img src="/upload/catalog/800-600/' . $row['file'] . '">';
                        }
                        ?>
                    </div>

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

                    echo ' value="' . $row['www'] . '"';
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

                else {

                    echo ' value="' . DEFAULT_CITY . '"';

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

    $catalog_view = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_catalog`
        WHERE `id` = '" . $_GET['id'] . "'
        LIMIT 1
    ");

    $row = mysql_fetch_array ($catalog_view, MYSQL_ASSOC);

    echo '
    <div class="module-main-block">

        <h1 class="bottom20">' . $row['title'] . '</h1>

        <div class="bottom20">

            <p>' . $row['description'] . '</p>
            <p><img src="/upload/catalog/800-600/' . $row['file'] . '"></p>
            <p>Руководитель: ' . $row['boss'] . '</p>
            <p>Телефон: ' . $row['phone'] . ', ' . $row['phone2'] . '</p>
            <p>Факс: ' . $row['fax'] . '</p>
            <p>E-mail: ' . $row['email'] . '</p>
            <p>Сайт: ' . $row['www'] . '</p>
            <p>Адрес: ' . $row['city'] . ', ' . $row['street'] . ', ' . $row['build'] . '</p>
            <p>На карте: ' . $row['lat'] . ', ' . $row['lon'] . '</p>

        </div>

    </div>';
}



// вывод списка организаций
if ($_GET['action'] == 'list') {

    $limit = '10'; // количесвто результатов на страницу
    page_limit ($limit); // считаем количество страниц


    make_select (("`id`, `title`, `phone`, `city`, `street`, `build`"), "catalog", $where, "ORDER BY `id`", ("LIMIT " . $start_page . ", " . $end_page));

    if ($current_count < '1') {

        echo '<p style="margin-right:20px">Не добавлено ни одной организации. <a class="dashed" href="/admin/index.php?section=catalog&amp;action=add">Добавить?</a></p>';

} else {
?>



        <table class="bottom20">

            <thead>

                <tr>

                    <th>#</th>
                    <th>Название</th>
                    <th>Телефон</th>
                    <th>Адрес</th>
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

                <td>' . $row['phone'] . '</td>

                <td>' . $row['city'] . ', ' . $row['street'] . ', ' . $row['build'] . '</td>

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




// форма добавления или редактирования раздела
if ($_GET['id'] && $_GET['action'] == 'edit_category' || $_GET['action'] == 'add_category') {

    $category_list = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_catalog_categories`
        WHERE `id` = '" . $_GET['id'] . "'
    ");


    $row = mysql_fetch_array ($category_list, MYSQL_ASSOC);
?>


    <form class="form-block module-main-block" action="?section=catalog&action=category" method="post">

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

<?php
}



// Вывод списка разделов и категорий
if ($_GET['action'] == 'category') {
?>


    <div class="module-submenu">

        <a class="dashed" href="/admin/index.php?section=catalog&amp;action=add_category">Добавить новый раздел</a>

    </div>


    <div class="module-main-block">

        <table class="bottom20">

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
            FROM `" . DB_PREFIX . "_catalog_categories`
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
                        <a class="dashed" href="?section=catalog&amp;action=delete_category&amp;id=' . $row['id'] . '">удалить</a>
                        <a class="dashed" href="?section=catalog&amp;action=edit_category&amp;id=' . $row['id'] . '">изменить</a></td>
                    <td>';

                    $subcategory_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_catalog_subcategories` WHERE `pid` = '".$row['id']."'");

                    while ($subrow = mysql_fetch_array ($subcategory_list, MYSQL_ASSOC)) {

                        echo '<span class="subcategory">' . $subrow['title'] . '</span>';
                    }

                    echo '<a class="dashed" href="?section=catalog&amp;action=add_subcategory&amp;id=' . $row['id'] . '">добавить</a>

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


    <form class="form-block" action="?section=catalog&action=category" method="post">

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


    <script type="text/javascript">
    function newField() {

        document.getElementById('new-subcat').outerHTML='<div class="form-group" style="padding-top:20px;border-top:1px #ccc dashed"><label class="form-label" for="title">Название категории:</label><div class="form-input"><input class="span1" name="title[]" type="text" id="title"></div></div><div class="form-group"><label class="form-label" for="url">Адрес:</label><div class="form-input"><input class="span1" name="url[]" type="text" id="url"></div></div><div class="form-group"><label class="form-label" for="position">Порядковый номер:</label><div class="form-input"><input name="position[]" type="text" id="position"></div></div><div id="new-subcat"></div>';

    }
    </script>
	
<?php
}



// настройки
if ($_GET['action'] == 'settings') {
?>

    <form class="form-block" action="?section=catalog&action=settings" method="post">

        <legend>Настройки каталога</legend>

        <div class="form-group">

            <label class="form-label" for="default-city">Город по умолчанию:</label>

            <div class="form-input">

                <input name="default-city" type="text" id="default-city" value="<?php echo DEFAULT_CITY; ?>">

            </div>

        </div>


        <div class="form-group">

            <label class="form-label" for="default-coord">Координаты:</label>

            <div class="form-input">

                <input name="default-coord" type="text" id="default-coord" value="<?php echo DEFAULT_COORD; ?>">

            </div>

        </div>


        <div class="form-group">

            <div class="form-input">

                <input class="button" type="submit" name="update-settings" value="Сохранить">

            </div>

        </div>

    </form>

<?php
}
?>

</div>
