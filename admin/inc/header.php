<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

    <title>Панель управления сайтом</title>

    <meta http-equiv="Content-Language" content="ru">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/admin/inc/style.css">
    <link rel="stylesheet" type="text/css" href="/admin/inc/normalize.css">


<?php
// подключаем список стилей модуля
if ($_GET['section'] != '') {
?>

    <link rel="stylesheet" type="text/css" href="/admin/modules/<?php echo $_GET['section']?>/style.css">

<?php
}


// js-модули для редкатора подгружаем только по запросу
if ($_GET['editor'] == '1') {
?>

    <script type="text/javascript" src="/inc/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/inc/Djenx.Explorer/djenx-explorer.js"></script>
    <script type="text/javascript" src="/inc/translit.js"></script>

<?php
}
?>

</head>
<body>
