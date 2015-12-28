<div class="module-main-block">
    <table class="module-main-block bottom20">
        <thead>
            <tr>
                <th>#</th>
                <th>Валюта</th>
                <th>Обновлено</th>
                <th>Период</th>
                <th>Курс на дату</th>
                <th>Номинал</th>
                <th>Курс</th>
                <th>Действия</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($currency_list as $row): ?>

        <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['title']?></td>
            <td><?=date ("H:i d.m.Y", $row['date'])?></td>
            <td><?=$row['period']/60/60?> час.</td>
            <td><?=date ("d.m.Y", $row['cur_date'])?></td>
            <td><?=$row['nominal']?> <?=$row['code']?></td>
            <td><?=$row['rate']?> руб.</td>
            <td>
                <a class="dashed" href="?section=currency&amp;action=delete&amp;id=<?=$row['id']?>">удалить</a>
                <a class="dashed" href="?section=currency&amp;action=list&amp;id=<?=$row['id']?>">обновить</a>
            </td>
        </tr>
        <?php endforeach ?>

        </tbody>
    </table>

    <div class="pagination">
        <ul>
            <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_currency')) / $limit), '/admin/index.php?section=currency&action=list')?>
        </ul>
    </div>
</div>
