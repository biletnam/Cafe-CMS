<div class="module-title">
    <h1>Фотографии</h1>
</div>

<div class="module-menu">
    <a class="button" href="?section=photos&amp;action=list">Список фотографий</a></li>
    <a class="button" href="?section=photos&amp;action=add">Добавить фотографии</a>
    <a class="button" href="?section=photos&amp;action=album_list">Альбомы</a>
    <a class="button" href="?section=photos&amp;action=album_add">Добавить альбом</a>
</div>

<div class="module-messages">
    <?php print_message ($message, $error);?>
</div>

<div class="module-main-block">
    <?php include $tpl ?>
</div>
