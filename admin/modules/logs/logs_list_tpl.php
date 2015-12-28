<div class="module-submenu">
    Сортировка:
    <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=user">по пользователям</a>
    <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=type">по типу</a>
    <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=status">по статусу</a>
    <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=ip">по ip-адресу</a>
    <a class="dashed" href="/admin/index.php?section=logs&amp;action=list&amp;order=date">по дате</a>
</div>


<table class="module-main-block">
    <thead>
        <tr>
            <th>Дата и время</th>
            <th>Логин</th>
            <th>Действие</th>
            <th>Статус</th>
            <th>ip-адрес</th>
        </tr>
    </thead>

    <tbody>
    
        <?php
        $status = array(0 => '<span style="color:red">ошибка</span>', 'успешно');

        foreach ($log_list as $row): ?>
        <tr>

            <td><?=date ('d.m.Y H:i:s', $row['date'])?></td>
            <td><?=$user_list[$row['user']]?></td>
            <td><?=$row['type']?></td>
            <td><?=$status[$row['status']]?></td>
            <td><?=$row['ip']?></td>

        </tr>
        <?php endforeach ?>

    </tbody>
</table>


<div class="pagination">
    <ul>
        <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_logs')) / $limit), '/admin/index.php?section=logs&action=list'); ?>
    </ul>
</div>
