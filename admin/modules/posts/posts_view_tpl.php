<h1 class="bottom20"><?=$post_view['title']?></h1>

<div class="bottom20"><?=$post_view['text']?></div>

<div class="module-footer">
    <p><strong>Дата размещения</strong>: <?=date ("j.m.Y H:i", $post_view['date'])?></p>
    <p><strong>Ключевые слова</strong>: <?=$post_view['keywords']?></p>
    <p><strong>Описание страницы</strong>: <?=$post_view['description']?></p>

    <div>
        <a class="dashed" href="?section=posts&amp;action=delete&amp;id=<?=$post_view['id']?>">удалить</a>
        <a class="dashed" href="?section=posts&amp;action=edit&amp;id=<?=$post_view['id']?>&amp;editor=1">изменить</a>
    </div>
</div>

<h2>Комментарии:</h2>

<?php foreach ($comments_view as $row): ?>
<div class="post-view-comment">
    <p class="post-view-comment-login"><?=$row['login']?>
    <span class="post-view-comment-date"> (<?=date ('H:i:s d.m.Y', $row['date'])?>)</span></p>
    <p class="post-view-comment-text"><?=$row['text']?><p>
</div>
<?php endforeach ?>
