<?php
/*
################################################################################
###                                                                          ###
###                              Cafe CMS                                    ###
###                        http://cms.rad-li.ru                              ###
###                         mailto:rad-li@ya.ru                              ###
###                                                                          ###
################################################################################
*/

/* Вывод сообщений
 * Сообщения выводятся после какой-либо операции, например, добавление или
 * удаление записи. Два типа сообщений: $message - успешные операции
 * и $error - ошибки. Текст сообщений передается при выполнении соответствующих
 * операций в модулях. Сообщения выводятся в соответсвующем блоке модуля,
 * а также записываются в журнал действий с помощью функции log_write ().
 */
function print_message ($message, $error) {

    // Записываем в журнал и выводим сообщение об ошибке
    if (!empty($error)) {

        log_write ($error, '0', '1');

        echo '<div class="print_error">' . $error . '</div>';
    }


    // Записываем в журнал и выводим сообщение об успешной операции
    if (!empty($message)) {

        log_write ($message, '1', '2');

        echo '<div class="print_done">' . $message . '</div>';
    }
}



/* Запись действий в журнал
 *
 * Входные данные:
 * $text   - тип действия пользователя,
 * $status - статус действия (успех 1 или ошибка 0),
 * $level  - уровень детализации.
 *
 * Всего три уровня детализации:
 * 0 - ведение журнала отключено,
 * 1 - записываются только самые важные события,
 * 2 - записываются все события.
 *
 * В журнал пишутся следующие данные:
 * user   - id пользователя,
 * date   - дата и время выполнения действия,
 * type   - тип действия,
 * status - статус действия (успех или ошибка),
 * ip     - текущий ip-адрес пользователя,
 * ua     - user-agent пользователя
 */
function log_write ($text, $status, $level) {

    if ($level <= LOG_LEVEL) {

        if (empty ($_SESSION['id'])) $_SESSION['id'] = '0';

        $add_log = "INSERT `" . DB_PREFIX . "_logs` (
            `user`,
            `date`,
            `type`,
            `status`,
            `ip`
        )
        VALUES (
            '" . $_SESSION['id'] . "',
            '" . mktime () . "',
            '" . $text . "',
            '" . $status . "',
            '" . $_SERVER['REMOTE_ADDR'] . "'
        )";

        @mysql_query ($add_log);
    }
}



/* Проверяем, установлена ли CMS
 * Если файл /config.php не существует, предлагаем начать установку CMS
 * Если файл /config.php существует, включаем его и продолжаем работу
 */
function check_install () {

    (include $_SERVER['DOCUMENT_ROOT'] . '/config.php')
    or die (header ('Location: /install/index.php'));
}



/* Подключение к базе данных
 * Данные для подключения находятся в файле /config.php.
 * При ошибках подключения или выбора БД, выводятся соответствующие сообщения.
 * Если успешно подключились и выбралу нужную базу, устанавливаем кодировку utf8
 */
function db_connect () {

    @mysql_connect (DB_SERVER, DB_LOGIN , DB_PASSWORD) or die ('Не удалось подключиться к серверу БД!');
    @mysql_select_db (DB_NAME) or die ('Невозможно открыть базу данных!');
    @mysql_query ('SET NAMES UTF8');
}



/* Экранируем кавычки и удаляем пробелы
 * $input_data - может иметь стрковое значение или быть массивом. Если это
 * массив, разбираем его, и к каждому элементу применяем функцию еще раз
 * $output_data - очищенные данные
 */
function clear_input ($input_data) {

    if (is_array ($input_data)) {

        foreach  ($input_data AS $key => $value) {

            $output_data[$key] = clear_input($input_data[$key]);
        }

    } else {

        if (get_magic_quotes_gpc () == 1) {

            stripslashes($input_data);
        }

        $output_data = mysql_real_escape_string(trim($input_data));
    }

    return $output_data;
}



