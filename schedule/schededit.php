<? $Title = "������������� ���������� �����"; ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 
<!-- Body --> 

<script type="text/javascript" src="/jscript/schededit.js"></script>
<script type="text/javascript" src="/jscript/selectcascade.js"></script>
<h1><? echo $Title ?></h1>
<div class="textOfPage">
<p>�� ���� ��������� �� ������ ������������� ��� �������� ���������� ������. ��� ����, ����� ������ �������� ���� ��������� � ������, ��� ������ ���� "����������" � ������������� �� ��������� <a href="/specmaker.php">��������� ��������������</a>.
<p>��� �������������� ��� ���������� ������� ������� �� ������� ���� ������������� (��� ��������� �������� ��������� � ������), ����� ������� ������ (���� ������� �����, ������ ��������)
</div>
<form>
<table class="optionsArrayTable">
<colgroup>
  <col width="150px">
  <col width="*">
</colgroup>
<tbody><tr>
<td>���������:</td>
<td><? echoFacultSelect() ?></td></tr>
<tr><td>�������������: </td>
  <td><? echoSpecialitySelect(); ?></td>
</tr>
<tr><td>�������: </td>
  <td><select id="Semestr" class="shortselect">
    <option value="0">���</option>
    <? 
      $Res = mysql_query("SELECT `id`, `SemName` FROM `SemestrNames`");
      $SemArray = mysql_fetch_assoc($Res);
      while($SemArray != false) {
        echo '<option value="'.$SemArray['id'].'">'.$SemArray['SemName']."</option>";
        $SemArray = mysql_fetch_assoc($Res);
      }
    ?>
  </select></td></tr>
  <tr><td>������: </td>
  <td>������� <select id="group" disabled="disabled">
    <option value="0">- ������ -</option>
    </select>
    ��� <input type="text" id="newGroupName" class="shortinput"><input type="button" id="btnCreateGroup" value="�������">
  </td></tr>
<tbody></table>
</form>

<table class="tableSpec">
<tr>
  <th>����������</th>
</tr>
<tr>
  <td class="subjAll">
  <input type="button" id="btnSave" value="���������">
  <? 
  $WeekDays = array('�����������', '�������', '�����', '�������', '�������', '�������');
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
            <th>�����</th>
            <th colspan="3">�������</th>
            <th>�������������</th>
            <th>���.</th>
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
              <img alt="split" src="/img/split.png" title="���������/����� ������� �� ���������/�����������">
            </td>
            <td>
              <select class="subjSelect">
                <option value="0">�� ������</option>
              </select>
            </td>
            <td>
              <select class="longselect">
                <option value="none">�� ������</option>
                <option value="Lection">������</option>
                <option value="Pract">��������</option>
                <option value="Lab">������������</option>
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