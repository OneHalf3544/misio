<td id="rightcolumn" class="menuColumn">
<!-- Right column -->

<!-- addfile logo --> 
<a href="/files/addfile.php">
    <div id="addfileplease">
        <p>����� ������� � ������� - ������ ���� ������!<br>
        ����� ���� ����� � �������� �����: ���� ��������/���� ��� ���-���� ��� - �������� ����� ��������� � ��������������!
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
<div class="title">��� ������?</div>
<?php
  // ��������� � ����
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
  // ������� ����� "��� ������?"
  $Res = mysql_query("SELECT * FROM `WhoOnLine` WHERE `NickName` not like \"guest%\"");
  $UsersArr = mysql_fetch_row($Res); //������ � ���������� ������ ������� (���, ����� ����.���������, ��������)  
while ($UsersArr != false) {  
  $UserList = $UserList.' <a href="/Users/profile.php?LogName='.$UsersArr[0].'" title="'.$UsersArr[2].'">'.$UsersArr[0].'</a>';
  $UCount = $UCount+1;
  $UsersArr = mysql_fetch_row($Res); //������ � ���������� ������ ������� (���, ����� ����.���������, ��������)
}
?>
<div class="content">
  <div class="tOnline">������ �����: <? echo $UCount+$GCount; ?></div>
  <div class="gOnline">������: <? echo $GCount; ?></div>
  <div class="uOnline">�������������: <? echo $UCount; ?></div>
  <? echo $UserList; ?>
</div>

</div>
*/ ?>
<!-- /Right column -->
</td><!--/U1RIGHTPART1Z-->
