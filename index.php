<?php
################################################################################
###                                                                          ###
###                              Cafe CMS                                    ###
###                        http://cms.rad-li.ru                              ###
###                         mailto:rad-li@ya.ru                              ###
###                                                                          ###
################################################################################

include 'admin/inc/functions.php';
include 'admin/inc/get_functions.php';
include 'admin/inc/mysql.class.php';

check_install(); // проверяем, установлена ли cms

$db = new SafeMySQL();

define ("CAFE", '1');

include TEMPLATE . '/index.php';// подключаем шаблон
?>
