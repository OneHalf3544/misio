<? $Title = "������� ������������"; ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 
<!-- Body --> 
<h1><? echo $Title; ?></h1>

<script type="text/javascript">
  $(document).ready(function (){
    $('.loadPrepList').click(function(){
      var DeptId = $(this).attr('data-deptId');
      $(this).attr('data-deptId', 'current');
      $.get('/test2.php', {act:'loadDeptPrepList', DeptId:DeptId}, function(data){
        data = removeAdvertisement (data);
        $('a[data-deptId=current]').after($('<ul>'+data+'</ul>'));
        $('a[data-deptId=current]').remove();
      });
      return false;
    });
  });
</script>
<?
$ResDeptsList = mysql_query(
  'SELECT `Depts`.`id`, `Depts`.`DeptName`, `Depts`.`Description`, `Prepods`.`PrepodName` FROM `Depts` 
  LEFT JOIN `Prepods` ON `Depts`.`ZavKaf` = `Prepods`.`id`
  ORDER BY `DeptName`'
);

for(;$DeptArray = mysql_fetch_assoc($ResDeptsList);): ?>
  <div class="depts">
    <div class="title">������� "<? echo $DeptArray['DeptName'] ?>"</div>
    <div class="content">
      <h3>���������� ��������:</h3>
      <? echo $DeptArray['PrepodName'] ?><br />
      <h3>������������� �������:</h3>
      <a href="/" class="loadPrepList" data-deptId="<? echo $DeptArray['id'] ?>">��������� ������</a><br />
      <h3>��������: </h3><br />
      <? echo add_p_Tag($DeptArray['Description']); ?><br />
    </div>
  </div>
<? endfor; ?>

<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php")?>