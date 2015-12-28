<h1 class="bottom20"><?=$weather['city']?></h1>

<div class="bottom20">

    Широта: <?=$weather['lat']?><br>
    Долгота: <?=$weather['lon']?><br>
    Небо: <?=$weather['description']?><br>
    Облачность: <?=$weather['clouds']?>% <br>

    Температура: <?=round($weather['temp'], 1)?>°C <br>
    Давление <?=round($weather['pressure']/1.34)?> мм. рт. ст. <br>
    Влажность: <?=$weather['humidity']?>% <br>

    Скорость ветра: <?=$weather['wind_speed']?> м/сек <br>

</div>

<div>
    <a class="dashed" href="?section=weather&amp;action=delete&amp;id=<?=$row['id']?>">удалить</a>
    <a class="dashed" href="?section=weather&amp;action=edit&amp;id=<?=$row['id']?>">изменить</a>
</div>
