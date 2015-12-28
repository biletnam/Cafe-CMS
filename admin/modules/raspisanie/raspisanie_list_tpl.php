<table class="bottom20">
    <thead>
        <tr>
            <th>#</th>
            <th>Маршрут</th>
            <th>Обновлено</th>
            <th>Период</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sql_list as $row): ?>
        <tr>
            <td><?=$row['id']?></td>
            <td><a href="?section=raspisanie&amp;action=view&amp;id=<?=$row['id']?>"><?=$row['title']?></a></td>
            <td><?=date ("H:i d.m.Y", $row['date'])?></td>
            <td><?=$row['period']/60/60?> час.</td>
            <td>
                <a class="dashed" href="?section=raspisanie&amp;action=delete&amp;id=<?=$row['id']?>">удалить</a>
                <a class="dashed" href="?section=raspisanie&amp;action=edit&amp;id=<?=$row['id']?>">изменить</a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="pagination">
    <ul>
        <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_raspisanie')) / $limit), '/admin/index.php?section=raspisanie&action=list'); ?>
    </ul>
</div>
