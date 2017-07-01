<? $Title = "Прочая информация"; ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 
<!-- Body --> 
<?
  if (isset($_REQUEST['id'])) {
    $Res = mysql_query('SELECT * FROM `Pages` WHERE `id` = '.$_REQUEST['id']);
    $S = mysql_fetch_assoc($Res);
    echo "<h1>".$S['Title']."</h1>";
    echo str_replace('&quot', "\"", $S['Content']);
  }
  else {
    $Res = mysql_query('SELECT `id`, `Title` FROM `Pages`');
    while(($S = mysql_fetch_assoc($Res)) != false) {
      echo "<a href=\"/Pages.php?id=".$S['id']."\">".$S['Title']."<br>";
    }
  }

$TypeOfMat = 'SitePages';
$NMaterial = $_REQUEST['id'];
include 'comments.php';
include 'addcomment.php';
?>
<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php")?>