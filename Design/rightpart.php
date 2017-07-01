<td id="rightcolumn" class="menuColumn">
<!-- Right column -->

<!-- addfile logo --> 
<a href="/files/addfile.php">
    <div id="addfileplease">
        <p>Прими участие в проекте - добавь свою работу!<br>
        Внеси свой вклад в развитие сайта: сдал курсовик/лабу или что-либо еще - поделись своим вариантом с однокурсниками!
    </div>
  </a>
<!-- /addfile logo -->

<!-- Poll -->
<? if(isset($_COOKIE['login']))
    include $_SERVER["DOCUMENT_ROOT"]."/poll.php"; ?>
<!-- /Poll -->

<!-- News Calendar -->
<? include($_SERVER["DOCUMENT_ROOT"]."/calendar.php"); ?>
<!-- /News Calendar -->

<? include $_SERVER["DOCUMENT_ROOT"]."/minichat.php"; ?>

<? /*
<div class="vBlock">
<div class="title">Кто Онлайн?</div>
<?php
  // Запишемся в базу
  if (isset($_COOKIE['login']))
    $UserLogin = $_COOKIE['login'];
  else 
    $UserLogin = $_COOKIE['guest'];
  if(isset($UserLogin)) {
    mysql_query(
      'INSERT INTO `WhoOnLine` 
      VALUES("'.$UserLogin.'", "'.time().'", "'.$_SERVER['PHP_SELF'].'")
      ON DUPLICATE KEY 
      UPDATE `Page` = "'.$_SERVER['PHP_SELF'].'", 
        `LastVisitTime` = "'.time().'"'
    );
  }
  
  $UserList = ""; $UCount = 0;
  mysql_query(
      'UPDATE `Users`, `WhoOnLine` SET `Users`.`LastVisitTime` = `WhoOnLine`.`LastVisitTime`
      WHERE `WhoOnLine`.`LastVisitTime` < "'.(time()-300).'" AND `Users`.`NickName` = `WhoOnLine`.`NickName`'
  );
  mysql_query("DELETE FROM `WhoOnLine` WHERE `LastVisitTime` < \"".(time()-300)."\"");
  $GCount = mysql_result(mysql_query("SELECT count(*) FROM `WhoOnLine` WHERE `NickName` LIKE \"guest%\""), 0);
  // Выведем списк "Кто ОнЛайн?"
  $Res = mysql_query("SELECT * FROM `WhoOnLine` WHERE `NickName` not like \"guest%\"");
  $UsersArr = mysql_fetch_row($Res); //Массив с содержимым строки таблицы (Ник, время посл.посещения, страница)  
while ($UsersArr != false) {  
  $UserList = $UserList.' <a href="/Users/profile.php?LogName='.$UsersArr[0].'" title="'.$UsersArr[2].'">'.$UsersArr[0].'</a>';
  $UCount = $UCount+1;
  $UsersArr = mysql_fetch_row($Res); //Массив с содержимым строки таблицы (Ник, время посл.посещения, страница)
}
?>
<div class="content">
  <div class="tOnline">Онлайн всего: <? echo $UCount+$GCount; ?></div>
  <div class="gOnline">Гостей: <? echo $GCount; ?></div>
  <div class="uOnline">Пользователей: <? echo $UCount; ?></div>
  <? echo $UserList; ?>
</div>

</div>
*/ ?>
<!-- /Right column -->
</td><!--/U1RIGHTPART1Z-->
