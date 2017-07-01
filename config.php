<?php
    require_once("utf8cp1251.php");
    require_once("fileLoaded.php");
/***********************************************************************************
Константы
***********************************************************************************/
define('TempDir', $_SERVER["DOCUMENT_ROOT"].'/files/files/tmp/');
// Постоянная часть заголовка
define('CONSTANT_TITLE_PART', ' - МИСиО, сайт студентов приборфака СПбГМТУ');
// Адрес бакэнда для Ajax-запросов
define('AJAXBackendURL', '/test2.php');

$WeekDays = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');

$MaterialType = array(
    "News"      => "Новости",
    "Files"     => "Файлы",
    "Photos"    => "Фотографии",
    "Prepods"   =>"Преподаватели",
    "SitePages" =>"Страницы сайта"
);

$FilesType = array(
    "Bileti"        => "Билеты/вопросы/задачи",
    "Kursovie"      => "Курсовые работы",
    "Laboratornie"  => "Лабораторные",
    "Lektsii"       => "Лекции",
    "Metodichki"    => "Методички",
    "Program ms"     => "Программы",
    "Resheniya"     => "Решения типовиков/задач",
    "Uchebniki"     => "Учебники",
    "Shpargalki"    => "Шпаргалки",
    "Other"         => "Прочее..",
);
  
$PrepodStatus = array(
    "none"        => "Не указан",
    "Assist"      => "Асистент",
    "Prep"        => "Преподаватель",
    "StPrep"      => "Старший преподаватель",
    "Dotsent"     => "Доцент",
    "Proffessor"  => "Профессор"
);

/***********************************************************************************
Функции необходимые для страниц сайта
***********************************************************************************/

/*
 * Возвращает ссылку с параметрами поиска (нужно вставить в аттрибут href)
 */
function filterFilesUrlLink($Facult, $Speciality, $Semestr, $Subject, 
        $PrepodId, $MaterialType, $Sorting, $ShowUnmarkedFiles) {
    return '/files/files.php'.
        '?Facult='.$Facult.
        '&Speciality='.$Speciality.
        '&Semestr='.$Semestr.
        '&Subject='.$Subject.
        '&PrepodId='.$PrepodId.
        '&MaterialType='.$MaterialType.
        '&Sorting='.$Sorting.
        '&ShowUnmarkedFiles='.$ShowUnmarkedFiles;
}

function echoSemestrNamesSelect($SemestrId = -1) {
    if ($SemestrId == -1 && isset($_COOKIE['SemestrId']))
        $SemestrId = $_COOKIE['SemestrId'];
    ?>
    <select id="Semestr" name="Semestr" class="shortselect">
    <option value="0">Все</option>
    <?
      $Res = mysql_query("SELECT `id`, `SemName` FROM `SemestrNames`");
      while(($SemArray = mysql_fetch_assoc($Res)) != false):
          if($SemArray['id'] == $SemestrId): ?>
              <option value="<? echo $SemArray['id']; ?>" selected="selected">
                <? echo $SemArray['SemName']; ?>
              </option>
          <? else: ?>
              <option value="<? echo $SemArray['id']; ?>">
                <? echo $SemArray['SemName']; ?>
              </option>
          <? endif;
      endwhile; ?>
  </select>
<? }

/*
 * Выводит select со списком предметов указанной специальности и семестра
 */
function echoSubjectSelect($SubjectId, $SpecId = -1, $SemestrId = -1) {
    if ($SpecId == -1
            && isset($_COOKIE['SpecId']))
        $SpecId = $_COOKIE['SpecId'];
    if($SemestrId == -1 && isset($_COOKIE['SemestrId'])) {
        $SemestrId = $_COOKIE['SemestrId'];
    }
    if($SemestrId != 0)
        $WhereSemestr = ' AND `SpecNSubj`.`Semestr` = '.$SemestrId;
    $Res = mysql_query(
        'SELECT `Subjects`.`id`, `Subjects`.`Title` FROM `Subjects`
        LEFT JOIN `SpecNSubj` ON `SpecNSubj`.`SubjectId` = `Subjects`.`id`'.
        ' WHERE `SpecNSubj`.`SpecId` = '.$SpecId.
        $WhereSemestr.
        ' ORDER BY `Subjects`.`Title`'
    ); ?>
<select class="longselect" name="Subject" id="Subject">
    <option value=""> - Не указан - </option>
    <option value="0">Не выбран</option>
    <? 
    if($SpecId != '')
        while(($S = mysql_fetch_assoc($Res)) != false):
            if ($S['id'] == $SubjectId) :
                echo '<option value="'.$S['id'].'" selected="selected">'.$S['Title'].'</option>';
            else :
                echo '<option value="'.$S['id'].'">'.$S['Title'].'</option>';
            endif;
        endwhile; ?>
</select>
<? }

