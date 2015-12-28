<table class="bottom20">
    <caption class="bottom20"><h2><?=$sql_list['title']?></h2></caption>
    <thead>
        <tr>
            <th>Маршрут</th>
            <th>Отправление</th>
            <th>Прибытие</th>
            <th>Дни</th>
            <th>Остановки</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i=0; $i<$raspisanie['pagination']['total']; $i++): ?>
        <tr>
            <td><?=$raspisanie['threads'][$i]['thread']['short_title']?></td>
	        <td><?=substr($raspisanie['threads'][$i]['departure'], 0, 5)?></td>
	        <td><?=substr($raspisanie['threads'][$i]['arrival'], 0, 5)?></td>
	        <td><?=$raspisanie['threads'][$i]['days']?></td>
	        <td><?=$raspisanie['threads'][$i]['stops']?></td>
        </tr>
        <?php endfor ?>
    </tbody>
</table>
