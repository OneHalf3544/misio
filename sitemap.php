<? $Title = "����� �����" ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 

<!-- Body --> 
<div class="textOfPage">
  <h1><? echo $Title; ?></h1>
  <ul>
      <li>�������� �����</li>
    <ul>
    <? 
      $Res = mysql_query('SELECT `id`, `Title` FROM `Pages` ORDER BY `Title`');
      while(($S = mysql_fetch_assoc($Res)) != false) {
        echo "<li><a href=\"/Pages.php?id=".$S['id']."\">".$S['Title']."</a></li>";
      }
    ?>
        <li><a href="/SitePages/editsitepages.php">�������� �������</a></li>
    </ul>
    
    <li> �������������
    <ul>
        <li> <a href="/prepods/prepods.php">������ ��������������</a></li>
        <li> <a href="/prepods/prepodedit.php">�������� �������� �������������</a></li>
        <li> <a href="/prepods/depts.php">������ ������ ������������</a></li>
    </ul>
    
    <li> ����������
    <ul>
      <li> <a href="/schedule/raspisanie.php">���������� �����</a></li>
      <li> <a href="/schedule/schededit.php">�������� ����������</a></li>
      <li> <a href="/specmaker.php">�������� ��������������</a></li>
    </ul>
    
    <li> ������������ �����
    <ul>
      <li> <a href="/Users/users.php">������ �������������</a></li>
      <li> <a href="/Users/register.php">��� �������</a></li>
    </ul>
    
    <li> �����
    <ul>
      <li> <a href="/files/files.php">�����</a></li>
      <li> <a href="/files/addfile.php">�������� ����</a></li>
    </ul>
    
  </ul>
</div>
<!-- /Body -->

<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php") ?>