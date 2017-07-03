<?php 
if (isset($_REQUEST['id'])) {
    $Title = 'Редактировать новость';
} else {
    $Title = "Добавить новость";
}

include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 
<!-- Body --> 
<?php echo "<h1>$Title</h1>"; ?>

<?php1
  if (isset($_REQUEST['SaveNewContent'])) {
    if (!isset($_REQUEST['id'])) {
      mysql_query(
        'INSERT INTO `News` (`Title`, `Message`, `Author`, `DateOfAdding`) 
        VALUES (
          "'.htmlspecialchars($_REQUEST['title']).'",
          "'.htmlspecialchars($_REQUEST['Message']).'",
          "'.htmlspecialchars($_COOKIE['login']).'",
          "'.time().'")'
      );
      echoRedirectHtml ('/oneofmaterial.php?TypeOfMat=News&NMaterial='.mysql_insert_id(), 'Новость создана');
    } 
    else {
      mysql_query(
        'UPDATE `News` SET 
          `Title` = "'.htmlspecialchars($_REQUEST['title']).'", 
          `Message` = "'.htmlspecialchars($_REQUEST['Message']).'", 
          `Author` = "'.htmlspecialchars($_COOKIE['login']).'", 
          `DateOfAdding` = "'.time().'"
          WHERE `id` = '.intval($_REQUEST['id'])
      );
      echoRedirectHtml ('/oneofmaterial.php?TypeOfMat=News&NMaterial='.$_REQUEST['id'], 'Новость изменена');
    }
  }
  if (isset($_REQUEST['id'])) {
    $Res = mysql_query('SELECT * FROM `News` WHERE `id` = '.$_REQUEST['id']);
    $NewsArray = mysql_fetch_assoc($Res);
  }
?>

<?php
  if (isset($_COOKIE['login'])) {
?>
<form method="post">
  <?php if(isset($_REQUEST['id'])): ?>
    <input name="id" type="hidden" value="<?php echo $NewsArray['id'] ?>">
  <?php endif; ?>
  <input name="SaveNewContent" type="hidden" value="true">
  Заголовок
  <input type="text" name="title" class="longinput" value="<?php echo $NewsArray['Title'] ?>">
  Описание
  <textarea name="Message" class="high"><?php echo $NewsArray['Message']; ?></textarea>
  <input type="submit" value="Сохранить">
</form> 
<?php } else { ?>
  Залогиньтесь для добавления новости.
<?php } ?>

<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php")?>