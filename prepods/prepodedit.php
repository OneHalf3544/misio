<? 
if (isset($_REQUEST['id']) || isset($_REQUEST['edit']))
    $Title = "Редактировать данные о преподавателе";
else
    $Title = "Создать описание преподавателя";
include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 
<!-- Body --> 
<?
echo "<h1>$Title</h1>";
if (isset($_REQUEST['edit'])) { // Если надо что-то сохранять
    if ($_REQUEST['edit'] == "") {
        // Если нужно создать новую запись
        mysql_query(
            'INSERT INTO `Prepods`
            (`PrepodName`, `BirthDay`, `DeptId`, `EMail`, `Telephone`, `Status`, `Description`)
            VALUES (
              "'.$_REQUEST['prepodName'].'",
              "'.$_REQUEST['yearbirth'].'-'.$_REQUEST['monthbirth'].'-'.$_REQUEST['daybirth'].'",
              "'.$_REQUEST['DeptId'].'",
              "'.$_REQUEST['EMail'].'",
              "'.$_REQUEST['Telephone'].'",
              "'.$_REQUEST['Status'].'",
              "'.$_REQUEST['description'].'"
            )'
        );
        $fL = new fileLoaded('PrepPhoto', $_SERVER["DOCUMENT_ROOT"].'/img/prepods/');
        $fL->resizeImage(200, 250);
        // TODO Удалять "лишние" файлы, "'.basename($fL->ResultName).'",

        $_REQUEST['id'] = mysql_insert_id();
        if ($fL->FileHasBeenLoaded) {
            // Переименовываем файл, если запись только что была создана и файл был загружен
            rename(
                $fL->ResultName,
                $_SERVER["DOCUMENT_ROOT"].'/img/prepods/'.$_REQUEST['id'].$fL->getFileExtension()
            );
            mysql_query(
                'UPDATE `Prepods` SET `ImgName` = "'.$_REQUEST['id'].$fL->getFileExtension().'" '.
                'WHERE id='.$_REQUEST['id']
            );
        }
    }
    else {
        // Если нужно отредактировать старую запись
        $sql_str = 'UPDATE `Prepods` SET '.
            '`PrepodName` = "'.($_REQUEST['prepodName']).'", '.
            '`BirthDay` = "'.$_REQUEST['yearbirth'].'-'.$_REQUEST['monthbirth'].'-'.$_REQUEST['daybirth'].'", ';
        if($fL->FileHasBeenLoaded)
            $sql_str .= '`ImgName` = "'.basename($fL->ResultName).'", ';
        $sql_str .= '`DeptId`      = "'.$_REQUEST['DeptId'].'", '.
            '`Status`      = "'.$_REQUEST['Status'].'", '.
            '`Telephone`   = "'.$_REQUEST['Telephone'].'", '.
            '`EMail`       = "'.$_REQUEST['EMail'].'", '.
            '`Description` = "'.htmlspecialchars($_REQUEST['description']).'" '.
            'WHERE `id` = '.intval($_REQUEST['edit']);

        mysql_query($sql_str);
        $_REQUEST['id'] = $_REQUEST['edit'];
    }
}
  if(isset($_REQUEST['id'])) {
      $S = mysql_fetch_assoc(mysql_query(
          'SELECT *, RIGHT(`BirthDay`, 2) AS `DayBirth`, '.
          'MONTH(`BirthDay`) AS `MonthBirth`, '.
          'YEAR(`BirthDay`) AS `YearBirth` '.
          'FROM `Prepods` WHERE `id` = "'.intval($_REQUEST['id']).'"'
      ));
  }
?>

<form method="post" action="/prepods/prepodedit.php"  enctype="multipart/form-data">
<input name="edit" type="hidden" value="<? echo $_REQUEST['id'] ?>">

<table class="optionsArrayTable">
    <tr>
        <td>ФИО преподавателя</td>
        <td><input type="text" class="longinput" name="prepodName" value="<? echo $S['PrepodName'] ?>"></td>
    </tr>
    <tr>
        <td>Фотография</td>
        <td>
            <?
            if (isset($S['ImgName'])):
                echo "Загружен файл: ".$S['ImgName'].'; ' ; ?>
                <label><input type="checkbox" name="deleteImage" />
                    <span class="alertMessage">Удалить изображение</span>
                </label><br />
            <? endif; ?>
            <input name="PrepPhoto" type="file">
        </td>
    </tr>
    <tr>
        <td>День рождения</td>
        <td>
            <select name="daybirth">
                <option value="0">---</option>
                <? echoDaysOptions($S['DayBirth']); ?>
            </select>/
            <select name="monthbirth">
                <option value="0">---</option>
                <? echoMonthsOptions($S['MonthBirth']); ?>
            </select>/
            <select name="yearbirth">
                <option value="0">---</option>
                <? echoYearsOptions($S['YearBirth']); ?>
            </select>
        </td>
  </tr>
  <tr>
    <td>E-Mail:</td>
    <td><input class="longinput" type="text" name="EMail" value="<? echo $S['EMail'] ?>"></td>
  </tr>
  <tr>
    <td>Телефон:</td>
    <td><input class="longinput" type="text" name="Telephone" value="<? echo $S['Telephone'] ?>"></td>
  </tr>
  <tr>
    <td>Кафедра</td>
    <td> 
      <select class="longselect" name="DeptId">
        <option value="0"> - Не указана - </option>
        <?  
          $Res2 = mysql_query(
              'SELECT * FROM `Depts` ORDER BY `DeptName`'
          ); 
          while(($S2 = mysql_fetch_assoc($Res2)) != false) {
            if ($S['DeptId'] == $S2['id'])
                echo '<option value="'.$S2['id'].'" selected="selected">'.$S2['DeptName'].'</option>';
            else
                echo '<option value="'.$S2['id'].'">'.$S2['DeptName'].'</option>';
          }
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td>Должность</td>
    <td>
      <select class="longselect" name="Status">
        <option value="0">---</option>
        <?
        foreach($PrepodStatus as $a => $b) {
          if ($S['Status'] == $a)
            echo '<option selected="selected" value="'.$a.'">'.$b.'</option>';
          else
            echo '<option value="'.$a.'">'.$b.'</option>';
        }
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td colspan="2">Описание
        <textarea cols="100" rows="10" name="description" class="high">
        <? echo $S['Description'] ?>
        </textarea>
    </td>
  </tr>
  </table>
  <input type="submit" value="Сохранить">
</form>
<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php")?>