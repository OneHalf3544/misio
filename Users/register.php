<?php
/* Регистрация пользователя, либо редактирование профиля в зависимости от того, 
  зашел ли пользователь под своим логином, или числится как гость */

if(isset($_COOKIE['login']) || $_REQUEST['LogName']) {
    $Title = 'Редактирование профиля';
    if (isset($_REQUEST['LogName'])) {
        $Login = $_REQUEST['LogName'];
    }
    else {
        $Login = $_COOKIE['login'];
    }
}
else {
    $Title = 'Регистрация пользователя';
}
  
  include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php");
  
  if (isset($_REQUEST['SaveProfile'])):
    if ($_REQUEST['Passwd'] != $_REQUEST['PasswdConfirm']) {
      ?> Введенные пароли не совпадают <? }
      
    $Res = mysql_query('SELECT COUNT(*) FROM `Users` WHERE `NickName` = "'.$_REQUEST['NickName'].'"');
    if (mysql_result($Res, 0) != 0) :
      // Обновляем данные если такой пользователь есть в базе
      mysql_query(
        'UPDATE `Users` SET 
        `SurName`       = "'.$_REQUEST['SurName'].'", 
        `FirstName`     = "'.$_REQUEST['FirstName'].'", 
        `EMail`         = "'.$_REQUEST['EMail'].'", 
        `HomePage`      = "'.$_REQUEST['HomePage'].'", 
        `ICQ`           = "'.$_REQUEST['ICQ'].'", 
        `vkontakte`     = "'.$_REQUEST['VKontakte'].'", 
        `GroupId`       = "'.$_REQUEST['Group'].'",
        `City`          = "'.$_REQUEST['City'].'", 
        `SpecId`        = "'.$_REQUEST['Speciality'].'",
        `Gender`        = "'.$_REQUEST['Gender'].'",
        `SemestrId`     = "'.$_REQUEST['Semestr'].'", 
        `BirthDay`      = "'.$_REQUEST['BirthYear'].'-'.$_REQUEST['BirthMonth'].'-'.$_REQUEST['BirthDay'].'" 
        WHERE `NickName` = "'.$_REQUEST['NickName'].'"'
      );
    else :
      // Создаем запись в базе если такого юзера еще нет
        $password = $_POST['Passwd'];
        $salt = '$1$gRUR'; // TODO Заменить на генератор символов
        $md5hash = crypt($password,$salt);
      mysql_query(
        'INSERT INTO `Users` (`NickName`, `Passwd`, `SurName`, `FirstName`, `EMail`, 
          `HomePage`, `ICQ`, `vkontakte`, `GroupId`, `City`, `SpecId`, `Gender`, `SemestrId`, `BirthDay`)
        VALUES (
        "'.$_REQUEST['NickName'].'",
        "'.$md5hash.'",
        "'.$_REQUEST['SurName'].'",
        "'.$_REQUEST['FirstName'].'",
        "'.$_REQUEST['EMail'].'",
        "'.$_REQUEST['HomePage'].'",
        "'.$_REQUEST['ICQ'].'",
        "'.$_REQUEST['VKontakte'].'",
        "'.$_REQUEST['Group'].'",
        "'.$_REQUEST['City'].'",
        "'.$_REQUEST['Speciality'].'",
        "'.$_REQUEST['Gender'].'",
        "'.$_REQUEST['Semestr'].'",
        "'.$_REQUEST['BirthYear'].'-'.$_REQUEST['BirthMonth'].'-'.$_REQUEST['BirthDay'].'"
        )'
      );
      $Login = $_REQUEST['NickName'];
    endif;
  endif;

  if(isset($Login)) {
    $Res = mysql_query(
        'SELECT `Users`.*, `Speciality`.`FacultId` FROM `Users`
        LEFT JOIN `Speciality` ON `Users`.`SpecId` = `Speciality`.`id`
        WHERE `NickName` = "'.$Login.'"'
    ); // Получаем данные о пользователе
    $UserArray = mysql_fetch_assoc($Res);
    $BirthDayArray = explode("-", $UserArray['BirthDay']);
  }
?>
      
<h1><? echo $Title ?></h1>
<script type="text/javascript" src="/jscript/selectcascade.js"></script>
<? if(isset($_REQUEST['SaveProfile']) && $_REQUEST['NickName'] == $_COOKIE['login']):
    // Поменяем куки, если юзер редактировал свой профиль ?>
    <script type="text/javascript">