/* Заменяем спецсимволы на HTML сущности
 * $include_data - может иметь стрковое значение или быть массивом. Если это
 * массив, разбираем его и к каждому элементу применяем функцию еще раз.
 * $exclude_data - содержит значения, которые не нужно обрабатывать (в случае,
 * если $include_data является массивом). На выходе получим чистые данные
 * без спецсимволов
 * $output_data - очищенные данные
 */
function clear_html ($include_data, $exclude_data) {

    if (is_array ($include_data)) {

        foreach  ($include_data AS $key => $value) {

            $output_data[$key] = clear_html ($include_data[$key], $exclude_data);
        }

    } else {

        if (!in_array ($include_data, $exclude_data)) {

            $output_data = htmlspecialchars ($include_data);

        } else {

            $output_data = $include_data;
        }
    }

    return $output_data;
}



// Проверяем ошибки в переменных POST и GET
// TODO надо переделать попроще
function check_error () {

    global $error;
    $error = '';


    // проверяем GET-запросы
    if ($_GET) {

        // проверяем id и pid
        if (isset ($_GET['id'])  && !ctype_digit ($_GET['id']))  {$error .= 'id должен состоять только из цифр.';}
        if (isset ($_GET['pid']) && !ctype_digit ($_GET['pid'])) {$error .= 'pid должен состоять только из цифр.';}


        // проверяем номер страницы
        if (isset ($_GET['page']) && !ctype_digit ($_GET['page'])) {

            $error .= 'Номер страницы должен быть цифрой.';
            unset ($_GET['page']);
        }
    }


    // проверяем POST-запросы
    if ($_POST) {

        // проверяем id
        if ((isset ($_POST['id']) && $_POST['id'] !== '') && empty ($_POST['position']) && !ctype_digit ($_POST['id'])) {

            $error .= 'id должен состоять только из цифр.' . $_POST['id'];
        }


        // проверяем pid
        if (isset ($_POST['pid']) && empty ($_POST['position']) && !ctype_digit ($_POST['pid'])) {

            $error .= 'pid должен состоять только из цифр.';
        }


        // значение параметра position должно быть цифрой
        if (isset ($_POST['id']) && isset ($_POST['position'])) {

            foreach ($_POST['id'] as $testcase) {

                if (!ctype_digit ($testcase)) {

                    $error .= 'id должен состоять только из цифр.';
                }
            }

            foreach ($_POST['position'] as $testcase) {

                if (!ctype_digit ($testcase)) {

                    if ($testcase != "") {

                        $error .= 'position должно состоять только из цифр.';
                    }
                }
            }
        }
    }
}



// Высчитываем начальную и конечную запись для вывода на текущей странице
function page_limit ($page_limit) {

    global $start_page;
    global $end_page;

    if (isset ($_GET['page'])) {

        $start_page = $_GET['page'] * $page_limit - $page_limit;
        $end_page   = $page_limit;

    } else {

        $start_page = '0';
        $end_page   = $page_limit;
    }
}



