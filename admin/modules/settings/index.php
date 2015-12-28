<?php
defined('CAFE') or die (header ('Location: /'));


check_error ();


// Проверяем уровень доступа
if ($_SESSION['status'] != '1') {

    $error = 'Не удалось показать настройки: не достаточно прав';
}



// Записываем новые настройки
if (isset ($_POST['update'])) {

    $w_string = '<?php
define ("DB_SERVER",   "' . DB_SERVER . '"); // сервер базы данных
define ("DB_NAME",     "' . DB_NAME . '"); // имя базы данных
define ("DB_PREFIX",   "' . DB_PREFIX . '"); // префикс для таблиц
define ("DB_LOGIN",    "' . DB_LOGIN . '"); // логин для доступа к БД
define ("DB_PASSWORD", "' . DB_PASSWORD . '"); // пароль для доступа к БД
define ("SITE_NAME",   "' . $_POST['site_name'] . '"); // название сайта
define ("TEMPLATE",    "template/' . $_POST['template'] . '"); // тема оформления
define ("VERSION",     "' . VERSION . '"); // текущая версия CMS
define ("LOG_LEVEL",   "' . $_POST['log_level'] . '"); // уровень детализации журнала
define ("DEBUG",       "' . $_POST['debug'] . '");
?>';


    $fop = fopen ($_SERVER["DOCUMENT_ROOT"] . '/config.php', 'w');

    if ($fwr = fwrite ($fop, $w_string)) {

        fclose ($fop);

        $message = 'Настройки обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении настроек';
    }
}



// Бекап настроек
if (isset ($_GET['action']) == 'backup') {

    if (copy ($_SERVER["DOCUMENT_ROOT"] . '/config.php', $_SERVER["DOCUMENT_ROOT"] . '/config_backup_' . mktime () . '.php')) {

        $message = 'Бекап настроек успешно сохранен';

    } else {

        $error = 'Возникла ошибка при сохранении бекапа настроек';
    }
}
else {

    $tpl = "settings_view_tpl.php";
}


        
include "settings_main_tpl.php";
?>
