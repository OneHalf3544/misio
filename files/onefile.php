<?
  $FilesArray['Message'] = str_replace("&quot", "\"", $FilesArray['Message']);
  $ProfileLink = '<a href="/Users/profile.php?LogName='.$FilesArray['NickName'].'">'.$FilesArray['NickName'].'</a>';
  
  // ����� ������� ����� html-����
?>
    <div class="matBlock">
      <h2 class="title">
        <? 
        if($_SERVER['PHP_SELF'] != '/oneofmaterial.php') { // ��������� �� ������ �� ��������� � �������������?
          echo '<a href="/oneofmaterial.php?TypeOfMat=Files&NMaterial='.$FilesArray['id'].'">'.
          $FilesArray['Title'].'</a>';
        } 
        else {
          echo $FilesArray['Title'];
        } ?>
        <a class="editlink" href="/files/addfile.php?id=<? echo $FilesArray['id']; ?>">[�������������]</a>
      </h2>
      <div class="content">
        <a class="loadLink" href="http://misio.ucoz.ru/load/0-0-0-<? echo $FilesArray['id'] ?>-20">[������� � misio.ucoz.ru]</a>
        <a class="loadLink" href="/materials/<? echo $FilesArray['URL'] ?>">[������� � �������]</a>
        
        <div class="Message"><? echo add_p_Tag($FilesArray['Message']); ?></div>
        <div class="Details">
          �������� �� ��������� 
            <? 
            $Tmp = array();
            foreach(explode(',', $FilesArray['Type']) as $a) // ��������� ������ � �����������
                $Tmp[] = '"<a href="'.filterFilesUrlLink(
                    $_REQUEST['Facult'], // ���������
                    $_REQUEST['Speciality'], // �������������
                    $_REQUEST['Semestr'], // �������
                    $_REQUEST['Subject'], // �������
                    $_REQUEST['PrepodId'],  // ������
                    $a, // ���������
                    $_REQUEST['Sorting'], // ����������
                    $_REQUEST['ShowUnmarkedFiles'] // ���������� ����� � ������������� ����������
                ).'">'.$FilesType[$a].'"</a>';
            echo implode(', ', $Tmp);
            ?>

            �� �������� <a href="<? echo filterFilesUrlLink(
                    $_REQUEST['Facult'], // ���������
                    $_REQUEST['Speciality'], // �������������
                    $_REQUEST['Semestr'], // �������
                $FilesArray['SubjectId'], // �������
                    $_REQUEST['PrepodId'],  // ������
                    $_REQUEST['MaterialType'], // ���������
                    $_REQUEST['Sorting'], // ����������
                    $_REQUEST['ShowUnmarkedFiles'] // ���������� ����� � ������������� ����������
                ) ?>" title="�������� ����� �� ������� ��������">"<? echo $FilesArray['SubjTitle']; ?>"</a>
          <br />�������������: <? 
            if ($FilesArray['PrepodId'] != 0)
              // echo '<a href="/oneofmaterial.php?TypeOfMat=Prepods&NMaterial='.$FilesArray['PrepodId'].'">';
              // echo $FilesArray['PrepodName'].'</a>';
              echo '<a href="'.filterFilesUrlLink(
                    $_REQUEST['Facult'], // ���������
                    $_REQUEST['Speciality'], // �������������
                    $_REQUEST['Semestr'], // �������
                    $_REQUEST['Subject'], // �������
                $FilesArray['PrepodId'],  // ������
                    $_REQUEST['MaterialType'], // ���������
                    $_REQUEST['Sorting'], // ����������
                    $_REQUEST['ShowUnmarkedFiles'] // ���������� ����� � ������������� ����������
                ).'">'.$FilesArray['PrepodName'].'"</a>';
          ?>
        </div>
        <div class="Details">
          ����������: <? echo $FilesArray['Views']; ?>
          | �������: <? echo $ProfileLink; ?> 
          | ����: <? echo date("d-m-Y",$FilesArray['DateOfAdding']); ?>
          | ������������: <? echo $FilesArray['CommCount']; ?>
        </div>
      </div>
    </div>
<?