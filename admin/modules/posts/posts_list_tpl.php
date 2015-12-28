<table>
    <thead>
        <tr>
            <th class="span0">Раздел</th>
            <th class="span3">Заголовок записи</th>
            <th>Действие</th>
            <th>Дата и время</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($post_list as $post): ?>
        <tr>
            <td><?=$category_title[$post['type']]?></td>
            <td>
                <a href="?section=posts&action=view&id=<?=$post['id']?>"><?=$post['title']?></a>
            </td>
            <td>
                <a class="dashed" href="?section=posts&amp;action=delete&amp;id=<?=$post['id']?>">удалить</a>
                <a class="dashed" href="?section=posts&amp;action=edit&amp;id=<?=$post['id']?>&amp;editor=1">изменить</a></td>
            <td><?=date ('d.m.Y H:i:s', $post['date'])?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<div class="pagination">
    <ul>
        <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_posts')) / $limit), '/admin/index.php?section=posts&action=list'); ?>
    </ul>
</div>
