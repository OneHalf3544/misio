<? $Title = "Редактировать расписание групп"; ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 
<!-- Body --> 

<script type="text/javascript" src="/jscript/schededit.js"></script>
<script type="text/javascript" src="/jscript/selectcascade.js"></script>
<h1><? echo $Title ?></h1>
<div class="textOfPage">
<p>На этой страничке вы можете редактировать или добавить расписание группы. Для того, чтобы нужные предметы были добавлены в списки, они должны быть "прицеплены" к специальности на страничке <a href="/specmaker.php">редактора специальностей</a>.
<p>Для редактирования вам необходимо сначала выбрать из списков свою специальность (это подгрузит названия предметов в списки), затем выбрать группу (либо создать новую, указав название)
</div>
<form>
<table class="optionsArrayTable">
<colgroup>
  <col width="150px">
  <col width="*">
</colgroup>
<tbody><tr>
<td>Факультет:</td>
<td><? echoFacultSelect() ?></td></tr>
<tr><td>Специальность: </td>
  <td><? echoSpecialitySelect(); ?></td>
</tr>
<tr><td>Семестр: </td>
  <td><select id="Semestr" class="shortselect">
    <option value="0">Все</option>
    <? 
      $Res = mysql_query("SELECT `id`, `SemName` FROM `SemestrNames`");
      $SemArray = mysql_fetch_assoc($Res);
      while($SemArray != false) {
        echo '<option value="'.$SemArray['id'].'">'.$SemArray['SemName']."</option>";
        $SemArray = mysql_fetch_assoc($Res);
      }
    ?>
  </select></td></tr>
  <tr><td>Группа: </td>
  <td>Выбрать <select id="group" disabled="disabled">
    <option value="0">- Группа -</option>
    </select>
    или <input type="text" id="newGroupName" class="shortinput"><input type="button" id="btnCreateGroup" value="Создать">
  </td></tr>
<tbody></table>
</form>

<table class="tableSpec">
<tr>
  <th>Расписание</th>
</tr>
<tr>
  <td class="subjAll">
  <input type="button" id="btnSave" value="Сохранить">
  <? 
  $WeekDays = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
  $LectTime = array('8:30', '10:10', '11:50', '14:00', '15:40', '17:20', '19:00', '20:40');
  $PrepSel = PrepodSelect();
  foreach ($WeekDays as $Day) { ?>
    <div class="schedule">
      <div class="title"><? echo $Day ?></div>
      <div class="content">
        <table class="scheduleWeekDay" width="100%">
        <colgroup>
          <col width="40px">
          <col width="16px">
          <col width="*">
          <col width="100px">
          <col width="60px">
          <col width="40px">
        </colgroup>
        <tbody>
          <tr>
            <th>Время</th>
            <th colspan="3">Предмет</th>
            <th>Преподаватель</th>
            <th>Ауд.</th>
          </tr>
          <?php 
          foreach($LectTime as $t) { ?>
          <tr>
            <td>
            <? if($t != '14:00')
                echo $t;
               else { ?>
                <select class="antract">
                  <option value="0">13:30</option>
                  <option value="1" selected="selected">14:00</option>
                </select>
            <? } ?>
            </td>
            <td>
              <img alt="split" src="/img/split.png" title="Раздвоить/слить предмет на числитель/знаменатель">
            </td>
            <td>
              <select class="subjSelect">
                <option value="0">Не выбран</option>
              </select>
            </td>
            <td>
              <select class="longselect">
                <option value="none">Не выбран</option>
                <option value="Lection">Лекция</option>
                <option value="Pract">Практика</option>
                <option value="Lab">Лабораторная</option>
              </select>
            </td>
            <td>
              <? echo $PrepSel; ?>
            </td>
            <td><input type="text" value="" class="shortinput"></td>
          </tr>
          <? } //End foreach ?>
        </tbody>
        </table>
      </div>
    </div>
  <? 
  } //End foreach
  ?>
    
  </td></tr></table>

<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php")?>