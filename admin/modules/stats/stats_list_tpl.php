<table class="module-main-block bottom20">
    <thead>
        <tr>
            <th>#</th>
            <th>Название</th>
            <th>Статус</th>
            <th>Дата установки</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>
       
        <?php foreach ($counter_list as $row): ?>
        <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['title']?></td>
            <td><?=$status[$row['status']]?></td>
            <td><?=date ('d.m.Y H:i:s', $row['date'])?></td>
            <td>
                <a class="dashed" href="/admin/index.php?section=stats&amp;action=delete&amp;id=<?=$row['id']?>">удалить</a>
                <a class="dashed" href="/admin/index.php?section=stats&amp;action=edit&amp;id=<?=$row['id']?>">изменить</a>
            </td>
        </tr>
        <?php endforeach?>
    </tbody>
</table>

<div class="pagination">
    <ul>
        <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_stats')) / $limit), '/admin/index.php?section=stats&action=list'); ?>
    </ul>
</div>