/*
 * Выводит select со списком групп
 */
function echoGroupSelect($GroupId = -1) { ?>
    <select id="group" name="Group">
    <option value="0">- Группа -</option>
    <?
      if ($GroupId == -1 && isset($_COOKIE['GroupId']))
        $GroupId = $_COOKIE['GroupId'];

      $Res = mysql_query('SELECT * FROM `Groups` ORDER BY `Title`');
      while (($S=mysql_fetch_assoc($Res)) != false): ?>
        <option value="<? echo $S['id']; ?>"
            <? if($GroupId == $S['id']): ?> selected="selected" <? endif; ?>
        ><? echo $S['Title']; ?></option>
      <? endwhile; ?>
    </select>
<? }

function materialURL($TypeOfMat, $NMaterial){
    switch ($TypeOfMat) {
        case 'SitePages':
            $result = '/Pages.php?id='.$NMaterial;
        break;
        default:
            $result = '/oneofmaterial.php?TypeOfMat='.$TypeOfMat.'&NMaterial='.$NMaterial;
        break;
    }
    return $result;
}

function profileURL ($Login) { // Возвращает ссылку на профиль по логину пользователя
    return "<a href=\"/Users/profile.php?LogName=$Login\" title=\"Профиль пользователя\">$Login</a>";
}

function pageSelector ($FirstNMat, $NMat, $MatType) {
  $PageSelector = '';

  $Res = mysql_query('SELECT COUNT(*) FROM `'.$MatType.'`');
  $CountOfMat = mysql_result($Res, 0); //Количество новостей в базе
  
  for($i = 1; $i <= ceil($CountOfMat / $NMat); $i++) {
    if ($FirstNMat == ($i-1)*$NMat)
      $PageSelector .= "<span class=\"currentPage\">$i</span> ";
    else
      $PageSelector .= '<a href="'.$_SERVER['PHP_SELF'].'?FirstNMat='.($i-1)*$NMat.'">'.$i.'</a> ';
  }
  $PageSelector = '<div class="pageSelector">'.$PageSelector."</div>";
  return $PageSelector;
}

function PrepodOptions ($PrepodId) {
    $Res = mysql_query(
      'SELECT `Prepods`.`id`, `Prepods`.`PrepodName` FROM `Prepods`
      WHERE `DeptId` = 0 ORDER BY `PrepodName`'
    );
    $result = '<optgroup label="Кафедра не указана">';
    while(($S = mysql_fetch_row($Res)) != false):
        if($PrepodId == $S[0]) {
            $result .= "<option value=\"$S[0]\" selected=\"selected\">$S[1]</option>";
        }
        else {
            $result .=  "<option value=\"$S[0]\">$S[1]</option>";
        }
    endwhile;
    $result .= '</optgroup>';

    $Res = mysql_query(
      'SELECT `Prepods`.`id`, `Prepods`.`PrepodName`, `Prepods`.`DeptId`, `Depts`.`DeptName` FROM `Prepods`
      LEFT JOIN `Depts` ON `Depts`.`id` = `Prepods`.`DeptId`
      WHERE `DeptId` <> 0 ORDER BY `DeptName`, `PrepodName`'
    );

    while(($S = mysql_fetch_assoc($Res)) != false):
        if($LastDeptId != $S['DeptId']):
            if(isset($LastDeptId))
                $result .=  '</optgroup>';
            $result .= '<optgroup label="Кафедра &quot;'.$S['DeptName'].'&quot;">';
        endif;

        if($PrepodId == $S['id'])
            $result .=  '<option value="'.$S['id'].'" selected="selected">'.$S['PrepodName'].'</option>';
        else
            $result .=  '<option value="'.$S['id'].'">'.$S['PrepodName'].'</option>';

        $LastDeptId = $S['DeptId'];
    endwhile;

    return $result;
}

function PrepodSelect($PrepodId = 0) {
    $result = '<select name="PrepodId" class="longselect">'.
        '<option value="0"> - Не выбран - </option>'.
        PrepodOptions($PrepodId).
    '</select>';
    return $result;
}

function echoFacultSelect($FacultId = -1) { ?>
    <select id="Facult" name="Facult" class="longselect">
    <option value="0"> - Факультет - </option>
        <?
        if ($FacultId == -1 && isset($_COOKIE['FacultId']))
            $FacultId = $_COOKIE['FacultId'];
        $Res = mysql_query("SELECT `id`, `FacultName` FROM `Facults` ORDER BY `FacultName`");
        while(($FacultArray = mysql_fetch_assoc($Res)) != false) {
            if ($FacultId == $FacultArray['id'])
                echo '<option value="'.$FacultArray['id'].'" selected="selected">'.$FacultArray['FacultName'].'</option>';
            else
                echo '<option value="'.$FacultArray['id'].'">'.$FacultArray['FacultName'].'</option>';
        } ?>
    </select>
<? }

