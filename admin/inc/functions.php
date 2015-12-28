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

    $db = new SafeMySQL(array('user' => DB_LOGIN, 'pass' => DB_PASSWORD, 'db' => DB_NAME, 'charset' => 'utf8'));

    if ($level <= LOG_LEVEL) {

        if (empty ($_SESSION['id'])) $_SESSION['id'] = '0';

        $data = array(
            'user'   => $_SESSION['id'],
            'date'   => mktime (),
            'type'   => $text,
            'status' => $status,
            'ip'     => $_SERVER['REMOTE_ADDR']);

        $add_log = $db->query("INSERT " . DB_PREFIX . "_logs SET ?u", $data);

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
function page_limit ($limit) {

    global $start;
    global $end;

    if (isset ($_GET['page'])) {

        $start = $_GET['page'] * $limit - $limit;
        $end   = $limit;

    } else {

        $start = '0';
        $end   = $limit;
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

    if ($_SESSION['status'] == '1') { // удалять может только администратор

        $db = new SafeMySQL(array('user' => DB_LOGIN, 'pass' => DB_PASSWORD, 'db' => DB_NAME, 'charset' => 'utf8'));
        $delete = $db->query("DELETE FROM " . DB_PREFIX . "_" . $_GET['section'] . " WHERE id=?i", $_GET['id']);

        if ($delete) {

            header ('Location: /admin/index.php?section=' . $_GET['section'] . '&action=list');

        } else {

            print_message ('', 'При удалении записи возникла ошибка: ' . mysql_errno() . ': ' . mysql_error () . '.');
        }

    } else {

        print_message ('','Не достаточно прав для удаления.');
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
?>
