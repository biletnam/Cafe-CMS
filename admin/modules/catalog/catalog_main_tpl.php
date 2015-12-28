<div class="module-title">
    <h1>Каталог организаций</h1>
</div>


<div class="module-menu">
    <a class="button" href="?section=catalog&amp;action=list">Список</a>
    <a class="button" href="?section=catalog&amp;action=add">Добавить организацию</a>
    <a class="button" href="?section=catalog&amp;action=category">Разделы и категории</a>
    <a class="button" href="?section=catalog&amp;action=settings">Настройки</a>
</div>


<div class="module-messages">
    <?php print_message ($message, $error); ?>
</div>


<div class="module-main-block">
    <?php include $tpl ?>
</div>