//        setCookie('FacultId', '', 0);
//        setCookie('SpecId', '', 0);
        setCookie('FacultId', <? echo $_REQUEST['Facult']     ?>, 999999999999, '/', 'misio.hut1.ru');
        setCookie('SpecId',   <? echo $_REQUEST['Speciality'] ?>, 999999999999, '/', 'misio.hut1.ru');
        setCookie('SemestrId', <? echo $_REQUEST['Semestr']   ?>, 999999999999, '/', 'misio.hut1.ru');
    </script>
<? endif; ?>

<form action="<? echo $_SERVER['PHP_SELF'] ?>" method="post">
<input type="hidden" name="SaveProfile" value="true">
<table class="optionsArrayTable">
<tbody>
  <tr>
    <td>Логин: </td>
    <td>
    <? if (isset($Login)): ?>
      <? echo $Login ?><input type="hidden" name="NickName" value="<? echo $Login ?>">
    <? else: ?>
      <input type="text" name="NickName">
    <? endif; ?>
    </td>
  </tr>
  <tr>
    <td>Пароль: </td>
    <td><input class="longinput" type="password" name="Passwd"></td>
  </tr>
  <tr>
    <td>Пароль (подтверждение): </td>
    <td><input class="longinput" type="password" name="PasswdConfirm"></td>
  </tr>
  <tr>
    <td>Фамилия: </td>
    <td><input class="longinput" type="text" name="SurName" <? echo 'value="'.$UserArray['SurName'].'"' ?> ></td>
  </tr>
  <tr>
    <td>Имя:</td>
    <td><input class="longinput" type="text" name="FirstName" <? echo 'value="'.$UserArray['FirstName'].'"' ?> ></td>
  </tr>
  <tr>
    <td>E-Mail адрес: </td>
    <td><input class="longinput" type="text" name="EMail" <? echo 'value="'.$UserArray['EMail'].'"' ?>></td>
  </tr>
  <tr>
    <td>Домашняя страница: </td>
    <td><input class="longinput" type="text" name="HomePage" <? echo 'value="'.$UserArray['HomePage'].'"' ?>></td>
  </tr>
  <tr>
    <td>ICQ-номер: </td>
    <td><input class="longinput" type="text" name="ICQ" <? echo 'value="'.$UserArray['ICQ'].'"' ?> ></td>
  </tr>
  <tr>
    <td>id VKontakte (номер профиля): </td>
    <td><input class="longinput" type="text" name="VKontakte" <? echo 'value="'.$UserArray['vkontakte'].'"' ?> ></td>
  </tr>
  <tr>
    <td>№ учебной группы: </td>
    <td><? echoGroupSelect($UserArray['GroupId']); ?></td>
  </tr>
  <tr>
    <td>Город: </td>
    <td><input class="longinput" type="text" name="City" <? echo 'value="'.$UserArray['City'].'"' ?> ></td>
  </tr>
  <tr>
    <td>Дата рождения: </td>
    <td>
      <select name="BirthDay">
        <option value="0">---</option>
        <? echoDaysOptions($BirthDayArray[2]); ?>
      </select>/
      <select name="BirthMonth">
        <option value="0"<? if($BirthDayArray[1] == 0) echo ' selected="selected"' ?>>---</option>
        <? echoMonthsOptions($BirthDayArray[1]); ?>
      </select>/
      <select name="BirthYear">
        <option value="0">---</option>
        <? echoYearsOptions($BirthDayArray[0]); ?>
      </select>
    </td>
  </tr>
  <tr>
    <td>Ваш пол:</td>
    <td>
      <select class="longselect" name="Gender">
          <option value="1" <? if($UserArray['Gender'] == 1) echo 'selected="selected"'; ?>>Мужской</option>
          <option value="2" <? if($UserArray['Gender'] == 2) echo 'selected="selected"'; ?>>Женский</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>Раздел по умолчанию:</td>
    <td><? echoSemestrNamesSelect($UserArray['SemestrId']); ?></td>
  </tr>
  <tr>
    <td>Факультет: </td>
    <td><? echoFacultSelect($UserArray['FacultId']); ?></td>
  </tr>
  <tr>
    <td>Специальность: </td>
    <td><? echoSpecialitySelect($UserArray['FacultId'], $UserArray['SpecId']); ?></td>
  </tr>
  <tr>
    <td>Аватар: </td>
    <td><input class="longinput" type="file" name="Avatar"></td>
  </tr>
</tbody>
</table>
<?
  if(isset($Login)) {
    echo '<input type="submit" value="Сохранить">';
  }
  else {
    echo '<input type="submit" value="Зарегистрироваться">';
  }
?>
</form>
<? include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php") ?>