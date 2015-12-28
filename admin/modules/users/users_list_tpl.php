<table class="module-main-block bottom20">
    <thead>
        <tr>
            <th>#</th>
            <th>Логин</th>
            <th>Статус</th>
            <th>Дата регистрации</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($user_list as $user_row): ?>
        <tr>
            <td><?=$user_row['id']?></td>
            <td><a href="/admin/index.php?section=users&action=view&id=<?=$user_row['id']?>"><?=$user_row['login']?></a></td>
            <td><?=$user_status_array[$user_row['status']]?></td>
            <td><?=date ('d.m.Y', $user_row['reg_date'])?></td>
            <td>
                <a class="dashed" href="?section=users&amp;action=delete&amp;id=<?=$user_row['id']?>">удалить</a>
                <a class="dashed" href="?section=users&amp;action=edit&amp;id=<?=$user_row['id']?>">изменить</a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="pagination">
    <ul>
        <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_users')) / $limit), '/admin/index.php?section=users&action=list'); ?>
    </ul>
</div>
