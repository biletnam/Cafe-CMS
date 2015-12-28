<?php
include 'inc/functions.php';
include 'inc/get_functions.php';
include 'inc/mysql.class.php';

check_install(); // проверяем, установлена ли cms

$db = new SafeMySQL();

define ("CAFE", '1');

include 'modules/auth/index.php';
include 'inc/main_tpl.php';
?>
