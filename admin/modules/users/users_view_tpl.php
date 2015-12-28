<h2 class="bottom20"><?=$user_row['login']?></h2>

<p class="bottom20"><strong>Статус</strong>: <?=$user_status_array[$user_row['status']]?></p>

<p class="bottom20"><strong>Дата регистрации</strong>: <?=date ('d.m.Y', $user_row['reg_date'])?></p>

<p class="bottom20"><strong>E-mail</strong>: <?=$user_row['email']?></p>

<a class="dashed" href="?section=users&amp;action=delete&amp;id=<?=$user_row['id']?>">удалить</a>
<a class="dashed" href="?section=users&amp;action=edit&amp;id=<?=$user_row['id']?>">изменить</a>
