<?php $Title = "�������� ��������������";
  include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php"); ?>
<!-- Body --> 

<script type="text/javascript" src="/jscript/selectcascade.js"></script>
<script type="text/javascript" src="/jscript/specmaker.js"></script>
<h1><? echo $Title ?></h1>
<div class="textOfPage">
    <p>�� ���� ��������� �� ������ "����������" �������� � ����� �������������
        � ������ �������. � ����� ������� ������������ �������� ���������������
        ��������� �������������, � ������ - ���������, ��� �������� �������.</p>
    <p>������� �� ����������� <img alt="�������" src="/img/specmaker/del.png">
        ��� <img alt="��������" src="/img/specmaker/add.png"> ���������/�������
        �������� �/�� ������ ������.</p>
    <p>��� ����, ����� ��������������� ���������� � ��������, �������� �������
        ���� �� ���������</p>
    <p>���������� �� ����������� ����������� ��������, �������������� �������
        �������. �.�. ����� ��� ������ ����� �������������� ��� ����������
        ������, �� �������, ��� ���� ������� ����� � ����� ������, �� � �����
        ���� ������� ������ ���� ���� ��� ���� �������������� ����� ������.
        �.�, ��������, ����� ��������, ��� "������ ����������" ���
        "������������� �������" ������ ���� ����������� � ���� ��������������,
        "��������������" - ��� �������������� ���������� � �.�.</p>
    <p>� ������ �������, ���������� �������� �������� ������ ���������
        ��-�������, ��������, "������� (�������)", "������� (����������)" � �.�.
        ���� � ���, ��� ��� ���������� ������ �� ����������� ���������� �
        ��������, � ����������������� ����� ������ �� ������ ������������ ��
        ���� ���������. (��� ����� ��� ����������� �������� "�����" ��� ������
        �������������� ���������)</p>
</div>
<form action="/error.php">
<table class="optionsArrayTable">
    <tbody>
    <tr>
        <td>���������:</td>
        <td><? echoFacultSelect() ?></td>
    </tr>
    <tr>
        <td>�������������: </td>
        <td><? echoSpecialitySelect(); ?></td>
    </tr>
    <tr>
        <td>�������: </td>
        <td><? echoSemestrNamesSelect();?></td>
    </tr>
    <tbody>
</table>
</form>

<table class="tableSpec">
<tr><th>�������� ���������� �������� � �������������</th><th>����� �/��� �������� ������ ��������</th></tr>
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
    <form id="searchForm" action="/error.php">����� �� ��������.<br>
      <input type="text" id="findStr" onkeydown="javascript:if(13==event.keyCode){searchSubjects(); return false;}"><input type="button" value="������" onclick="searchSubjects()"><br>
      ��� <input type="button" value="�������" id="createButton">
    </form>
  </td>
</tr></table>
<? include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php"); ?>