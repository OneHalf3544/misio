<?php //move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)
if(isset($_REQUEST['id']))
$Title = "Изменить файл";
else
$Title = "Добавить файл";
include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php"); 
if (!isset($_COOKIE['login'])) { ?>
    <h1>Добавление файла</h1>
    Нужно быть зарегистрированным пользователем, чтобы добавлять файлы
    <? include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php");
    exit;
}

if(isset($_REQUEST['EditId'])) {
    foreach($FilesType as $key => $value) {
      if ($_REQUEST[$key] != '') {
        $AssignableFileType[$key] = $key;
      }
    }
    if ($_REQUEST['EditId'] != '') { // Если нужно сохранить запись
        mysql_query(
            'UPDATE `Files` SET
            `Title`     = "'.$_REQUEST['Title'].'",
            `Message`   = "'.$_REQUEST['Message'].'",
            `SubjectId` = "'.$_REQUEST['Subject'].'",
            `PrepodId`  = "'.$_REQUEST['PrepodId'].'",
            `Author`    = "'.$_REQUEST['Author'].'",
            `Type`      = "'.implode(',', $AssignableFileType).'"
            WHERE `id` = '.intval($_REQUEST['EditId'])
        );
        $_REQUEST['id'] = intval($_REQUEST['EditId']);
    }
    else { // Если нужно создать запись
        $tempName = $_FILES['LoadedFile']['tmp_name'];
        $NewName = $_FILES['LoadedFile']['name'];
        $UploadDir = $_SERVER["DOCUMENT_ROOT"].'/materials/';
        $ResultName = $UploadDir.$NewName;
        $size = $_FILES['LoadedFile']['size']; // TODO Добавить проверку ошибок
        move_uploaded_file($tempName, $ResultName); //TODO учесть возвращаемое значение
        chmod($ResultName, 0644);

        // TODO Дата, файл, размер
        mysql_query(
            'INSERT INTO `Files` (`Title`, `Message`, `Size`, `DateOfAdding`, `NickName`, `URL`, `SubjectId`, `PrepodId`, `Author`, `Type`)
            VALUES (
              "'.$_REQUEST['Title']   .'",
              "'.$_REQUEST['Message'] .'",
              "'.$size                .'",
              "'.time()               .'",
              "'.$_COOKIE['login']    .'",
              "'.$NewName             .'",
              "'.$_REQUEST['Subject'] .'",
              "'.$_REQUEST['PrepodId'].'",
              "'.$_REQUEST['Author']  .'",
              "'.implode(',', $AssignableFileType).'"
            )'
        );
        $_REQUEST['id'] = mysql_insert_id();
    }
}
  
  if(isset($_REQUEST['id'])) {
    $FileArray = mysql_fetch_assoc(mysql_query(
      'SELECT `Files`.*, `Subjects`.`Title` AS SubjTitle, `Prepods`.`PrepodName` FROM `Files` 
        LEFT JOIN `Subjects` ON `Files`.`SubjectId` = `Subjects`.`id` 
        LEFT JOIN `Prepods`  ON `Files`.`PrepodId`  = `Prepods`.`id`
        WHERE `Files`.`id` = "'.$_REQUEST['id'].'"'
    ));
  }
?>
<script type="text/javascript">
    function addSubjects(objsubj, tlocation) {
        $('#Subject').empty(); $('#Subject').append($('<option value="0">Все</option>'));
        if (objsubj.id != "") {
            $('#Subject').attr('disabled', '');
            for(var i = 0; i < (objsubj.id.length); i++) {
                $('#Subject').append($('<option value="'+objsubj.id[i]+'">'+objsubj.subject[i]+'</option>'));
            }
        }
    }
</script>
<script type="text/javascript" src="/jscript/selectcascade.js"></script>
<h1><? echo $Title ?></h1>
<a class="backLink" href="javascript:history.back()">Вернуться назад</a>
<form action="/files/addfile.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="id"     value="<? echo $_REQUEST['id'] ?>">
<input type="hidden" name="EditId" value="<? echo $_REQUEST['id'] ?>">
<table class="optionsArrayTable">
<tbody>
<tr>
  <td>Заголовок</td>
  <td><input class="longinput" name="Title" type="text" value="<? echo $FileArray['Title'] ?>"></td>
</tr>
<tr>
  <td>Описание</td>
  <td><textarea name="Message"><? echo $FileArray['Message'] ?></textarea></td>
</tr>
<tr>
  <td>Файл</td>
  <td>
  <? if(isset($FileArray['URL'])) 
    echo '<span style="color:red;">('.$FileArray['Size'].'кб) Загрузить другой файл  </span>' ?>
  <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
  <input class="longinput" name="LoadedFile" type="file"></td>
</tr>
<tr><td>Факультет: </td>
    <td><? echoFacultSelect() ?></td></tr>
<tr><td>Специальность: </td>
  <td><? echoSpecialitySelect(); ?></td></tr>
<tr><td>Семестр: </td>
  <td><select id="Semestr" name="Semestr" class="shortselect">
    <option value="0">Все</option>
    <? $Res = mysql_query("SELECT `id`, `SemName` FROM `SemestrNames`");
    $SemArray = mysql_fetch_assoc($Res);
    while($SemArray != false) {
        echo '<option value="'.$SemArray['id'].'">'.$SemArray['SemName']."</option>";
        $SemArray = mysql_fetch_assoc($Res);
    } ?>
  </select></td></tr>
<tr><td>Предмет: </td>
  <td><select id="Subject" name="Subject" class="longselect">
    <option value="0">Не указан</option>
    <? if($FileArray['SubjectId'] != 0): ?>
      <option value="<? echo $FileArray['SubjectId'] ?>" selected="selected">
        <? echo $FileArray['SubjTitle'] ?>
      </option>
    <? endif ?>
  </select></td>
</tr>

<tr>
  <td>Тип материала</td>
  <td>
    <? $TypeArr = explode(',', $FileArray['Type']);
    foreach ($FilesType as $key => $value): ?>
      <label><input type="checkbox" name="<? echo $key ?>" value="<? echo $key ?>"
      <? if(in_array($key, $TypeArr)) echo ' checked="checked"' ?> /><? echo $value ?></label><br />
    <? endforeach; ?>
  </td>
</tr>
<tr>
  <td>Преподаватель:</td>
  <td>
  <select class="longselect" name="PrepodId">
    <option value="0"> - Не выбран - </option>
    <? echo PrepodOptions($FileArray['PrepodId']); ?>
  </select>
  </td>
</tr>

<tr>
  <td>Автор:</td>
  <td><input class="longinput" name="Author" type="text" value="<? echo $FileArray['Author']?>" /></td>
</tr>
<tr>
  <td colspan="2"><label><input type="checkbox" />Материал недоступен для просмотра</label>
</tr>
</tbody>
</table>
<input type="submit" value="Применить" />
</form>
<? include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php"); ?>