<div class="module-title">
    <h1>Настройки</h1>
</div>

<div class="module-menu">
    <a class="button" href="?section=settings">Настройки</a>
    <a class="button" href="?section=settings&amp;action=backup">Сделать бекап настроек</a>
    <a class="button" href="http://cms.rad-li.ru/?upd=<?php echo VERSION; ?>">Проверить обновления</a>
</div>

<div class="module-messages">
    <?php print_message ($message, $error); ?>
</div>

<div class="module-main-block">
    <?php include $tpl; ?>
</div>

<div class="module-footer">
    Версия Cafe CMS <?php echo VERSION; ?>
</div>
