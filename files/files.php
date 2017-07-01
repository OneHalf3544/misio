<? $Title = "Файлы" ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 

<!-- Body --> 
<h1>Файлы</h1>
<? include($_SERVER["DOCUMENT_ROOT"]."/files/filesfilter.php"); ?>
<?
  $PageSelector = '';
  $NFiles = 10; 
  if(!isset($_GET['FirstNFile'])) {
    $FirstNFile = 0; //Число новостей на странице и номер первой новости
  }
  else {
    $FirstNFile = $_GET['FirstNFile'];
  }

if(!isset($_REQUEST['Facult'])) { // Если параметры в URL отсутствуют
    $_REQUEST['Facult'] = $_COOKIE['FacultId'];
    $_REQUEST['Speciality'] = $_COOKIE['SpecId'];
    $_REQUEST['Subject'] = '';
    $_REQUEST['MaterialType'] = '';
    $_REQUEST['PrepodId'] = 0;
    $_REQUEST['Sorting'] = 1;
    if (isset($_COOKIE['SemestrId']))
        $_REQUEST['Semestr'] = $_COOKIE['SemestrId'];
    else
        $_REQUEST['Semestr'] = 0;
}

/* Если выбран предмет */
if ($_REQUEST['Subject'] != "") {
  $Where = " WHERE `Files`.`SubjectId` = ".$_REQUEST['Subject'];
}
elseif ($_REQUEST['Speciality'] != 0) {
    if ($_REQUEST['Semestr'] != 0)
        $Semestr =  'AND `Semestr` = '.$_REQUEST['Semestr'];
    
    $Where = 'LEFT JOIN `SpecNSubj`   ON `SpecNSubj`.`SubjectId` = `Subjects`.`id` '.$Semestr.
        ' WHERE `SpecNSubj`.`SpecId` = '.intval($_REQUEST['Speciality']).' ';
}
elseif ($_REQUEST['Facult'] != 0) {
    if ($_REQUEST['Semestr'] != 0)
        $Semestr =  ' AND `SpecNSubj`.`Semestr` = '.$_REQUEST['Semestr'].' ';

    $Where = 'LEFT JOIN `SpecNSubj`   ON `SpecNSubj`.`SubjectId` = `Subjects`.`id` '.$Semestr.
        'LEFT JOIN `Speciality`  ON `Speciality`.`id` = `SpecNSubj`.`SpecId` '.
        'WHERE `Speciality`.`FacultId` = '.intval($_REQUEST['Facult']).' ';
}
elseif (isset($_REQUEST['Semestr']) && $_REQUEST['Semestr'] != 0) {
    $Where = 'LEFT JOIN `SpecNSubj`   ON `SpecNSubj`.`SubjectId` = `Subjects`.`id` '.$Semestr.
        ' WHERE `SpecNSubj`.`Semestr` = '.intval($_REQUEST['Semestr']).' ';
}

if(isset($_REQUEST['MaterialType']) && 
    $_REQUEST['MaterialType'] != "0" &&
    $_REQUEST['MaterialType'] != "") { // Если указан тип материала
  if(isset($Where)) { //Если переменная $Where уже назначена
    $Where .= ' AND ';
  }
  else {
    $Where = 'WHERE ';
  }
  $Where .= '`Files`.`Type` LIKE "%'.$_REQUEST['MaterialType'].'%"';
}

if(isset($_REQUEST['PrepodId']) && 
    $_REQUEST['PrepodId'] != "0") { // Если указан препод
  if(isset($Where)) { //Если переменная $Where уже назначена
    $Where .= ' AND ';
  }
  else {
    $Where = 'WHERE ';
  }
  $Where .= '`Files`.`PrepodId` = '.intval($_REQUEST['PrepodId']);
}

if(@$_REQUEST['ShowUnmarkedFiles'] == 'yes') { // Если указан препод
  if(isset($Where)) { //Если переменная $Where уже назначена
    $Where .= ' OR ';
  }
  else {
    $Where = 'WHERE ';
  }
  $Where .= '`Files`.`SubjectId` = 0 ';
}

if (isset($_REQUEST['Sorting']) && $_REQUEST['Sorting'] != '') {
 $OrderBy = "ORDER BY ";
 switch ($_REQUEST['Sorting']) {
  case 1: 
    $OrderBy .= "DateOfAdding DESC ";
    break;
  case 2:
    $OrderBy .= "Title ";
    break;
  case 3:
    $OrderBy .= "size ";
    break;
  case 4:
    $OrderBy .= "Type ";
    break;
  case 5:
    $OrderBy .= "CommCount DESC ";
    break;
  case 6:
    $OrderBy .= "Loads ";
    break;
 }
}
else {
  $OrderBy .= ' ORDER BY `id` DESC';
}

$Limit = "LIMIT $FirstNFile, $NFiles"; 

$queryStr = "SELECT DISTINCT `Files`.`id`, `Files`.`*`, `Subjects`.`Title` AS SubjTitle, `Prepods`.`PrepodName` ";
$queryStr .= "FROM `Files` LEFT JOIN `Prepods` ON `Files`.`PrepodId` = `Prepods`.`id` ";
$queryStr .= "LEFT JOIN `Subjects` ON `Files`.`SubjectId` = `Subjects`.`id` $Where ";

if (isset($OrderBy)) {
    $queryStr .= "$OrderBy ";
} else {
    $queryStr .= "ORDER BY `id` DESC ";
}

$Res = mysql_query(
    'SELECT COUNT(DISTINCT `Files`.`id`) FROM `Files`
    LEFT JOIN `Subjects` ON `Files`.`SubjectId` = `Subjects`.`id` '.$Where
);
$CountOfFiles = mysql_result($Res, 0); //Количество новостей в базе

for($i = 1; $CountOfFiles > $NFiles && $i <= ceil($CountOfFiles / $NFiles); $i++) {
  if ($_REQUEST['FirstNFile'] == ($i-1)*$NFiles)
    $PageSelector .= '<span class="currentPage">'.$i.'</span> ';
  else
    $PageSelector .= '<a href="'.filterFilesUrlLink(
        $_REQUEST['Facult'],
        $_REQUEST['Speciality'],
        $_REQUEST['Semestr'],
        $_REQUEST['Subject'],
        $_REQUEST['PrepodId'],
        $_REQUEST['MaterialType'],
        $_REQUEST['Sorting'],
        $_REQUEST['ShowUnmarkedFiles']
    ).'&FirstNFile='.($i-1)*$NFiles.'">'.$i.'</a> ';
}

$PageSelector = '<div class="pageSelector">'.$PageSelector."</div>";
echo $PageSelector;

// echo $queryStr.$Limit;
$Res = mysql_query($queryStr.$Limit);
while (($FilesArray = mysql_fetch_assoc($Res)) != false) {
  include ($_SERVER["DOCUMENT_ROOT"]."/files/onefile.php");
}
echo $PageSelector;
?>
<!-- /Body -->

<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php") ?>