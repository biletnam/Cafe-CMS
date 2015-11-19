<?php
/*
################################################################################
###                                                                          ###
###                              Cafe CMS                                    ###
###                         Установщик системы                               ###
###                        http://cms.rad-li.ru                              ###
###                         mailto:rad-li@ya.ru                              ###
###                                                                          ###
################################################################################
*/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>

    <title>Установка системы управления сайтом Cafe CMS</title>

    <meta http-equiv="Content-Language" content="ru">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">

    <link rel="stylesheet" type="text/css" href="/install/style.css">	

</head>

<body>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/functions.php';


define (VERSION, "0.6.0"); // текущая версия CMS


// если файл настроек существует - прерываем установку CMS
if (file_exists ($_SERVER['DOCUMENT_ROOT'] . '/config.php')) {

    exit (print_message('Cafe CMS уже установлена на вашем сайте. В целях безопасности удалите папку <nobr>/install</nobr>.'));
}


// если передан параметр install, т. е. кнопка "установить" была нажата, начинаем установку
if ($_POST['install']) {

    $db_server    = $_POST['db_server'];
    $db_name      = $_POST['db_name'];
    $db_login     = $_POST['db_login'];
    $db_password  = $_POST['db_password'];
    $db_prefix    = $_POST['db_prefix'];
    $adm_login    = $_POST['adm_login'];
    $adm_password = $_POST['adm_password'];
    $adm_mail     = $_POST['adm_mail'];
    $site_name    = $_POST['site_name'];
    $now_date     = time ();
    $error = '';


    // проверяем корректность заполнения полей и пробуем подключиться к бд с указанными данными
    if (!@mysql_connect ($db_server, $db_login, $db_password)) {

        $error .= 'Не удалось подключиться к базе данных.';

    } else {

        if (strlen ($db_prefix)    < '1')   {$error .= 'Префикс таблиц должен содержать хотя бы 1&nbsp;символ.';}
        if (strlen ($db_prefix)    > '20')  {$error .= 'Префикс таблиц не должен превышать 20&nbsp;символов.';}
        if (strlen ($adm_login)    < '1')   {$error .= 'Логин администратора должен содержать хотя бы 1&nbsp;символ.';}
        if (strlen ($adm_login)    > '20')  {$error .= 'Логин администратора не должен превышать 20&nbsp;символов.';}
        if (strlen ($adm_password) < '6')   {$error .= 'Пароль администратора должен быть не короче 6&nbsp;символов.';}
        if (strlen ($site_name)    > '100') {$error .= 'Название сайта превышает 100&nbsp;символов.';}
    }


    // создаем и записываем настройки в файл config.php в коневом каталоге
    $w_string = '<?php
define ("DB_SERVER",   "' . $db_server . '"); // сервер базы данных
define ("DB_NAME",     "' . $db_name . '"); // имя базы данных
define ("DB_PREFIX",   "' . $db_prefix . '"); // префикс для таблиц
define ("DB_LOGIN",    "' . $db_login . '"); // логин для доступа к БД
define ("DB_PASSWORD", "' . $db_password . '"); // пароль для доступа к БД
define ("SITE_NAME",   "' . $site_name . '"); // название сайта
define ("TEMPLATE",    "template/simple"); // тема оформления
define ("VERSION",     "' . VERSION . '"); // текущая версия CMS
define ("LOG_LEVEL",   "0"); // уровень детализации журнала
?>';


    // если нет ошибок в предыдущем шаге, сохраняем файл с настройками сайта
    if (empty ($error)) {

        if ($fp = fopen ($_SERVER['DOCUMENT_ROOT'] . '/config.php', 'w+')) {

            if (fwrite ($fp, $w_string)) {

                fclose ($fp);
            }

        } else {

            $error .= 'Невозможно сохраниить файл настроек. Проверьте, доступен ли корневой каталог сайта для записи.';
        }
    }


    // если нет ошибок в предыдущем шаге, импортируем данные в базу данных
    if (empty ($error)) {


        // Далее идут структуры таблиц для добавления в БД
        $creat_table_albums = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_albums` (
            `id`          INT(3)       AUTO_INCREMENT PRIMARY KEY,
            `title`       VARCHAR(30)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `description` VARCHAR(300) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `url`         VARCHAR(30)  CHARACTER SET  utf8 COLLATE utf8_general_ci
        ) ENGINE        = MyISAM       CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_comments = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_comments` (
            `id`        INT(3)          AUTO_INCREMENT PRIMARY KEY,
            `pid`       INT(8),
            `tid`       INT(8),
            `type`      VARCHAR(40)     CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `login`     VARCHAR(40)     CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `email`     VARCHAR(100)    CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `text`      VARCHAR(5000)   CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `date`      INT(10),
            `status`    INT(1),
            `ip`        VARCHAR(15),
            `ua`        VARCHAR(200)
        ) ENGINE      = MyISAM          CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_logs = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_logs` (
            `id`     INT(10)      AUTO_INCREMENT PRIMARY KEY,
            `user`   INT(5),
            `date`   INT(10),
            `type`   VARCHAR(100) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `status` INT(1),
            `ip`     VARCHAR(15)  CHARACTER SET  utf8 COLLATE utf8_general_ci
        ) ENGINE   = MyISAM       CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_pages = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_pages` (
            `id`          INT(5)        AUTO_INCREMENT PRIMARY KEY,
            `date`        INT(10),
            `url`         VARCHAR(500)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `title`       VARCHAR(1000) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `text`        MEDIUMTEXT    CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `position`    VARCHAR(4),
            `keywords`    VARCHAR(100)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `description` VARCHAR(100)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `pid`         INT(5)
        ) ENGINE        = MyISAM        CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_photos = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_photos` (
            `id`          INT(5)       AUTO_INCREMENT PRIMARY KEY,
            `title`       VARCHAR(100)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `description` VARCHAR(400) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `album`       INT(3),
            `date`        INT(10)
        ) ENGINE        = MyISAM       CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_posts = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_posts` (
            `id`          INT(8)       AUTO_INCREMENT PRIMARY KEY,
            `date`        INT(10),
            `login`       VARCHAR(40)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `status`      INT(1),
            `title`       VARCHAR(255) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `text`        MEDIUMTEXT   CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `type`        VARCHAR(40)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `category`    VARCHAR(40)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `keywords`    VARCHAR(255) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `description` VARCHAR(255) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `url`         VARCHAR(100) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `source`      VARCHAR(255) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `preview`     VARCHAR(100) CHARACTER SET  utf8 COLLATE utf8_general_ci
        ) ENGINE        = MyISAM       CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_posts_categories = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_posts_categories` (
            `id`       INT(3)        AUTO_INCREMENT PRIMARY KEY,
            `title`    VARCHAR(40)   CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `url`      VARCHAR(60)   CHARACTER SET  utf8 COLLATE utf8_general_ci
        ) ENGINE     = MyISAM        CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_posts_subcategories = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_posts_subcategories` (
            `id`       INT(3)        AUTO_INCREMENT PRIMARY KEY,
            `pid`      INT(3),
            `title`    VARCHAR(60)   CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `url`      VARCHAR(60)   CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `position` INT(3)
        ) ENGINE     = MyISAM        CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_stats = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_stats` (
            `id`     INT(2)        AUTO_INCREMENT PRIMARY KEY,
            `title`  VARCHAR(60)   CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `date`   INT(10),
            `code`   VARCHAR(4000) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `status` INT(1)
        ) ENGINE   = MyISAM        CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_users = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_users` (
            `id`       INT(5)       AUTO_INCREMENT PRIMARY KEY,
            `login`    VARCHAR(20)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `password` VARCHAR(32)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `email`    VARCHAR(100) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `reg_date` INT(10),
            `status`   INT(1)
        ) ENGINE     = MyISAM       CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_weather = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_weather` (
            `id`       INT(2)       AUTO_INCREMENT PRIMARY KEY,
            `appid`    VARCHAR(32)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `period`   INT(5),
            `date`     INT(10),
            `units`    VARCHAR(8) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `title`    VARCHAR(64) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `city_id`  INT(8),
            `cache`    VARCHAR(1024) CHARACTER SET  utf8 COLLATE utf8_general_ci
        ) ENGINE     = MyISAM       CHARACTER SET  utf8 COLLATE utf8_general_ci;";


        $creat_table_raspisanie = "CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $db_prefix . "_raspisanie` (
            `id`       INT(2)       AUTO_INCREMENT PRIMARY KEY,
            `appid`    VARCHAR(48)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `title`    VARCHAR(96)  CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `period`   INT(10),
            `date`     INT(10),
            `type`     VARCHAR(24) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `from_id`  VARCHAR(16) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `to_id`    VARCHAR(16) CHARACTER SET  utf8 COLLATE utf8_general_ci,
            `cache`    MEDIUMTEXT CHARACTER SET  utf8 COLLATE utf8_general_ci
        ) ENGINE     = MyISAM       CHARACTER SET  utf8 COLLATE utf8_general_ci;";



        // шифруем пароль администратора
        $adm_password = md5 ($adm_password);


        // данные для регистрации администратора
        $add_admin = "INSERT " . $db_name . "." . $db_prefix . "_users (
            login, password, email, reg_date, status)
        VALUES (
            '$adm_login', '$adm_password', '$adm_mail', '$now_date', '1')";


        // создаем таблицы и добавляем администратора
        if (empty ($error) &&
           (@mysql_query ($creat_table_albums) &&
            @mysql_query ($creat_table_comments) &&
            @mysql_query ($creat_table_logs) &&
            @mysql_query ($creat_table_pages) &&
            @mysql_query ($creat_table_photos) &&
            @mysql_query ($creat_table_posts) &&
            @mysql_query ($creat_table_posts_categories) &&
            @mysql_query ($creat_table_posts_subcategories) &&
            @mysql_query ($creat_table_stats) &&
            @mysql_query ($creat_table_users) &&
            @mysql_query ($creat_table_weather) &&
            @mysql_query ($creat_table_raspisanie) &&
            @mysql_query ($add_admin))) {

            $install = 'ok';

        } else {

            $error .= 'Произошла ошибка при добавление таблиц в&nbsp;базу&nbsp;данных.';;

            if (file_exists ($_SERVER['DOCUMENT_ROOT'] . '/config.php')) {

                @unlink ($_SERVER['DOCUMENT_ROOT'] . '/config.php');
            }
        }
    }
}



