<div class="debug-right">
    <b>POST</b>: <pre><?php print_r($_POST); ?></pre><br>
    <b>GET</b>: <pre><?php print_r($_GET); ?></pre><br>
    <b>Files</b>: <pre><?php print_r($_FILES); ?></pre><br>
    <b>Ошибка</b>: <?php echo $error; ?><br>
    <b>Сообщение</b>: <?php echo $message; ?><br>
    <b>MySQL</b>: <?php echo mysql_errno() . " " . mysql_error(); ?><br>
</div>

<div class="debug-bottom">
    <b>Статистика запросов:</b>
    <pre><?php print_r($db->getStats()); ?></pre>
</div>
