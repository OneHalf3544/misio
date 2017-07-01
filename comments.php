<hr>
<h1 class="commentsTitle">Комментарии пользователей:</h1>
<div class="pageSelector">
    <?
    /* Этот файл должен включаться коммандой "include",
    а переменные $TypeOfMat и $NMaterial должны быть определены "снаружи" */

  //Рисуем "PageSelector"
  $NComm = 50; //Число комментариев на странице
  if(!isset($_GET['FirstNComm'])) {
    $FirstNComm = 0;} //Номер первого комментария
  else {
    $FirstNComm = $_GET['FirstNComm'];}

  $Res = mysql_query("SELECT count(*) FROM `Comments` WHERE `TypeOfMat` = \"$TypeOfMat\" and `NMaterial` = $NMaterial");
  $CountOfComm = mysql_result($Res, 0); //Количество комментариев в базе
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
// Выбираем заданное количество ($NComm) комментариев из базы, начиная с заданного ($FirstNComm).
$Res = mysql_query(
    "SELECT * FROM `Comments` WHERE `TypeOfMat` = \"$TypeOfMat\" and `NMaterial` = $NMaterial
    ORDER BY `DateOfAdding` ASC LIMIT $FirstNComm, $NComm"
);
while (($CommArray = mysql_fetch_assoc($Res)) != false):
  // Далее следует кусок html-кода, соответствующий одному комментарию ?>
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