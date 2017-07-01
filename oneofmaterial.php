<?php //Этот модуль отображает материал с комментариями
$Title = "Комментарии к материалу";
include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php");

$TypeOfMat = $_REQUEST['TypeOfMat']; $NMaterial = intval($_REQUEST['NMaterial']);
//Увеличиваем счетчик просмотров
mysql_query("UPDATE `$TypeOfMat` SET `$TypeOfMat`.`Views` = `$TypeOfMat`.`Views` + 1 WHERE `id` = $NMaterial"); 


if ($TypeOfMat == 'Prepods') { ?>
  <h1>Информация о преподавателе</h1>
  <? $Res = mysql_query("SELECT * FROM `$TypeOfMat` WHERE `id` = $NMaterial");
  $Prepods = mysql_fetch_assoc($Res);
  include ($_SERVER["DOCUMENT_ROOT"]."/prepods/oneprepod.php");
}
elseif ($TypeOfMat == 'Files') {
  $Res = mysql_query(
    "SELECT `Files`.*, `Subjects`.`Title` AS `SubjTitle`, `Prepods`.`PrepodName`
    FROM `Files`
    LEFT JOIN `Subjects` ON `Files`.`SubjectId` = `Subjects`.`id` 
    LEFT JOIN `Prepods` ON `Files`.`PrepodId` = `Prepods`.`id` 
    WHERE `Files`.`id` = $NMaterial"
  );
  $FilesArray = mysql_fetch_assoc($Res);
  include ($_SERVER["DOCUMENT_ROOT"]."/files/onefile.php"); 
}
else {
  $Res = mysql_query("SELECT * FROM `$TypeOfMat` WHERE `id` = $NMaterial");
  $MatArray = mysql_fetch_assoc($Res);
  $MatArray['Message'] = str_replace("&quot", "\"", $MatArray['Message']);
  $dateofadding = date("d-m-Y",$MatArray['DateOfAdding']);

  // Далее следует кусок html-кода
?>
    <table class="matBlock">
      <tr>
        <td class="title"><? echo $MatArray['Title']; ?></td>
      </tr>
      <tr>
        <td class="content">
          <div class="Message"><? echo add_p_Tag($MatArray['Message']); ?></div>
          <div class="Details">
            Просмотров: <? echo $MatArray['Views']; ?>
            | Добавил: <? echo profileURL($MatArray['Author']); ?>
            | Дата: <? echo $dateofadding; ?>
          </div>
        </td>
      </tr>
    </table>
<?
}
include("comments.php"); // Комментарии к материалу
include("addcomment.php"); // Форма добавления комментария
include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php");
?>