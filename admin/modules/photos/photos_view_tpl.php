<div class="module-main-block">
    <div class="page-content">
        <h1 class="bottom20"><?=$view_photo['title']?></h1>
        <div class="bottom20"><?=$view_photo['description']?><br>
            <img class="big-photo" src="/upload/photo/800-600/<?=$view_photo['date']?>.jpg">
        </div>
        <p><strong>Дата размещения</strong>: <?=date ("j.m.Y H:i", $view_photo['date'])?></p>
        <div>
            <a class="dashed" href="?section=photos&amp;action=delete&amp;id=<?=$view_photo['id']?>">удалить</a>
            <a class="dashed" href="?section=photos&amp;action=edit&amp;id=<?=$view_photo['id']?>&amp;album=<?=$view_photo['album']?>">изменить</a>
        </div>
    </div>
</div>
