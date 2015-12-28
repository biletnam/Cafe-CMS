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


// получение страницы по ее id
function get_page ($id) {

    global $db;
    $result = $db->getRow('SELECT * FROM ' . DB_PREFIX . '_pages WHERE id=?i', $id);
    return $result;
}



// получение списка страниц: $limit - количество, $order - поле для сортировки
function get_page_list ($limit, $order) {

    global $db;
    global $start;
    global $end;

    page_limit ($limit);
    (!in_array ($_GET['order'], array ('date', 'title', 'position'))) ? $order = 'id' : $order = $_GET['order'];
    $result = $db->getAll('SELECT * FROM ' . DB_PREFIX . '_pages ORDER BY ?n DESC LIMIT ?i, ?i', $order, $start, $end);

    return $result;
}



// получение комментария по ее id
function get_comment ($id) {

    global $db;
    $result = $db->getRow("SELECT * FROM " . DB_PREFIX . "_comments WHERE id=?i", $id);
    return $result;
}



// получение списка комментариев: $limit - количество, $order - поле для сортировки
function get_comments_list ($limit, $order) { 

    global $db;
    global $start;
    global $end;

    page_limit ($limit); // считаем количество страниц

    $result = $db->getAll('SELECT * FROM ' . DB_PREFIX . '_comments ORDER BY date DESC LIMIT ?i, ?i', $start, $end);

    return $result;
}



function get_post_comments ($id) {

    global $db;
    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_comments WHERE tid=?i ORDER BY date ASC", $id);
    return $result;
}



function get_category ($id) {

    global $db;
    $result = $db->getRow("SELECT * FROM " . DB_PREFIX . "_posts_categories WHERE id=?i", $id);
    return $result;
}



function get_categories () {

    global $db;
    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_posts_categories");
    return $result;
}



function get_categories_title () {

    global $db;
    $result = $db->getIndCol('id', "SELECT id, title FROM " . DB_PREFIX . "_posts_categories");
    return $result;
}



function get_subcategories ($pid) {

    global $db;
    $sqlpart = '';
    
    if (!empty($pid)) {

        $sqlpart = $db->parse(" WHERE pid=?i", $pid);
    }
    
    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_posts_subcategories ?p", $sqlpart);
    return $result;
}



function get_subcategories_by_id ($id) {

    global $db;
    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_posts_subcategories WHERE id=?i", $id);
    return $result;
}



function get_post ($id) {

    global $db;
    $result = $db->getRow("SELECT * FROM " . DB_PREFIX . "_posts WHERE id=?i", $id);
    return $result;
}



function get_posts ($limit) {

    global $db;
    global $start;
    global $end;

    page_limit($limit);

    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_posts ORDER BY id DESC LIMIT ?i, ?i", $start, $end);

    return $result;
}



function get_photos ($limit) {

    global $db;
    global $start;
    global $end;

    page_limit($limit);

    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_photos ORDER BY ?n DESC LIMIT ?i, ?i", 'id', $start, $end);

    return $result;
}



function get_album_photos ($id, $limit) {

    global $db;
    global $start;
    global $end;

    page_limit($limit);

    if (!empty($limit)) {

        $sqlpart = $db->parse(" LIMIT ?i, ?i", $start, $end);
    }

    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_photos WHERE album=?i ORDER BY ?n DESC ?p", $id, 'id', $sqlpart);

    return $result;
}



function get_photo ($id) {

    global $db;
    $result = $db->getRow("SELECT * FROM " . DB_PREFIX . "_photos WHERE id=?i", $id);
    return $result;
}



function get_album ($id) {

    global $db;
    $result = $db->getRow("SELECT * FROM " . DB_PREFIX . "_albums WHERE id=?i", $id);
    return $result;
}



function get_albums ($limit) {

    global $db;
    $result = $db->getAll("SELECT id, title FROM " . DB_PREFIX . "_albums");
    return $result;
}



function get_user ($id) {

    global $db;
    $result = $db->getRow("SELECT * FROM " . DB_PREFIX . "_users WHERE id=?i", $id);
    return $result;
}



function get_users ($limit) {

    global $db;
    global $start;
    global $end;

    page_limit($limit);

    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_users ORDER BY `id` ASC LIMIT ?i, ?i", $start, $end);

    return $result;
}