// Вывод нумерации страниц (TODO: упростить)
function pager ($page_count, $referer) {

    $g_page = $_GET['page'];


    // если количество страниц более 1, выводим нумерацию страниц
    if ($page_count>1) {

        // количество страниц > 5 и текущая страница больше 3, выводим ссылку в начало <<
        if ($page_count>5 && $g_page>3){

            echo '<li><a class="page-num" href="' . $referer . '&page=1"><<</a></li>';
        }


        // если количество страниц меньше 5, выводим сразу все ссылки
        if ($page_count<=5) {

            for ($page = 1; $page <= $page_count; $page++) {

                if ($page > 0 && $page <= $page_count+1) {

                    if ($page == $g_page) {

	                    echo '<li><span class="cur_page">' . $page . '</span></li>';
                    }

                    else {

                        if (empty($g_page) && $page == 1) {

	                        echo '<li><span class="cur_page">' . $page . '</span></li>';
                        }

                        else {

	                        echo '<li><a class="page-num" href="' . $referer . '&page=' . $page . '">' . $page . '</a></li>';
                        }
                    }
                }
            }
        }


        if ($page_count>5 && ($g_page <= 3 || $g_page =="")) {

            for ($page = $g_page-2; $page <= 5; $page++) {

                if ($page > 0 && $page <= $page_count) {

                    if ($page == $g_page) {

	                    echo '<li><span class="cur_page">' . $page . '</span></li>';
                    }

                    else {

                        if (empty($g_page) && $page == 1) {

	                        echo '<li><span class="cur_page">' . $page . '</span></li>';
                        }

                        else {

	                        echo '<li><a class="page-num" href="' . $referer . '&page=' . $page . '">' . $page . '</a></li>';
                        }
                    }
                }
            }
        }


        if ($g_page > 3 && $g_page < $page_count-2) {

            for ($page = $g_page-2; $page < $g_page+3; $page++) {

                if ($page > 0) {

                    if ($page == $g_page) {

	                    echo '<li><span class="cur_page">' . $page . '</span></li>';
                    }

                    else {

	                    echo '<li><a class="page-num" href="' . $referer . '&page=' . $page . '">' . $page . '</a></li>';
                    }
                }
            }
        }


        if ($page_count>5 && $g_page >= $page_count-2) {

            for ($page = $page_count-4; $page <= $page_count; $page++) {

                if ($page > 0) {

                    if ($page == $g_page) {

	                    echo '<li><span class="cur_page">' . $page . '</span></li>';
                    }

                    else {

	                    echo '<li><a class="page-num" href="' . $referer . '&page=' . $page . '">' . $page . '</a></li>';
                    }
                }
            }
        }


        // если количество страниц больше 5, выводим ссылку на последнюю страницу
        if (($g_page != $page_count && $page_count > 5) || ($g_page < $page_count-5 && $page_count > 5)){

            echo '<li><a class="page-num" href="' . $referer . '&page=' . $page_count . '">>></a></li>';
        }
    }
}



/* Транслитерирование строки
 * На входе получаем строку с русским текстом, переводим все символы в нижний
 * регистр и заменяем русские буквы на латиницу, вместо пробела ставим тире.
 */
function translit ($str) {

    $str = mb_strtolower ($str, "utf-8");

    $rus = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о',
    'п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ');

    $lat = array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o',
    'p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','-');

    return str_replace ($rus, $lat, $str);
}



/* Перевод даты в таймстемп
 * Входные данные имеют вид 12:45:56 17.12.2012. Сначала создаем два массива -
 * время и дата, разделив входную строку по символу пробела. Далее каждый массив
 * делим на три части. В первом по символу :, второй по символу точки. Теперь
 * у нас в каждом массиве по три элемента, которые подставляем в функцию mktime.
 */
function timestamp ($get_date) {

    $date_array = explode(' ', $get_date);

    $time = explode(':', $date_array['0']);
    $date = explode('.', $date_array['1']);

    $timestamp = mktime ($time[0],$time[1],$time[2],$date[1],$date[0],$date[2]);

    return $timestamp;
}



// Удаляем запись из БД
// $section - из какого раздела/таблицы
// $id - номер записи в бд
function terminator () {

    $id = $_GET['id'];

    $delete = "DELETE FROM `" . DB_PREFIX . "_" . $_GET['section'] . "` WHERE `id` = " . $id . " LIMIT 1";

    if ($_SESSION['status'] == '1') { // удалять может только администратор

        if (mysql_query ($delete)) {

            header ('Location: /admin/index.php?section=' . $_GET['section'] . '&action=list&msg=del');

        } else {

            print_message ('', 'При удалении записи возникла ошибка: ' . mysql_errno() . ': ' . mysql_error () . '.');
        }

    } else {

        print_message ('','Не достаточно прав для удаления.');
    }
}



/* SELECT запрос в базу данных
 *
 * $row - поля для выбора,
 * $table - имя таблицы без префикса
 * $where - условия для выборки,
 * $order - поле для сортировки,
 * $limit  - сколько результатов показать,
 * $count - количество выбранных результатов
 * $pages_array - многомерный массив с данными о страницах
 */
