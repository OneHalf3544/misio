<?php $Title = "Редактор специальностей";
  include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php"); ?>
<!-- Body --> 

<script type="text/javascript" src="/jscript/selectcascade.js"></script>
<script type="text/javascript" src="/jscript/specmaker.js"></script>
<h1><? echo $Title ?></h1>
<div class="textOfPage">
    <p>На этой страничке вы можете "прикрепить" предметы к своей специальности
        в нужный семестр. С левой стороны отображаются предметы соответствующие
        выбранной специальности, а справа - созданные, или выданные поиском.</p>
    <p>Нажатие на пиктограммы <img alt="Удалить" src="/img/specmaker/del.png">
        или <img alt="Добавить" src="/img/specmaker/add.png"> добавляют/удаляют
        предметы в/из левого списка.</p>
    <p>Для того, чтобы отредактировать информацию о предмете, сделайте двойной
        клик на заголовке</p>
    <p>Старайтесь не дублировать создаваемые предметы, воспользуйтесь сначала
        поиском. Т.к. далее эти списки будут использоваться для фильтрации
        файлов, то логично, что если предмет ведут в целом потоке, то и здесь
        этот предмет должен быть один для всех специальностей этого потока.
        Т.е, например, такие предметы, как "Высшая математика" или
        "Отечественная история" должны быть практически у всех специальностей,
        "Электротехника" - для специальностей прифорфака и т.д.</p>
    <p>С другой стороны, необходимо называть предметы разных семестров
        по-разному, например, "ТеорМех (Статика)", "ТеорМех (Кинематика)" и т.д.
        Дело в том, что при добавлении файлов не сохраняется информация о
        семестре, а восстанавливается потом исходя из данных составленных на
        этой страничке. (Это нужно для возможности создания "общих" для разных
        специальностей предметов)</p>
</div>
<form action="/error.php">
<table class="optionsArrayTable">
    <tbody>
    <tr>
        <td>Факультет:</td>
        <td><? echoFacultSelect() ?></td>
    </tr>
    <tr>
        <td>Специальность: </td>
        <td><? echoSpecialitySelect(); ?></td>
    </tr>
    <tr>
        <td>Семестр: </td>
        <td><? echoSemestrNamesSelect();?></td>
    </tr>
    <tbody>
</table>
</form>

<table class="tableSpec">
<tr><th>Предметы выбранного семестра и специальности</th><th>Поиск и/или создание нового предмета</th></tr>
<tr>
  <td class="specCurr">
      <? 
      if(isset ($_COOKIE['FacultId']) && isset($_COOKIE['SpecId']) &&
              isset ($_COOKIE['SemestrId'])):
          $QueryStr = 'SELECT `Subjects`.* FROM `Subjects` ';
          $QueryStr .= 'LEFT JOIN `SpecNSubj` ON `SpecNSubj`.`SubjectId` = `Subjects`.`id` ';
          $QueryStr .= 'WHERE `SpecNSubj`.`SpecId` = "'.$_COOKIE['SpecId'].'" ';
          if($_COOKIE['SemestrId'] != "0")
              $QueryStr .= 'AND `SpecNSubj`.`Semestr` = '.$_COOKIE['SemestrId'].' ';
          $QueryStr .= 'ORDER BY `Subjects`.`Title` ASC';
          $Res = mysql_query($QueryStr);

          while (($S = mysql_fetch_assoc($Res)) != false): ?>
              <div id="subjId<? echo $S['id']; ?>" class="subject">
                  <div class="title"><? echo $S['Title']; ?></div>
                  <div class="content"><? echo add_p_Tag($S['Description']); ?></div>
              </div>
          <? endwhile;
      endif; ?>
  </td>
  <td class="subjAll">
    <form id="searchForm" action="/error.php">Поиск по названию.<br>
      <input type="text" id="findStr" onkeydown="javascript:if(13==event.keyCode){searchSubjects(); return false;}"><input type="button" value="Искать" onclick="searchSubjects()"><br>
      Или <input type="button" value="Создать" id="createButton">
    </form>
  </td>
</tr></table>
<? include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php"); ?>