<hr>
<h1 class="commentsTitle">����������� �������������:</h1>
<div class="pageSelector">
    <?
    /* ���� ���� ������ ���������� ��������� "include",
    � ���������� $TypeOfMat � $NMaterial ������ ���� ���������� "�������" */

  //������ "PageSelector"
  $NComm = 50; //����� ������������ �� ��������
  if(!isset($_GET['FirstNComm'])) {
    $FirstNComm = 0;} //����� ������� �����������
  else {
    $FirstNComm = $_GET['FirstNComm'];}

  $Res = mysql_query("SELECT count(*) FROM `Comments` WHERE `TypeOfMat` = \"$TypeOfMat\" and `NMaterial` = $NMaterial");
  $CountOfComm = mysql_result($Res, 0); //���������� ������������ � ����
  if ($CountOfComm > $NComm) {
    for($i = 1; $i <= ceil($CountOfComm / $NComm); $i++) {
        switch ($TypeOfMat) {
            case 'SitePages':
                echo '<a href="/Pages.php?id='.$NMaterial.'&FirstNComm='.($i-1)*$NComm.'">'.$i.'</a> ';
                break;

            default:
                echo '<a href="'.$_SERVER['PHP_SELF']."?TypeOfMat=$TypeOfMat&NMaterial=$NMaterial&FirstNComm=".($i-1)*$NComm."\">$i</a> ";
                break;
        }
    }
  } ?>
</div>
<?
// �������� �������� ���������� ($NComm) ������������ �� ����, ������� � ��������� ($FirstNComm).
$Res = mysql_query(
    "SELECT * FROM `Comments` WHERE `TypeOfMat` = \"$TypeOfMat\" and `NMaterial` = $NMaterial
    ORDER BY `DateOfAdding` ASC LIMIT $FirstNComm, $NComm"
);
while (($CommArray = mysql_fetch_assoc($Res)) != false):
  // ����� ������� ����� html-����, ��������������� ������ ����������� ?>
    <div class="comments">
      <div class="title">
        <a href="$Profile"><? echo profileURL($CommArray['Author']); ?></a>
        <span class="time"><? echo date("d-m-Y H:i", $CommArray['DateOfAdding']) ?></span>
      </div>
      <div class="content">
        <? echo $CommArray['Text'] ?>
      </div>          
    </div>
<? endwhile; ?>