if ($install == 'ok') {
?>

<h1>Установка Cafe CMS успешно завершена!</h1>

<div class="installed">

    <p>Ваш сайт доступен по адресу <a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>"><?php echo $_SERVER['SERVER_NAME']; ?></a></p>
    <p>Перейти <a href="/admin/">в панель управления сайтом</a>.</p>
    <p>В целях безопасности не забудьте удалить папку /install.</p>

</div>



<?php
} else {

    if ($error == true) echo print_message ($message, $error);
?>


<h1>Установка Cafe CMS</h1>

<p>До начала установки прочитайте файл <a href="/readme.txt">readme.txt</a>.</p>

<form action="" method="POST">

    <div class="left">

        <h2>Настройка базы данных</h2>

        <p><label>Сервер базы данных:<input type="text" class="input-text" name="db_server" <?php if($db_server) {echo 'value="' . $db_server . '"';}else{echo 'value="localhost"';}?>></label></p>

        <p><label>Название базы данных:<input type="text" name="db_name" <?php if($db_name) {echo 'value="' . $db_name . '"';}?>></label></p>

        <p><label>Имя пользователя:<input type="text" name="db_login" <?php if($db_login) {echo 'value="' . $db_login . '"';}?>></label></p>

        <p><label>Пароль:<input type="password" name="db_password" id="db_pass" <?php if($db_password) {echo 'value="' . $db_password . '"';}?>></label></p>

        <p><label>Префикс для таблиц:<input type="text" name="db_prefix" <?php if($db_prefix) {echo 'value="' . $db_prefix . '"';}else{echo 'value="cafe"';}?>></label></p>

    </div>


    <div class="right">

        <h2>Регистрация администратора сайта</h2>

        <p><label>Логин:<input type="text" name="adm_login" <?php if($adm_login) {echo 'value="' . $adm_login . '"';}else{echo 'value="admin"';}?>></label></p>

        <p><label>Пароль:<input type="password" name="adm_password" id="adm_pass" <?php if($adm_password) {echo 'value="' . $adm_password . '"';}?>></label></p>

        <p><label>E-mail:<input type="text" name="adm_mail" <?php if($adm_mail) {echo 'value="' . $adm_mail . '"';}?>></label></p>

        <h2 class="site">Настройка сайта</h2>

        <p><label>Название сайта:<input type="text" name="site_name" <?php if($site_name) {echo 'value="' . $site_name . '"';}?>></label></p>

        <p><input class="button" type="submit" name="install" value="установить">
            <a class="dashed" id="pass-view" style="cursor:pointer;color:#1d8ce0;border-bottom:1px dashed;"
            onclick="if (document.getElementById ('adm_pass' && 'db_pass').type == 'password'){
                document.getElementById ('db_pass').type = 'text';
                document.getElementById ('adm_pass').type = 'text';
                document.getElementById ('pass-view').innerHTML = 'скрыть';}
                else{document.getElementById ('adm_pass').type = 'password';
                document.getElementById ('db_pass').type = 'password';
                document.getElementById ('pass-view').innerHTML = 'показать';
            }">показать</a></p>

    </div>

</form>
<?php
}
?>



<div class="footer">

    <p>Система управления сайтом <a href="http://rad-li.ru">Cafe CMS</a> ver. <?php echo VERSION ?>.</p>

</div>

</body>
</html>
