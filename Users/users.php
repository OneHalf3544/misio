<? $Title = "Пользователи сайта"; ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 
<!-- Body --> 
<h1><? echo $Title; ?></h1>
<?
{ //Рисуем "PageSelector"
  $PageSelector = '';
  $NUsers = 50;
  if(!isset($_REQUEST['FirstNUser'])) {
    $FirstNUser = 0;} //Число юзеров на странице и номер первой новости
  else {
    $FirstNUser = $_REQUEST['FirstNUser'];}

  $Res = mysql_query("select count(*) from `Users`");
  $CountOfNews = mysql_result($Res, 0); //Количество новостей в базе
  preg_match("/[^\?]+/", $_SERVER['REQUEST_URI'], $PageURL);
  for($i = 1; $i <= ceil($CountOfNews / $NUsers); $i++) {
    if ($_REQUEST['FirstNUser'] == ($i-1)*$NUsers)
      $PageSelector .= "<span class=\"currentPage\">$i</span> ";
    else
      $PageSelector .= "<a href=\"$PageURL[0]?FirstNUser=".($i-1)*$NUsers."\">$i</a> ";
  }
  $PageSelector = '<div class="pageSelector">'.$PageSelector."</div>";
  echo $PageSelector;
} 
?>
<table class="usersTable">
  <tr>
    <th>Логин</th>
    <th>Имя</th>
    <th>Фамилия</th>
    <th>Факультет</th>
    <th>Группа</th>
    <th>Специальность</th>
  </tr>
<? 
  $Res = mysql_query(
    'SELECT `Users`.*, `Groups`.`Title` AS GroupName, `Speciality`.`Spec`, `Facults`.`FacultName` FROM `Users`
    LEFT JOIN `Groups` ON `Users`.`GroupId` = `Groups`.`id`
    LEFT JOIN `Speciality` ON `Users`.`SpecId` = `Speciality`.`id`
    LEFT JOIN `Facults` ON `Facults`.`id` = `Speciality`.`FacultId`
    ORDER BY `NickName` LIMIT '.$FirstNUser.', '.$NUsers
  );
  for($S = mysql_fetch_assoc($Res); $S != false; $S = mysql_fetch_assoc($Res)): 
?>
  <tr>
    <td><? echo profileURL($S['NickName']); ?></td>
    <td><? echo $S['FirstName'] ?></td>
    <td><? echo $S['SurName'] ?></td>
    <td><? echo $S['FacultName'] ?></td>
    <td><? echo $S['GroupName'] ?></td>
    <td><? echo $S['Spec'] ?></td>
  </tr>
<?  
  endfor;
?>
</table>
<? echo $PageSelector; ?>
<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php")?>