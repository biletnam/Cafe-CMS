<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

	<title><?php if (isset ($page_title)) {echo $page_title . ' - ';} echo SITE_NAME; ?></title>

	<meta http-equiv="Content-Language" content="ru">
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta name="description" content="<?php if (isset ($page_keywords)) {echo $page_keywords;}?>">
	<meta name="keywords" content="<?php if (isset ($page_description)) {echo $page_description;}?>">

	<link rel="shortcut icon" href="/<?php echo TEMPLATE; ?>/favicon.ico">
    <link rel="stylesheet" href="/<?php echo TEMPLATE; ?>/normalize.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo TEMPLATE; ?>/style.css">

</head>

<body>

    <div class="container">

        <div class="row">

            <div class="header span12">

                <h1><a href="/"><?php echo SITE_NAME; ?></a></h1>

            </div>

        </div>


        <div class="row">

            <div class="fon span12"></div>

        </div>


        <div class="row">

            <?php
            $limit = "6";

            $photo_list = mysql_query ("
                SELECT id, date
                FROM `" . DB_PREFIX . "_photos`
                LIMIT 0, " . $limit
                );

            if (mysql_num_rows ($photo_list) > '0') {

                while ($row = mysql_fetch_array ($photo_list, MYSQL_ASSOC)) {

                    echo '
                    <div class="thumb span2">

                        <a href="?photo=' . $row['id'] . '"><img src="/upload/photo/200-200/' . $row['date'] . '.jpg" width="140"></a>

                    </div>';
                }
            }


                for ($i= 1; $i <= $limit - mysql_num_rows ($photo_list); $i++) {

                    echo '<div class="thumb span2"><img src="http://placehold.it/140x100"></div>';
                }


            ?>

        </div>


        <div class="row">

            <div class="sidebar span2">

                <nav>

                    <ul>

                        <?php
                        get_page_list ('15');

                        if (count($pages_array) > 0) {

                            foreach ($pages_array as $page_item) {

                                echo '<li><a href="?p=' . $page_item['id'] . '">' . $page_item['title'] . '</a></li>';
                            }

                        } else {

                            echo '<li><a href="#">Пункт меню</a></li>';
                        }
                        ?>

                    </ul>

                </nav>

            </div>


            <div class="content span10">

            <?php
            // если передан id фотографии, показываем ее
            if (isset($_GET['photo'])) {

                get_photo($_GET['photo']);

                echo '
                <h2>' . $photo_content_array['title'] . '</h2>

                <p>' . $photo_content_array['description'] . '

                    <img style="overflow:hidden" width="760" src="/upload/photo/800-600/' . $photo_content_array['date'] . '.jpg">

                </p>';

            }


            // если передан id страницы, показываем ее содержимое
            if (isset($_GET['p'])) {

                get_page($_GET['p']);

                echo '
                <h2>' . $page_content_array['title'] . '</h2>

                <p>' . $page_content_array['text'] . '</p>';

            }

            // если передан id записи и не передан url страницы
            if (isset($_GET['post']) && !isset($_GET['page_url'])) {

		        // выводим содержимое записи с id = $_GET['post_id']
		        get_post ($_GET['post']);

                echo '
                    <h2>' . $post_content_array['title'] . '</h2>

                    <p>' . $post_content_array['text'] . '</p>';

            } else {

                // выводим список записей
                if (empty($_GET) || isset($_GET['page'])) {

                    // получаем 10 последних записей из текущего раздела
                    get_post_list('10', $category_id);

                    foreach ($post_array as $post_item) {

                        // обрезаем большие статьи до макс. 500 символов или первой точки после 150 символов
                        $post_item['text'] = strip_tags($post_item['text']);

                        if (strlen($post_item['text']) > "300") {

                            for ($i=300; $i<=500; $i++) {

                                if (mb_substr($post_item['text'], $i, 1, "utf-8") == ".") {

                                    $begin = $i+1;
                                    break;
                                }
                            }

                            $post_item['text'] = mb_substr($post_item['text'], 0, $begin, "utf-8"); // образаем сообщение

                        }


                        $category_list = mysql_query ("
                            SELECT *
                            FROM `" . DB_PREFIX . "_posts_subcategories`
                            WHERE `id` = '" . $post_item['category'] . "'
                            ");

                        $categories_rows = mysql_fetch_array ($category_list);


                        echo '
                        <h2><a class="post-title" href="?post=' . $post_item['id'] . '">' . $post_item['title'] . '</a></h2>

                        <p>' . $post_item['text'] . '

                            <a href="?post=' . $post_item['id'] . '">далее...</a>

                        </p>';
                    }
                    ?>


                    <div class="pagination">

                        <ul>
                            <?php pager (ceil ($post_count / $end_page), "/index.php?");?>
                        </ul>

                    </div>


                    <?php
                    if (count($post_array) <1) {

                        echo'
                        <h2>Список записей</h2>

                        <p>
                            Здесь будет выводиться список записей из всех разделов и категорий. Сейчас у вас нет записей, поэтому вы видите этот текст-заглушку. Зайдите в панель управления и добавьте первую запись.
                        </p>

                        <h2>Фоновая картинка</h2>

                        <p>
                            В верхней части страницы расположена фоновая картинка. Файл bg.jpg находится в папке с этим шаблоном. Вы можете поменять ее на свою, заменив исходный файл. Размер изображения 960х250px.
                        </p>

                        <h2>Список фотографий</h2>

                        <p>
                            Под фоновой картинкой находятся превью последних добавленных фотографий. Если у вас нет добавленных фотографий или их количество меньше 6, вместо фотографий появятся заглушки.
                        </p>

                        <h2>Меню</h2>

                        <p>
                            В левой части находится меню сайта. Оно формируется автоматически из всех имеющихся записей из модуля "Страницы". Добавьте страницы в панели управления, чтобы увидеть ссылки на них в меню.
                        </p>';
                    }
                }
            }
            ?>

            </div>

        </div>


        <div class="row">

            <div class="footer span12">

                <small>Сайт работает на <a href="http://rad-li.ru/">Cafe CMS</a>. Шаблон сайта Simple</small>

            </div>

        </div>

    </div>

</body>
</html>
