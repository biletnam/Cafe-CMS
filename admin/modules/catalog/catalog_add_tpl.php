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
                <option <?php if ($row['form'] == 'ip')  {echo "selected";}?> value="ip">индивидуальный предприниматель</option>
                <option <?php if ($row['form'] == 'p')   {echo "selected";}?> value="p">представительство</option>
                <option <?php if ($row['form'] == 'f')   {echo "selected";}?> value="f">филиал</option>
                <option <?php if ($row['form'] == 'tsj') {echo "selected";}?> value="tsj">товарищество собственников жилья</option>
                <option <?php if ($row['form'] == 'gu')  {echo "selected";}?> value="gu">государственное учреждение</option>
                <option <?php if ($row['form'] == 't')   {echo "selected";}?> value="t">товарищество</option>
                <option <?php if ($row['form'] == 'fh')  {echo "selected";}?> value="fh">фермерское хозяйство</option>
                <option <?php if ($row['form'] == 'ro')  {echo "selected";}?> value="ro">религиозная организация</option>
                <option <?php if ($row['form'] == 'kf')  {echo "selected";}?> value="kf">коммерческий фонд</option>
                <option <?php if ($row['form'] == 'nkf') {echo "selected";}?> value="nkf">некоммерческий фонд</option>
                <option <?php if ($row['form'] == 'oo')  {echo "selected";}?> value="oo">общественная организация</option>
                <option <?php if ($row['form'] == 'od')  {echo "selected";}?> value="od">общественное движение</option>
                <option <?php if ($row['form'] == 'up')  {echo "selected";}?> value="up">унитарное предприятие</option>
                <option <?php if ($row['form'] == 'prk') {echo "selected";}?> value="prk">производственный кооператив</option>
                <option <?php if ($row['form'] == 'pok') {echo "selected";}?> value="pok">потребительский кооператив</option>
                <option <?php if ($row['form'] == 'u')   {echo "selected";}?> value="u">учреждение</option>
                <option <?php if ($row['form'] == 'gk')  {echo "selected";}?> value="gk">государтсвенная корпорация</option>
                <option <?php if ($row['form'] == 'nkp') {echo "selected";}?> value="nkp">некоммерческое партнерство</option>
                <option <?php if ($row['form'] == 'ano') {echo "selected";}?> value="ano">автономная некоммерческая организация</option>
                <option <?php if ($row['form'] == 'ko')  {echo "selected";}?> value="ko">казачье общество</option>
                <option <?php if ($row['form'] == 'a')   {echo "selected";}?> value="a">ассоциация</option>
                <option <?php if ($row['form'] == 's')   {echo "selected";}?> value="s">союз</option>
                <option <?php if ($row['form'] == 'st')  {echo "selected";}?> value="st">садоводческое товарищества</option>

            </select>

        </div>

    </div>


    <div class="form-group">

        <label class="form-label" for="type">Раздел:</label>

        <div class="form-input">

            <select size="1" name="type" id="type" onchange="Load(); return false">

                <option selected value="0"></option>

                <?php
                $type_list = $db->getAll('SELECT id, title FROM ' . DB_PREFIX . '_catalog_categories');

                foreach ($type_list as $type) {

                    echo '<option ';

                    if ($row['type'] == $type['id']) {

                        echo 'selected ';$current_id = $type['id'];
                    }

                    echo 'value="' . $type['id'] . '">' . $type['title'] . '</option>';
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

                $category_list = $db->getAll('SELECT * FROM ' . DB_PREFIX . '_catalog_subcategories WHERE pid=?i', $current_id);

                foreach ($category_list as $categories_rows) {

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

                <input class="span3 file-select" type="file" id="files" name="file"

                <?php
                if (isset ($row['file'])) {

                    echo ' value="' . $row['file'] . '"';
                }
                ?>>

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