function echoSpecialitySelect($FacultId = -1, $SpecId = -1) {
    if ($FacultId == -1 && isset($_COOKIE['FacultId']))
        $FacultId = $_COOKIE['FacultId'];
    if ($SpecId == -1 && isset($_COOKIE['SpecId']))
        $SpecId = $_COOKIE['SpecId']; ?>
    <select id="Speciality" name="Speciality" class="longselect" <? if ($FacultId == 0) echo 'disabled="disabled"'; ?>>
    <option value="0"> - Специальность - </option>
    <? $Res = mysql_query("SELECT `id`, `Spec` FROM `Speciality` WHERE `FacultId` = $FacultId ORDER BY `Spec`");
    while(($SpecArray = mysql_fetch_row($Res)) != false) {
        if ($SpecId == $SpecArray[0])
            echo "<option value=\"$SpecArray[0]\" selected=\"selected\">".$SpecArray[1]."</option>";
        else
            echo "<option value=\"$SpecArray[0]\">".$SpecArray[1]."</option>";
    } ?>
    </select>
<? }

function echoDaysOptions ($Day) {
    for ($i = 1; $i <= 31; $i++) {
        if($Day == $i) {
            echo "<option value=\"$i\" selected=\"selected\">$i</option>";
        }
        else {
            echo "<option value=\"$i\">$i</option>";
        }
    }
}

function echoMonthsOptions ($Month) {
    for ($i = 1; $i <= 12; $i++) {
        if($Month == $i) {
            echo "<option value=\"$i\" selected=\"selected\">$i</option>";
        }
        else {
            echo "<option value=\"$i\">$i</option>";
        }
    }
}

function echoYearsOptions ($Year) {
    for($i=(int)(date("Y"))-15; $i >= (int)(date("Y"))-90; $i--) {
        if ($i == $Year) {
            echo "<option value=\"$i\" selected=\"selected\">$i</option>";
        }
        else {
            echo "<option value=\"$i\">$i</option>";
        }
    }
}

function add_p_Tag($InputStr) { // Функция замены переноса строк на теги <p>
    return ('<p>'.str_replace("\n", '<p>', $InputStr));
}

function db_connect () { // Подключеем базу данных
    $conn = mysql_connect("database", "misio8", "oinYax5d");
    if (!$conn) {
        echoRedirectHtml ('/mysqlerror.php', 'Ошибка');
        exit();
    }
    if (!mysql_select_db("misio8")) {
        echoRedirectHtml ('/mysqlerror.php', 'Ошибка: ' . mysql_error());
        exit();
    }
    return ($conn);
}

function echoRedirectHtml ($link, $text) {
?>
  <HTML>
    <HEAD>
      <META HTTP-EQUIV="REFRESH" CONTENT="1; URL="<?
      if (isset($link)) 
        echo $link;
      else 
        echo '/'; ?>">
    </HEAD>
    <body>
      <? echo $text; ?>
    </body>
  </HTML>
<? }

/***********************************************************************************
Функция img_resize(): генерация thumbnails
Параметры:
  $src             - имя исходного файла
  $dest            - имя генерируемого файла
  $width, $height  - ширина и высота генерируемого изображения, в пикселях
Необязательные параметры:
  $rgb             - цвет фона, по умолчанию - белый
  $quality         - качество генерируемого JPEG, по умолчанию - максимальное (100)
***********************************************************************************/
function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100)
{
  if (!file_exists($src)) {
    //echo 'Файл не существует';
    return false;
  }

  $size = getimagesize($src);

  if ($size === false) {
    //echo 'Не могу получить размер';
    return false;
  }

  // Определяем исходный формат по MIME-информации, предоставленной
  // функцией getimagesize, и выбираем соответствующую формату
  // imagecreatefrom-функцию.
  $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
  $icfunc = "imagecreatefrom" . $format;
  if (!function_exists($icfunc)) {
    //echo 'Не умею создавать картинки';
    return false;
  }

  $x_ratio = $width / $size[0];
  $y_ratio = $height / $size[1];

  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);

  $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
  $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
  $new_left    = 0; // $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
  $new_top     = 0; // !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

  $isrc = $icfunc($src);
  $idest = imagecreatetruecolor($new_width, $new_height);

  imagefill($idest, 0, 0, $rgb);
  imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, 
    $new_width, $new_height, $size[0], $size[1]);

  imagejpeg($idest, $dest, $quality);

  imagedestroy($isrc);
  imagedestroy($idest);

  return true;

}
?>