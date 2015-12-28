<?php
defined('CAFE') or die (header ('Location: /'));


check_error ();



// Проверяем уровень доступа
if ($_GET['action'] == 'settings' && $_SESSION['status'] != '1') {

    $error = 'Не удалось показать настройки: не достаточно прав';
}



// Очистка журнала
if ($_GET['action'] == 'delete' && empty ($error)) {

    $clear_log = "TRUNCATE `" . DB_PREFIX . "_logs`";

    if ($_SESSION['status'] == '1') {

        if ($db->query($clear_log)) {

            $message = 'Журнал действий очищен';

        } else {

            $error = 'При очистке журнала возникла ошибка';
        }

    } else {

        $error = 'Не удалось очистить журнал: не достаточно прав';
    }
}



// Записываем новые настройки
if (isset ($_POST['update-settings'])) {

    $w_string = '<?php
define ("DB_SERVER",   "' . DB_SERVER . '"); // сервер базы данных
define ("DB_NAME",     "' . DB_NAME . '"); // имя базы данных
define ("DB_PREFIX",   "' . DB_PREFIX . '"); // префикс для таблиц
define ("DB_LOGIN",    "' . DB_LOGIN . '"); // логин для доступа к БД
define ("DB_PASSWORD", "' . DB_PASSWORD . '"); // пароль для доступа к БД
define ("SITE_NAME",   "' . SITE_NAME . '"); // название сайта
define ("TEMPLATE",    "' . TEMPLATE . '"); // тема оформления
define ("VERSION",     "' . VERSION . '"); // текущая версия CMS
define ("LOG_LEVEL",   "' . $_POST['log_level'] . '"); // уровень детализации журнала
?>';

    $fop = fopen ($_SERVER["DOCUMENT_ROOT"] . '/config.php', 'w');

    if ($fwr = fwrite ($fop, $w_string)) {

        fclose ($fop);
        $message = 'Настройки журнала обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении настроек журнала';
    }
}



// Выводим журнал
if (($_GET['action'] == 'list' || $_GET['action'] == 'delete') && empty ($error)) {

    $limit = '10'; // количесвто результатов на страницу
    page_limit ($limit); // считаем количество страниц

    // поля разрешенные для сортировки
    (!in_array ($_GET['order'], array ('user', 'type', 'status', 'ip', 'date'))) ? $order = 'date' : $order = $_GET['order'];

    $user_list = $db->getIndCol("id", "SELECT id, login FROM " . DB_PREFIX . "_users");
    $log_list  = $db->getAll("SELECT * FROM " . DB_PREFIX . "_logs ORDER BY ?n DESC LIMIT ?i, ?i", $order, $start, $end);

    $tpl = 'logs_list_tpl.php';
}



// Выводим найстройки журнала
if ($_GET['action'] == 'settings' && empty ($error)) {

    $tpl = 'logs_settings_tpl.php';
}



include "logs_main_tpl.php";
?>