function get_currency_list ($limit) {

    global $db;
    global $start;
    global $end;

    page_limit($limit);

    $result = $db->getAll('SELECT * FROM ' . DB_PREFIX . '_currency ORDER BY id LIMIT ?i, ?i',$start, $end);

    return $result;
}



function get_currency_by_id ($id) {

    global $db;
    $result = $db->getRow('SELECT * FROM ' . DB_PREFIX . '_currency WHERE id=?i', $id);

    return $result;
}



function get_currency ($id) {

    global $db;

    // смотрим кэш в БД
    $cache = get_currency_by_id ($id);

    // считаем пора ли обновлять данные в кэше
    $newdate = mktime() - $cache['date'];

    // если кэш старый, обновляем его
    if ($newdate > $cache['period']) {

        $url = 'http://www.cbr.ru/scripts/XML_dynamic.asp';

        # Начальная дата для запроса  (сегодня - 2 дня)
        $date_1=date('d/m/Y', time()-272800);

        # Конечная дата включая завтрашний день
        $date_2=date('d/m/Y', time()+86400);

        # URL для запроса данных
        $requrl = "{$url}?date_req1={$date_1}&date_req2={$date_2}&VAL_NM_RQ={$cache['currency']}";

        $doc = file($requrl);
        $doc = implode($doc, '');

        # инициализируем массив
        $r = array();

        # ищем <ValCurs>...</ValCurs>
        if(preg_match("/<ValCurs.*?>(.*?)<\/ValCurs>/is", $doc, $m))

        # а потом ищем все вхождения <Record>...</Record>
        preg_match_all("/<Record(.*?)>(.*?)<\/Record>/is", $m[1], $r, PREG_SET_ORDER);

        $m = array();	# его уже использовали, реинициализируем
        $d = array();	# этот тоже проинициализируем

        # Сканируем на предмет самых нужных цифр
        for($i=0; $i<count($r); $i++) {

            if(preg_match("/Date=\"(\d{2})\.(\d{2})\.(\d{4})\"/is", $r[$i][1],$m)) {

		        $dv = mktime (0,0,0,$m[2],$m[1],$m[3]);

		        if(preg_match("/<Nominal>(.*?)<\/Nominal>.*?<Value>(.*?)<\/Value>/is", $r[$i][2], $m)) {

			        $m[2] = preg_replace("/,/",".",$m[2]);
			        $d[] = array($dv, $m[1], $m[2]);
		        }
	        }
        }

        $last = array_pop($d);				# последний известный день
        $prev = array_pop($d);				# предпосл. известный день
        $date = $last[0];   				# отображаемая дата
        $nominal = $last[1];
        $rate = sprintf("%.2f",$last[2]);	# отображаемый курс


        // сохраняем в БД
        $data = array(
            'date'     => mktime(),
            'cur_date' => $date,
            'nominal'  => $nominal,
            'rate'     => $rate);

        $add_cache = $db->query("UPDATE " . DB_PREFIX . "_currency SET ?u WHERE id=?i", $data, $id);
    }

	$sql = get_currency_by_id ($id);

    $currency = array(
        'title'    => $sql['title'],
        'code'     => $sql['code'],
        'currency' => $sql['currency'],
        'period'   => $sql['period'],
        'rate'     => $sql['rate'],
        'nominal'  => $sql['nominal'],
        'cur_date' => $sql['cur_date']);

    return $currency;	
}



function get_counters ($limit) {

    global $db;
    global $start;
    global $end;

    page_limit($limit);

    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_stats ORDER BY ?n ASC LIMIT ?i, ?i", "id", $start, $end);

    return $result;
}



function get_counter ($id, $code) {

    global $db;

    if ($code == '1') {

        $code = $db->getCol("SELECT `code` FROM " . DB_PREFIX . "_stats WHERE id=?i", $id);
        $result = $code['0'];
    }

    else {

        $result = $db->getRow("SELECT * FROM " . DB_PREFIX . "_stats WHERE id=?i", $id);
    }

    return $result;
}



function get_raspisanie_list ($limit) {

    global $db;
    global $start;
    global $end;

    page_limit($limit);

    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_raspisanie ORDER BY id LIMIT ?i, ?i", $start, $end);

    return $result;
}



function get_raspisanie_by_id ($id) {

    global $db;
    $result = $db->getRow('SELECT * FROM ' . DB_PREFIX . '_raspisanie WHERE id=?i', $id);
    return $result;
}



