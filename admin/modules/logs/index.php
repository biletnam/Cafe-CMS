<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Проверяем уровень доступа
if ($_GET['action'] == 'settings' && $_SESSION['status'] != '1') {

    $error = 'Не удалось показать настройки: не достаточно прав';
}



// Очистка журнала
if ($_GET['action'] == 'delete' && empty ($error)) {

    $clear_log = "TRUNCATE `" . DB_PREFIX . "_logs`";

    if ($_SESSION['status'] == '1') {

        if (mysql_query ($clear_log)) {

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
?>



    <div class="module-title">

        <h1>Журнал действий</h1>

    </div>


    <div class="module-menu">

        <a class="button" href="/admin/index.php?section=logs&amp;action=delete">Очистить журнал</a>
        <a class="button" href="/admin/index.php?section=logs&amp;action=settings">Настройки журнала</a>

    </div>


    <div class="module-messages">

        <?php print_message ($message, $error);?>

    </div>



<?php
// Выводим журнал
if (($_GET['action'] == 'list' || $_GET['action'] == 'delete') && empty ($error)) {


// Сортировка журнала. По умолчанию записи сортируются по дате
?>
    <div class="module-submenu">

        Сортировка:
        <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=user">по пользователям</a>
        <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=type">по типу</a>
        <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=status">по статусу</a>
        <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=ip">по ip-адресу</a>
        <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=date">по дате</a>

    </div>


    <table class="module-main-block">

        <thead>

            <tr>

                <th>Дата и время</th>
                <th>Логин</th>
                <th>Действие</th>
                <th>Статус</th>
                <th>ip-адрес</th>

            </tr>

        </thead>

        <tbody>


    <?php
    page_limit ('15');

    // поля разрешенные для сортировки
    (!in_array ($_GET['order'], array ('user', 'type', 'status', 'ip', 'date'))) ? $order = 'date' : $order = $_GET['order'];

    $log_list = mysql_query ("
        SELECT *
        FROM `" . DB_PREFIX . "_logs`
        ORDER BY `" . $order . "` DESC
        LIMIT " . $start_page . ", " . $end_page
    );

    $status   = array (0 => '<span style="color:red">ошибка</span>', 'успешно');

    while ($row = mysql_fetch_array ($log_list, MYSQL_ASSOC)) {

        $user_select = mysql_query ("SELECT login FROM `" . DB_PREFIX . "_users` WHERE `id` = " . $row['user']);

        $user = mysql_fetch_array ($user_select, MYSQL_ASSOC);

        if ($user['login'] == '') $user['login'] = 'guest';

        echo '
            <tr>

                <td>' . date ('d.m.Y H:i:s', $row['date']) . '</td>
                <td>' . $user['login'] . '</td>
                <td>' . $row['type'] . '</td>
                <td>' . $status[$row['status']] . '</td>
                <td>' . $row['ip'] . '</td>

            </tr>';
    }

    echo '
        </tbody>

    </table>


    <div class="pagination">

        <ul>';
            pager (ceil (mysql_num_rows (mysql_query ("SELECT id FROM `" . DB_PREFIX . "_logs`")) / $end_page),'/admin/index.php?section=logs&action=list');

    echo '
        </ul>

    </div>';
}



// Выводим найстройки журнала
if ($_GET['action'] == 'settings' && empty ($error)) {
?>

    <form class="form-block module-main-block" name="settings" action="?section=logs&action=list" method="post">

        <legend>Настройка журнала</legend>

        <div class="form-group">

            <label class="form-label" for="select">Уровень детализации:</label>

            <div class="form-input">

                <select size="1" name="log_level" class="span2">

                    <option <?php if (LOG_LEVEL == '0') {echo "selected";}?> value="0">журнал отключен</option>
                    <option <?php if (LOG_LEVEL == '1') {echo "selected";}?> value="1">минимальный уровень</option>
                    <option <?php if (LOG_LEVEL == '2') {echo "selected";}?> value="2">максимальный уровень</option>

                </select>

            </div>

        </div>


        <div class="form-group">

            <div class="form-input">

                <input class="button" type="submit" name="update-settings" value="сохранить">

            </div>

        </div>

    </form>

<?php
}
?>
