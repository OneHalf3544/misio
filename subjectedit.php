<?
  include("utf8cp1251.php");
  $id = str_replace('subjId', '', $_REQUEST['id']);
  $subject = htmlspecialchars(utf8_win($_REQUEST['subject']));
  $descr = htmlspecialchars(utf8_win($_REQUEST['descr']));
  
  $conn = mysql_connect("database", "misio8", "oinYax5d");
  if (!$conn) die("�� ���� ������������ � ����");
  mysql_select_db("misio8") or die ('������: ' . mysql_error());
  
  $StrQuery = "UPDATE `Subjects` SET `Title`=\"$subject\", `Description`=\"$descr\" WHERE `id` = $id";
  
  mysql_query($StrQuery);
  
  mysql_close($conn);
?>