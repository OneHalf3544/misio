<?
  if(!$AJAXLoadPrepSched): // ����� ���� ��� �������� �� �������� ���� ��� ?>
    <script type="text/javascript">
      $(document).ready(function(){
        $('.prepSchedule').click(function(){
          var PrepodId = $(this).attr('data-PrepodId');
          $(this).attr('data-PrepodId', 'current');
          $.get("/schedule/schedinner.php", {PrepodId:PrepodId, Printable:"no"}, function(data){
            data = removeAdvertisement(data);
            $('a[data-PrepodId=current]').after($('<div class="PrepodScheduleInner">'+data+'</div>'));
            $('a[data-PrepodId=current]').remove();
          });
          return false;
        })
      });
    </script>
    <? $AJAXLoadPrepSched = true;
  endif;
?>

<div class="prepods">
  <h2 class="title">
    <? 
      if($_SERVER['PHP_SELF'] != '/oneofmaterial.php') { // ��������� �� ������ �� ��������� � �������������?
        echo '<a href="/oneofmaterial.php?TypeOfMat=Prepods&NMaterial='.$Prepods['id'].'">'.
          $Prepods['PrepodName'].'</a>';
      } 
      else {
        echo $Prepods['PrepodName'];
      }
    ?>
    <a class="editlink" href="/prepods/prepodedit.php?id=<? echo $Prepods['id']?>">[�������������]</a>
  </h2>
  <div class="content">
    <? if(isset($Prepods['ImgName']) && $Prepods['ImgName'] != "") {
      echo '<img src="/img/prepods/'.$Prepods['ImgName'].'" alt="'.$Prepods['PrepodName'].'">';
    } ?>
    <h3>���� ��������:</h3> <? echo $Prepods['BirthDay'] ?><br />
    <h3>�������:</h3>
    <? 
      $Res2 = mysql_query('SELECT `DeptName` FROM `Depts` WHERE `id` = '.$Prepods['DeptId']);
      if ($Res2 != false) {
        $S = mysql_fetch_assoc($Res2);
        echo $S['DeptName']; 
      }
      else {
        echo "��� ������";
      }
    ?><br />
    <h3>�������:</h3>
    <?
      if (isset($Prepods['Telephone']) && $Prepods['Telephone'] != '')
        echo $Prepods['Telephone'];
      else
        echo "��� ������";
    ?>
    <br />
    <h3>E-Mail:</h3>
    <?
      if (isset($Prepods['EMail']) && $Prepods['EMail'] != '')
        echo $Prepods['EMail'];
      else
        echo "��� ������";
    ?>
    <br />
    <h3>������ ������:</h3><? echo $PrepodStatus[$Prepods['Status']] ?><br />
    <h3>����� ��������:</h3>
    <ul>
    <?
      $Res2 = mysql_query(
        ' SELECT DISTINCT `Subjects`.`Title`
          FROM `Subjects`
          INNER JOIN `Schedule` ON `Subjects`.`id` = `Schedule`.`SubjectId`
          INNER JOIN `Prepods` ON `Prepods`.`id` = `Schedule`.`PrepodId`
          WHERE `PrepodId` = '.intval($Prepods['id']).' ORDER BY `Title`'
      ); 
      $S = mysql_fetch_row($Res2);
      if ($S == false) 
        echo "<li>������ �����������";
      for(; $S != false; $S = mysql_fetch_row($Res2)) {
        echo "<li>$S[0]";
      }
    ?>
    </ul>
    <h3>���������� �������������:</h3> 
    <a class="prepSchedule" data-PrepodId="<? echo $Prepods['id'] ?>" 
       href="/schedule/schedinner.php?PrepodId=<? echo $Prepods['id'] ?>">
      ���������� ���������� �������������
    </a><br />
    <h3>��������:</h3> <? echo add_p_Tag($Prepods['Description']); ?>
    <div class="Details">����������(<? echo $Prepods['Views'] ?>), ������������:(<? echo $Prepods['CommCount'] ?>)</div>
  </div>
</div>