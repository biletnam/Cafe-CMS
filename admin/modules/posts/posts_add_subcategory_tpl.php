<form class="form-block module-main-block" action="?section=posts&action=category" method="post">

    <legend>Добавление новой категории</legend>

    <input name="pid" value="<?=$_GET['id']?>" type="hidden">

    <div class="form-group">
        <label class="form-label" for="title">Название категории:</label>

        <div class="form-input">
            <input class="span1" name="title[]" type="text" id="title">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="url">Адрес:</label>

        <div class="form-input">
            <input class="span1" name="url[]" type="text" id="url">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="position">Порядковый номер:</label>

        <div class="form-input">
            <input name="position[]" type="text" id="position">
        </div>
    </div>

    <div id="new-subcat"></div>

    <div class="form-group">
        <div class="form-input">
            <input class="button" type="submit" name="add-fields" value="Еще одну" onclick="newField(); return false">
            <input class="button" type="submit" name="add_subcategory" value="Сохранить">
        </div>
    </div>
</form>

<script type="text/javascript">
function newField() {

document.getElementById('new-subcat').outerHTML='<div class="form-group" style="padding-top:20px;border-top:1px #ccc dashed"><label class="form-label" for="title">Название категории:</label><div class="form-input"><input class="span1" name="title[]" type="text" id="title"></div></div><div class="form-group"><label class="form-label" for="url">Адрес:</label><div class="form-input"><input class="span1" name="url[]" type="text" id="url"></div></div><div class="form-group"><label class="form-label" for="position">Порядковый номер:</label><div class="form-input"><input name="position[]" type="text" id="position"></div></div><div id="new-subcat"></div>';
}
</script>
