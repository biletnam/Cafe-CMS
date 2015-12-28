<div class="module-submenu span5">
    <a class="dashed" href="?section=photos&amp;action=add&album=<?=$_GET['id']?>">Добавить фотографии в этот альбом</a>
</div>

<ul>
    <?php foreach ($photo_list as $row): ?>
    <li class="photo-thumb">
        <div class="thumb">
            <a href="/admin/index.php?section=photos&amp;action=view&amp;id=<?=$row['id']?>">
                <img src="/upload/photo/200-200/<?=$row['date']?>.jpg" width="200" height="200">
            </a>

            <div class="photo-caption">
                <p><?=$row['title']?></p>
                <a class="button" href="/admin/index.php?section=photos&amp;action=delete&amp;id=<?=$row['id']?>">удалить</a>
                <a class="button" href="/admin/index.php?section=photos&amp;action=edit&amp;id=<?=$row['id']?>&amp;album=<?=$row['album']?>">изменить</a>
            </div>
        </div>
    </li>
    <?php endforeach ?>
</ul>

<div class="pagination both">
    <ul>
    <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_photos WHERE album=' . $_GET['id'])) / $limit), '/admin/index.php?section=photos&action=list'); ?>
    </ul>
</div>
