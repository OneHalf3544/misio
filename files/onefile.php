<?
  $FilesArray['Message'] = str_replace("&quot", "\"", $FilesArray['Message']);
  $ProfileLink = '<a href="/Users/profile.php?LogName='.$FilesArray['NickName'].'">'.$FilesArray['NickName'].'</a>';
  
  // Далее следует кусок html-кода
?>
    <div class="matBlock">
      <h2 class="title">
        <? 
        if($_SERVER['PHP_SELF'] != '/oneofmaterial.php') { // Добавлять ли ссылку на страничку с комментариями?
          echo '<a href="/oneofmaterial.php?TypeOfMat=Files&NMaterial='.$FilesArray['id'].'">'.
          $FilesArray['Title'].'</a>';
        } 
        else {
          echo $FilesArray['Title'];
        } ?>
        <a class="editlink" href="/files/addfile.php?id=<? echo $FilesArray['id']; ?>">[Редактировать]</a>
      </h2>
      <div class="content">
        <a class="loadLink" href="http://misio.ucoz.ru/load/0-0-0-<? echo $FilesArray['id'] ?>-20">[Скачать с misio.ucoz.ru]</a>
        <a class="loadLink" href="/materials/<? echo $FilesArray['URL'] ?>">[Скачать с сервера]</a>
        
        <div class="Message"><? echo add_p_Tag($FilesArray['Message']); ?></div>
        <div class="Details">
          Материал из категории 
            <? 
            $Tmp = array();
            foreach(explode(',', $FilesArray['Type']) as $a) // наполняем массив с категориями
                $Tmp[] = '"<a href="'.filterFilesUrlLink(
                    $_REQUEST['Facult'], // Факультет
                    $_REQUEST['Speciality'], // Специальность
                    $_REQUEST['Semestr'], // Семестр
                    $_REQUEST['Subject'], // Предмет
                    $_REQUEST['PrepodId'],  // Препод
                    $a, // Категория
                    $_REQUEST['Sorting'], // Сортировка
                    $_REQUEST['ShowUnmarkedFiles'] // Показывать файлы с неотмеченными предметами
                ).'">'.$FilesType[$a].'"</a>';
            echo implode(', ', $Tmp);
            ?>

            по предмету <a href="<? echo filterFilesUrlLink(
                    $_REQUEST['Facult'], // Факультет
                    $_REQUEST['Speciality'], // Специальность
                    $_REQUEST['Semestr'], // Семестр
                $FilesArray['SubjectId'], // Предмет
                    $_REQUEST['PrepodId'],  // Препод
                    $_REQUEST['MaterialType'], // Категория
                    $_REQUEST['Sorting'], // Сортировка
                    $_REQUEST['ShowUnmarkedFiles'] // Показывать файлы с неотмеченными предметами
                ) ?>" title="Показать файлы по данному предмету">"<? echo $FilesArray['SubjTitle']; ?>"</a>
          <br />Преподаватель: <? 
            if ($FilesArray['PrepodId'] != 0)
              // echo '<a href="/oneofmaterial.php?TypeOfMat=Prepods&NMaterial='.$FilesArray['PrepodId'].'">';
              // echo $FilesArray['PrepodName'].'</a>';
              echo '<a href="'.filterFilesUrlLink(
                    $_REQUEST['Facult'], // Факультет
                    $_REQUEST['Speciality'], // Специальность
                    $_REQUEST['Semestr'], // Семестр
                    $_REQUEST['Subject'], // Предмет
                $FilesArray['PrepodId'],  // Препод
                    $_REQUEST['MaterialType'], // Категория
                    $_REQUEST['Sorting'], // Сортировка
                    $_REQUEST['ShowUnmarkedFiles'] // Показывать файлы с неотмеченными предметами
                ).'">'.$FilesArray['PrepodName'].'"</a>';
          ?>
        </div>
        <div class="Details">
          Просмотров: <? echo $FilesArray['Views']; ?>
          | Добавил: <? echo $ProfileLink; ?> 
          | Дата: <? echo date("d-m-Y",$FilesArray['DateOfAdding']); ?>
          | Комментариев: <? echo $FilesArray['CommCount']; ?>
        </div>
      </div>
    </div>
<?