function make_select ($row, $table, $where, $order, $limit) {

    global $sql_array;
    global $total_count;
    global $current_count;


    $sql = mysql_query("SELECT $row FROM `" . DB_PREFIX . "_" . $table . "` " . $where . " " . $order . " " . $limit);

    $current_count = mysql_num_rows($sql);

    $total_count   = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `" . DB_PREFIX . "_" . $table . "`"))['0'];

    if ($current_count > '0') {

        while ($items = mysql_fetch_array ($sql, MYSQL_ASSOC)) {

            $sql_array[] = $items;
        }
    }
}



/* Выбранная запись
 * $post_id             - id записи,
 * $post_content_array  - массив с содержимым записи
 */
function get_post ($post_id) {

    global $post_content_array;

    $post_content = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_posts` WHERE `id` = '$post_id' LIMIT 1");

    $post_content_array = mysql_fetch_array ($post_content, MYSQL_ASSOC);

}


/* Выбранная фотография
 * $photo_id            - id фотографии,
 * $photo_content_array  - массив с содержимым фотографии
 */
function get_photo ($photo_id) {

    global $photo_content_array;

    $photo_content = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_photos` WHERE `id` = '$photo_id' LIMIT 1");

    $photo_content_array = mysql_fetch_array ($photo_content, MYSQL_ASSOC);

}



/* Выбранная страница
 * $page_id             - id страницы,
 * $page_content_array  - массив с содержимым страницы
 */
function get_page ($page_id) {

    global $page_content_array;

    $page_content = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_pages` WHERE `id` = '$page_id' LIMIT 1");

    $page_content_array = mysql_fetch_array ($page_content, MYSQL_ASSOC);

}



/* Список корневых страниц (без подстраниц)
 * На входе получаем $limit - сколько страниц показать,
 * на выходе получаем многомерный массив $pages_array[].
 */
function get_page_list ($limit) {

    global $pages_array;

    $page_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_pages` WHERE position > '0' && `pid` = '0' ORDER BY `position` LIMIT $limit");

    if (mysql_num_rows ($page_list) > '0') {

        while ($pages = mysql_fetch_array ($page_list, MYSQL_ASSOC)) {

        $pages_array[] = array (

            'id' => $pages['id'],
            'title' => $pages['title'],
            'url'   => $pages['url']);
        }
    }
}



