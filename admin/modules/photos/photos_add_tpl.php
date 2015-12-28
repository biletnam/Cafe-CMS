<form class="form-block photo-left-block" enctype="multipart/form-data" name="new-photo" action="?section=photos&action=list" method="post">
    <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение ";} else {echo "Добавление новой";}?> фотографии</legend>

    <input type="hidden" name="id"
    <?php
    if (isset ($photo_list['id'])) {

        echo ' value="' . $photo_list['id'] . '"';
    }
    ?>>
    <div class="form-group-vertical">
        <label class="form-label-vertical" for="album">Альбом:</label>

        <div class="form-input-vertical">
            <select size="1" name="album" id="album">
                <option selected value="0">/</option>
                <?php
                foreach ($album_list as $row_album) {
                    echo '<option ';
                    if ($_GET['album'] == $row_album['id']) {echo 'selected ';}
                    echo 'value="' . $row_album['id'] . '">' . $row_album['title'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="title">Название фотографии:</label>

        <div class="form-input-vertical">
            <input type="text" name="title" id="title"
            <?php
            if (isset ($photo_list['title'])) {

                echo ' value="' . htmlspecialchars($photo_list['title']) . '"';
            }
            ?>>
        </div>
    </div>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="description">Описание фотографии:</label>

        <div class="form-input-vertical">
            <textarea class="span3" name="description" id="description" rows="5"><?php echo $photo_list['description']; ?></textarea>
        </div>
    </div>

    <?php
    if ($_GET['action'] == 'add') {
    ?>

    <div class="form-group-vertical">
        <div class="form-input-vertical span3" style="position:relative;text-align:center">
                <input class="span3" type="file" id="files" name="file" style="cursor:pointer;position:absolute;font-size:24px;right:0;opacity:0;-moz-opacity:0;filter:alpha(opacity=0)">
                <a type="button" class="button span2">Выберите фотографию</a>
        </div>
    </div>

    <?php
    }

    if ($_GET['action'] != 'edit') {
    ?>
    <div class="form-group-vertical">
        <label class="form-label-vertical" for="date">Дата добавления:</label>

        <div class="form-input-vertical span3">
            <input class="span1" type="text" name="date" id="date"
            <?php
            if (isset ($row['date'])) {

                echo ' value="' . date('H:i:s d.m.Y', $row['date']) . '"';

            } else {

                echo ' value="' . date ('H:i:s d.m.Y') . '"';
            }

       echo '>
        </div>

    </div>';
    }?>

    <div class="form-group-vertical">
        <div class="form-input-vertical">
            <?php
            ($_GET['action'] == 'edit') ? $name="update" : $name="add";
            echo '<input class="button" style="float:right" type="submit" name="' . $name . '" value="сохранить">';
            ?>
        </div>
    </div>
</form>

<div class="fileDisplayArea" id="fileDisplayArea"></div>

<script>
function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

        // Только если файл является изображением
        if (!f.type.match('image.*')) {
            document.getElementById('fileDisplayArea').innerHTML = '<div class="print_error">Это не фотография. Выберите файл с расширением jpg или jpeg.</div>';
            continue;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {

            return function(e) {

                document.getElementById('fileDisplayArea').innerHTML = ['<img src="', e.target.result,'">'].join('');
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
}

document.getElementById('files').addEventListener('change', handleFileSelect, false);
</script>
