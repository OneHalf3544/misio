<?php
//������ "PageSelector"
  $NNews = 5; 
  if(!isset($_GET['FirstNMat'])) {
    $FirstNMat = 0;} //����� �������� �� �������� � ����� ������ �������
  else {
    $FirstNMat = $_GET['FirstNMat'];}
  $PageSelector = pageSelector($FirstNMat, $NNews, 'News');
  echo $PageSelector;

// �������� �������� ���������� ($NNews) �������� �� ����, ������� � �������� ($FirstNNew).
$queryStr = "SELECT * FROM `News` ORDER BY `id` DESC LIMIT $FirstNMat, $NNews";
$Res = mysql_query($queryStr);

for (;$NewsArray = mysql_fetch_assoc($Res);):
  $NewsArray['Message'] = str_replace("&quot;", "\"", $NewsArray['Message']);
  $Profile = '/Users/profile.php?LogName='.$NewsArray['Author'].'"';
  $dateofadding = date("d-m-Y", $NewsArray['DateOfAdding']);
  //���������� ����� ������������
  $ResComm = mysql_query('SELECT COUNT(*) FROM `Comments` WHERE `TypeOfMat`="News" AND `NMaterial`='.$NewsArray['id']);
  $Comments_num = mysql_result($ResComm, 0);
  $Comments_URL = '/oneofmaterial.php?TypeOfMat=News&NMaterial='.$NewsArray['id'];
  // ����� ������� ����� html-���� ?>
  <div class="matBlock">
    <h2 class="title"><a href="<? echo $Comments_URL ?>"><? echo $NewsArray['Title'] ?></a> 
      <a class="editlink" href="/newsmaker.php?id=<? echo $NewsArray['id'] ?>">[�������������]</a></h2>
    <div class="content">
      <div class="Message"><? echo add_p_Tag($NewsArray['Message']) ?></div>
      <div class="Details">
        ����������: <? echo $NewsArray['Views'] ?> 
        | �������: <a href="<? echo $Profile ?>"><? echo $NewsArray['Author'] ?></a> 
        | ����: <span title=""><? echo $dateofadding ?></span> 
        | <a href="<? echo $Comments_URL ?>">����������� (<? echo $Comments_num ?>)</a>
      </div>
    </div>
  </div>
<? endfor;

echo $PageSelector;
?>