// Список подразделов сайта
function get_subcategory_list ($category, $order) {

    global $subcategory_array;

    $subcategory_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_posts_subcategories` WHERE `pid` = '$category' ORDER BY $order");

    if (mysql_num_rows ($subcategory_list) > '0') {

        while ($subcategory = mysql_fetch_array ($subcategory_list, MYSQL_ASSOC)) {

            $subcategory_array[] = array (

                'id' => $subcategory['id'],
                'pid' => $subcategory['pid'],
                'title' => $subcategory['title'],
                'url'   => $subcategory['url']);
        }
    }
}



// Количество комментариев к записи
function get_comment_count ($post_id) {

    global $comment_count;

    $comment_count = mysql_num_rows (mysql_query ("SELECT `tid` FROM `" . DB_PREFIX . "_comments` WHERE `tid` = '" . $post_id . "'"));

    return $comment_count;
}



/* Список основных разделов сайта (без подразделов)
 * $order           - поле для сортировки
 * $category_array  - многомерный массив со списком разделов
 * Поля сортировки  - id, title, url.
 */
function get_category_list ($order) {

    global $category_array;

    $category_list = mysql_query ("SELECT `id`, `title`, `url` FROM `" . DB_PREFIX . "_posts_categories` ORDER BY $order");

    if (mysql_num_rows ($category_list) > '0') {

        while ($category = mysql_fetch_array ($category_list, MYSQL_ASSOC)) {

            $category_array[] = array (

                'id'    => $category['id'],
                'title' => $category['title'],
                'url'   => $category['url']);
        }
    }
}



/* Список записей
 * $limit       - сколько записей выводить
 * $category    - из какого раздела, если не указано, выводятся из все разделов
 * $post_array  - многомерный массив с данными о записях
 * $start_page  - начальная запись для вывода
 * $end_page    - конечная запись для вывода
 * $post_count  - общее количество всех записей
 */
function get_post_list ($limit, $category) {

    global $post_array;
    global $end_page;
    global $post_count;

    // если передан $_GET['page'] (номер страницы для постраничного вывода записей)
    if (isset ($_GET['page'])) {

        // определяем начальную и конечную запись для вывода
        $start_page = $_GET['page'] * $limit - $limit;
        $end_page   = $limit;

    } else {

        // если $_GET['page'] не передан, выводим $limit страниц
        $start_page = '0';
        $end_page   = $limit;
    }

    // определяем из каких разделов показывать записи
    if (empty($category)) {

        $sort = ''; // из всех

    } else {

        $sort = '&& ' . $category; // из заданного в $category
    }

    $post_list = mysql_query ("SELECT * FROM `" . DB_PREFIX . "_posts` WHERE `status` > '0' $sort ORDER BY `date` DESC LIMIT " . $start_page . ", " . $end_page . "");

    // считаем общее количество записей в БД
    $post_count = mysql_num_rows (mysql_query ("SELECT * FROM `" . DB_PREFIX . "_posts` WHERE `status` > '0' $sort ORDER BY `date` ASC "));

    while ($post = mysql_fetch_array ($post_list, MYSQL_ASSOC)) {

        $post_array[] = array (

            'id'        => $post['id'],
            'title'     => $post['title'],
            'date'      => $post['date'],
            'text'      => $post['text'],
            'type'      => $post['type'],
            'url'       => $post['url'],
            'status'    => $post['status'],
            'category'  => $post['category'],
            'preview'   => $post['preview'],
            'count'     => $post_count);
    }
}



/* Загрузка файлов
 * Входные значения:
 * $valid_types - разрешенные расширения файлов в виде массива,
 * $valid_mime  - то же для mime-типов
 * $dl_path     - путь для сохранения файла
 */
function file_upload ($valid_types, $valid_mime, $dl_path) {

    $filenametmp = $_FILES['file']['name']; // расположение временного файла

    $ext = strtolower (substr ($filenametmp, 1 + strrpos ($filenametmp, "."))); // расширение файла

    // проверяем расширение файла
    if (!in_array ($ext, $valid_types)) {

        echo 'Файл не загружен. Разрешена загрузка только файлов с расширениями jpg и jpeg. <a href="javascript:history.back(1)">Назад</a>';
        exit;
    }

    // перемещаем временный файл в нужный каталог
    if (!copy ($_FILES["file"]["tmp_name"], $dl_path)) {

        echo 'При загрузке файла произошла ошибка. <a href="javascript:history.back(1)">Назад</a>';
        exit;
    }
}



/* Обрезка изображения
 * $input_file  - путь к изображению,
 * $width       - ширина изображения в пикселях,
 * $height      - высота изображения в пикселях,
 * $output_file - путь для сохранения файла
 * $quality     - качество сжатия jpg в процентах
 */
function resize_pic ($input_file, $width, $height, $output_file, $quality) {

    $src = imagecreatefromjpeg ($input_file);
    $w_src = imagesx ($src);
    $h_src = imagesy ($src);

    // если изображение горизонтальное
    if ($w_src>=$h_src) {

        if ($w_src >= $width && $h_src >= $height) {

            $neww = $width; $newh = $height;

        } else {

            $neww = $w_src; $newh = $h_src;
        }

        $k1 = $neww/imagesx ($src);
        $k2 = $newh/imagesy ($src);
        $k = $k1 > $k2 ? $k2 : $k1;

        $w = intval (imagesx ($src)*$k);
        $h = intval (imagesy ($src)*$k);

        $dest = imagecreatetruecolor ($w,$h);
        imagecopyresampled ($dest, $src, 0, 0, 0, 0, $w, $h, imagesx ($src), imagesy ($src));
    }


    // если изображение векртикальное
    if ($w_src < $h_src) {

        if ($w_src >= $height && $h_src >= $height) {

            $neww = $height; $newh = $height;

        } else {

            $neww = $w_src; $newh = $h_src;
        }

        $k1 = $neww/imagesx ($src);
        $k2 = $newh/imagesy ($src);
        $k = $k1 > $k2 ? $k2 : $k1;

        $w = intval (imagesx ($src)*$k);
        $h = intval (imagesy ($src)*$k);
        $dest = imagecreatetruecolor ($w, $h);
        imagecopyresampled ($dest, $src, 0, 0, 0, 0, $w, $h, imagesx ($src), imagesy ($src));
    }

    // сохраняем
    $save = imagejpeg ($dest, $output_file, $quality); //сохраняем рисунок в формате

    // чистим память
    imagedestroy ($dest);
}



/* Создаем квадратное превью из исходного изображения
 * $input_file  - путь к изображению,
 * $width       - ширина изображения в пикселях,
 * $height      - высота изображения в пикселях,
 * $output_file - путь для сохранения файла
 * $quality     - качество сжатия jpg в процентах
 */
function crop_preview ($input_file, $width, $output_file, $quality) {

    $src = imagecreatefromjpeg ($input_file);
    $w_src = imagesx ($src);
    $h_src = imagesy ($src);

    // создаём пустую квадратную картинку
    $dest = imagecreatetruecolor ($width, $width);

    // вырезаем квадратную серединку по x, если фото горизонтальное
    if ($w_src > $h_src)

    imagecopyresized ($dest, $src, 0, 0, round ((max ($w_src, $h_src) - min ($w_src, $h_src))/2), 0, $width, $width, min ($w_src, $h_src), min ($w_src, $h_src));

    // вырезаем квадратную верхушку по y,
    // если фото вертикальное
    if ($w_src < $h_src)

    imagecopyresized ($dest, $src, 0, 0, 0, 0, $width, $width, min($w_src, $h_src), min($w_src, $h_src));

    // квадратная картинка масштабируется без вырезок
    if ($w_src == $h_src)

    imagecopyresized ($dest, $src, 0, 0, 0, 0, $width, $width, $w_src, $w_src);

    // вывод картинки и очистка памяти
    $save = Imagejpeg($dest, $output_file, $quality); //сохраняем рисунок в формате JPEG

    imagedestroy($dest);
}



// обновление кэша погодных данных
function get_open_weather($city_id, $units, $appid) {

    $json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?id=' . $city_id .'&mode=json&units=' . $units . '&lang=ru&appid=' . $appid);

    return $json;
}


// вывод погоды
function get_weather ($id) {
    global $row;
    global $weather;

    // смотрим кэш в БД
    $sql_list = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_weather`
        WHERE `id` = '" . $id . "'
        LIMIT 1
    ");

    $row = mysql_fetch_array ($sql_list, MYSQL_ASSOC);

    // считаем пора ли обновлять данные в кэше
    $newdate = mktime() - $row['date'];

    // если кэш старый, обновляем его
    if ($newdate > $row['period']) {

        // забираем новые данные с сервера openweathermap.org
        $cache = get_open_weather($row['city_id'], $row['units'], $row['appid']);
        
        // сохраняем в БД
        $add_cache = mysql_query ("
        UPDATE `" . DB_PREFIX . "_weather` SET
            `date`  = '" . mktime() . "',
            `cache` = '" . $cache . "'
        WHERE `id`  = '" . $row['id'] . "'");

    }


    // считываем новые данные из БД
    $sql_list = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_weather`
        WHERE `id` = '" . $id . "'
        LIMIT 1
    ");

    $row = mysql_fetch_array ($sql_list, MYSQL_ASSOC);

    $weather = json_decode($row['cache'], true);
    
    return $row;
    return $weather;
}
?>
