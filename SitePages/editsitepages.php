<? $Title = "�������� ������� �����";
include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?>
<!-- Body -->
<script type="text/javascript">
    $(document).ready(function() {
        $('input[name=DeletePage]').click(function(){
            if(confirm('�� �������, ��� ������ ������� ��������?'))
                $.get('/test2.php', {id:"<? echo $_REQUEST['id']; ?>",
                    act:"DeletePage"}, function(data){
                        alert(removeAdvertisement(data));
                        window.location.href = "<? echo $_SERVER['PHP_SELF']; ?>";
                });
        });
    });
</script>
<? if(isset($_REQUEST['SavingPage'])):
    if($_REQUEST['id'] == 0) {// �������� ���������
        mysql_query(
            'INSERT INTO `Pages` (`Title`, `Content`, `Description`)
            VALUES ("'.$_REQUEST['PageTitle'].'",
            "'.$_REQUEST['PageContent'].'", "'.$_REQUEST['PageDescription'].'")'
        );
        $_REQUEST['id'] = mysql_insert_id();
    }
    else // �������������� ��������
        mysql_query(
            'UPDATE `Pages` SET `Title`="'.$_REQUEST['PageTitle'].'",
            `Content` = "'.$_REQUEST['PageContent'].'",
            `Description` = "'.$_REQUEST['PageDescription'].'"
            WHERE `id` = '.intval($_REQUEST['id'])
        );
endif; ?>

<h1><? echo $Title; ?></h1>
<? if (isset($_REQUEST['id'])):
    if($_REQUEST['id']) {
        $Res = mysql_query('SELECT * FROM `Pages` WHERE `id` = '.$_REQUEST['id']);
        $S = mysql_fetch_assoc($Res);
    } ?>
    <div class="textOfPage">
        <p>���� ���� � ��������� ������������� ��������� � ����� ����, �����
        ���������� ����� ������������� ����� �� ������� ���������, ���
        ��������� ������� ���������� ������ � ������ ��������.</p>
    </div>
    <a href="/Pages.php?id=<? echo $_REQUEST['id']; ?>">����������� ��������</a>
    <form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
        <? if($_REQUEST['id'] != 0): ?>
            <input type="button" name="DeletePage" value="������� ��������">
        <? endif; ?>
        <input type="hidden" name="SavingPage" value="yes" />
        <input type="hidden" name="id" value="<? echo $_REQUEST['id']; ?>" />
        <h2>���������</h2>
        <input class="longinput" value="<? echo $S['Title']; ?>" type="text" name="PageTitle">
        <h2>��������</h2>
        <textarea name="PageDescription"><? echo $S['Description'] ?></textarea>
        <h2>����������</h2>
        <textarea name="PageContent" class="editPage"><? echo str_replace('&quot', "\"", $S['Content']); ?></textarea>
        <input type="submit" value="���������"/>
    </form>
  <? else: ?>
      <div class="textOfPage">
          ����� �� ������ ��������������� �������� �����. ���� 
          <a href="/SitePages/editsitepages.php?id=0">������� �����</a>
          ���������, ���� ��������������� ��� ������������, ������ �������
          ������������ ����:
      </div>
    <ul>
    <? $Res = mysql_query('SELECT `id`, `Title` FROM `Pages`');
    while(($S = mysql_fetch_assoc($Res)) != false) {
      echo '<li><a href="/SitePages/editsitepages.php?id='.$S['id'].'">'.$S['Title'].'</li>';
    } ?>
    </ul>
  <? endif; ?>
    
<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php") ?>