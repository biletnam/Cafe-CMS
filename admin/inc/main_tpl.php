<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

    <title>Панель управления сайтом</title>

    <meta http-equiv="Content-Language" content="ru">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/admin/inc/style.css">
    <link rel="stylesheet" type="text/css" href="/admin/inc/normalize.css">
    <link rel="stylesheet" type="text/css" href="/admin/modules/<?=$_GET['section']?>/style.css">

<?php
// js-модули для редкатора подгружаем только по запросу
if ($_GET['editor'] == '1') { ?>
    <script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/js/Djenx.Explorer/djenx-explorer.js"></script>
    <script type="text/javascript" src="/js/translit.js"></script>
<?php } ?>

</head>
<body>
<?php
include 'inc/menu_tpl.php';

?>


<div class="right-side">
<?php
/* Выводим содержимое модуля
 * Необходимый модуль вызывается через параметр GET из ссылки в левом меню.
 * Имя модуля передается в параметре section. В необязательном параметре action
 * передается значение для определенного действия выбранного модуля.
 * Если параметр $_GET['section'] не указан, будет показан модуль
 * по умолчанию dashboard
 */
!empty ($_GET['section']) ? include 'modules/' . $_GET['section'] . '/index.php' : (header ('Location: ?section=dashboard'));
?>

</div>

<?php if (DEBUG == '1') {include 'inc/debug_tpl.php';}?>


</body>
</html>	
