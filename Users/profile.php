<? $Title = "Информация о пользователе" ?>
<? include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?>
<? //Этот модуль отображает профиль пользователя, заданного запросом GET
$LogName = $_GET['LogName'];

$Res = mysql_query("SELECT * FROM `Users` WHERE `NickName` = \"$LogName\"");
$ProfArray = mysql_fetch_assoc($Res);

$EMail = $ProfArray['EMail'];
$Verify = $ProfArray['Verify'];
$BirthDay = $ProfArray['BirthDay'];
$FirstName = $ProfArray['FirstName'];
$SurName = $ProfArray['SurName'];
$Avatar = $ProfArray['Avatar'];
switch ($ProfArray['Gender']) {
  case 1: 
    $Gender = "Мужской"; 
    break;
  case 2: 
    $Gender = "Женский";
    break;
}
$HomePage = $ProfArray['HomePage'];
$Facult = $ProfArray['Facult'];
$TitleProf = $ProfArray['Title'];
$GroupName = $ProfArray['GroupName'];
$Rights = $ProfArray['Rights'];
?>
<h1><? echo $Title?></h1> [<a href="/Users/register.php?LogName=<? echo $LogName?>">Редактировать</a>]
    <table>
      <tr><td width=150px>Пользователь:</td><td><? echo $LogName ?></td></tr>
      <tr><td>Права пользователя:</td><td><? echo $Rights ?></td></tr>
      <tr><td>Фамилия:</td><td><? echo $SurName ?></td></tr>
      <tr><td>Имя:</td><td><? echo $FirstName ?></td></tr>
      <tr><td>Регистрационый IP:</td><td><? echo $ProfArray['RegIP']; ?></td></tr>
      <tr><td>Дата регистрации:</td><td><? echo date("d.m.y", $ProfArray['RegDate']); ?></td></tr>
      <tr><td>Дата входа:</td><td></td></tr>
      <tr><td>Дата рождения:</td><td><? echo $BirthDay ?></td></tr>
<?
      if($_GET['LogName']==$_COOKIE['login']) {
        echo "<tr><td>E-Mail</td><td>$EMail</td></tr>";}
      else
        echo "<tr><td>E-Mail</td><td>EMail скрыт</td></tr>";
?>
      <tr><td>ICQ:</td><td><? echo $ProfArray['ICQ']; ?></td></tr>
      <tr><td>Группа:</td><td><? echo $GroupName ?></td></tr>
      <tr><td>Факультет:</td><td><? echo $Facult ?></td></tr>
      <tr><td>Город:</td><td><? echo $ProfArray['City']; ?></td></tr>
    </table>

<? include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php") ?>