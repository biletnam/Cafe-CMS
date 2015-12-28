<table class="module-main-block">
    <thead>
        <tr>
            <th>Альбом</th>
            <th>Описание</th>
            <th>Фото</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>
                <a href="?section=photos&amp;action=album_view&amp;id=0">Вне альбомов</a>
            </td>
            <td>Корневой каталог</td>
            <td>
                <?=count($root_album)?>
            </td>
            <td> </td>
        </tr>

        <?php foreach ($albums_list as $row): ?>
        <tr>
            <td>
                <a href="?section=photos&amp;action=album_view&amp;id=<?=$row['id']?>"><?=$row['title']?></a>
            </td>
            <td><?=$row['description']?></td>
            <td>
                <?php
                $photo_list = $db->getAll("SELECT id FROM " . DB_PREFIX . "_photos WHERE album=?i", $row['id']);
                echo count($photo_list); ?>
            </td>
            <td>
                <a class="dashed" href="?section=photos&amp;action=album_delete&amp;id=<?=$row['id']?>">удалить</a>
                <a class="dashed" href="?section=photos&amp;action=album_edit&amp;id=<?=$row['id']?>">изменить</a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="pagination">
    <ul>
    </ul>
</div>
