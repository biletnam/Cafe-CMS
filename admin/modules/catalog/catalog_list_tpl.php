<table class="bottom20">
    <thead>
        <tr>
            <th>#</th>
            <th>Название</th>
            <th>Телефон</th>
            <th>Адрес</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach ($catalog_list as $row): ?>

    <tr>
        <td><?=$row['id']?></td>
        <td><a href="?section=catalog&amp;action=view&amp;id=<?=$row['id']?>"><?=$row['title']?></a></td>
        <td><?=$row['phone']?></td>
        <td><?=$row['city']?>, <?=$row['street']?>, <?=$row['build']?></td>
        <td>
            <a class="dashed" href="?section=catalog&amp;action=delete&amp;id=<?=$row['id']?>">удалить</a>
            <a class="dashed" href="?section=catalog&amp;action=edit&amp;id=<?=$row['id']?>">изменить</a>
        </td>
    </tr>

    <?php endforeach ?>

    </tbody>
</table>

<div class="pagination">
    <ul>
        <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_catalog'))) / $limit, '/admin/index.php?section=catalog&action=list'); ?>
    </ul>
</div>
