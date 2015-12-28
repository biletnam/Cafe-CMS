<table class="module-main-block bottom20">
    <thead>
        <tr>
            <th>Пользователь</th>
            <th>Текст комментария</th>
            <th>Действия</th>
            <th class="span1">Дата и время</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($comments_list as $comments_row): ?>
        <tr>

            <td><?=$comments_row['login']?><br><small><?=$comments_row['ip']?><br><?=$comments_row['email']?></small></td>
            <td style="height:55px;display:block;overflow:hidden"
                <?php if ($comments_row['status'] < "1") {echo ' style="color:#aaa"';}?>><?=$comments_row['text']?>
            </td>
            <td>
                <a class="dashed" href="?section=posts&amp;action=delete_comment&amp;id=<?=$comments_row['id']?>">удалить</a><br>
                <a class="dashed" href="?section=posts&amp;action=edit_comment&amp;id=<?=$comments_row['id']?>&amp;editor=1">изменить</a></td>
            <td><?=date ('d.m.Y', $comments_row['date'])?> <?=date ('H:i:s', $comments_row['date'])?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="pagination">
    <ul>
        <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_comments')) / $limit), '/admin/index.php?section=posts&action=comments'); ?>
    </ul>
</div>
