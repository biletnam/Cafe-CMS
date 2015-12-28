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


<div class="module-main-block">
    <?php include $tpl ?>
</div>