// обновление расписания
function get_yandex_raspisanie($from_id, $to_id, $appid, $type) {

    $json = file_get_contents('https://api.rasp.yandex.net/v1.0/search/?apikey=' . $appid . '&format=json&from=' . $from_id . '&to=' . $to_id . '&lang=ru&page=1&transport_types=' . $type . '');

    return $json;
}



// расписания
function get_raspisanie ($id) {

    global $db;
    global $sql_list;
    global $raspisanie;

    // смотрим кэш в БД
    $cache = get_raspisanie_by_id ($id);

    // считаем пора ли обновлять данные в кэше
    $newdate = mktime() - $cache['date'];

    // если кэш старый, обновляем его
    if ($newdate > $cache['period']) {

        // забираем новые данные с сервера яндекса
        $new_cache = get_yandex_raspisanie($cache['from_id'], $cache['to_id'], $cache['appid'], $cache['type']);

        // сохраняем в БД
        $data = array(
            'date'  => mktime(),
            'cache' => $new_cache);

        $add_cache = $db->query("UPDATE " . DB_PREFIX . "_raspisanie SET ?u WHERE id=?i", $data, $id);
    }

    // считываем новые данные из БД
    $sql_list = get_raspisanie_by_id ($id);
    $raspisanie = json_decode($sql_list['cache'], true);

    return $sql_list;
    return $raspisanie;
}



function get_weather_list ($limit) {

    global $db;
    global $start;
    global $end;

    page_limit($limit);

    $result = $db->getAll("SELECT * FROM " . DB_PREFIX . "_weather ORDER BY ?n LIMIT ?i, ?i", "id", $start, $end);

    return $result;
}



function get_weather_city ($id) {

    global $db;
    $result = $db->getRow('SELECT * FROM ' . DB_PREFIX . '_weather WHERE id=?i', $id);
    return $result;
}




function get_openweathermap($city_id, $units, $appid) {

    $json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?id=' . $city_id .'&mode=json&units=' . $units . '&lang=ru&appid=' . $appid);

    return $json;
}



function get_weather ($id) {

    global $weather;
    global $db;

    // смотрим кэш в БД
    $cache = get_weather_city ($id);

    // считаем пора ли обновлять данные в кэше
    $newdate = mktime() - $cache['date'];

    // если кэш старый, обновляем его
    if ($newdate > $cache['period']) {

        // забираем новые данные с сервера openweathermap.org
        $json_cache = get_openweathermap($cache['city_id'], $cache['units'], $cache['appid']);

        // сохраняем в БД
        $data = array(
            'date'  => mktime(),
            'cache' => $json_cache);

        $add_cache = $db->query("UPDATE " . DB_PREFIX . "_weather SET ?u WHERE id=?i", $data, $id);
    }


    // считываем новые данные из БД
    $sql = get_weather_city ($id);
    $w_json = json_decode($sql['cache'], true);

    $weather = array(
        'city'       => $sql['title'],
        'lat'        => $w_json['coord']['lat'],
        'lon'        => $w_json['coord']['lon'],
        'description'=> $w_json['weather'][0]['description'],
        'clouds'     => $w_json['clouds']['all'],
        'temp'       => $w_json['main']['temp'],
        'pressure'   => $w_json['main']['pressure'],
        'humidity'   => $w_json['main']['humidity'],
        'wind_speed' => $w_json['wind']['speed']);

    return $weather;
}



function get_catalog_item ($id) {

    global $db;
    $result = $db->getRow("SELECT * FROM " . DB_PREFIX . "_catalog WHERE id=?i", $_GET['id']);
    return $result;
}



function get_catalog_category_item ($id) {

    global $db;
    $result = $db->getRow('SELECT * FROM ' . DB_PREFIX . '_catalog_categories WHERE id=?i', $_GET['id']);
    return $result;
}



function get_catalog_list ($limit) {

    global $db;
    global $start;
    global $end;

    page_limit($limit);

    $result = $db->getAll('SELECT id, title, phone, city, street, build FROM ' . DB_PREFIX . '_catalog ORDER BY id LIMIT ?i, ?i',$start, $end);

    return $result;
}



function get_category_list ($limit) {

    global $db;
    $result = $db->getAll('SELECT * FROM ' . DB_PREFIX . '_catalog_categories ORDER BY id');
    return $result;
}
?>
