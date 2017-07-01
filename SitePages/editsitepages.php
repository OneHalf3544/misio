<? $Title = "–едактор страниц сайта";
include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?>
<!-- Body -->
<script type="text/javascript">
    $(document).ready(function() {
        $('input[name=DeletePage]').click(function(){
            if(confirm('¬ы уверены, что хотите удалить страницу?'))
                $.get('/test2.php', {id:"<? echo $_REQUEST['id']; ?>",
                    act:"DeletePage"}, function(data){
                        alert(removeAdvertisement(data));
                        window.location.href = "<? echo $_SERVER['PHP_SELF']; ?>";
                });
        });
    });
</script>
<? if(isset($_REQUEST['SavingPage'])):
    if($_REQUEST['id'] == 0) {// —оздание странички
        mysql_query(
            'INSERT INTO `Pages` (`Title`, `Content`, `Description`)
            VALUES ("'.$_REQUEST['PageTitle'].'",
            "'.$_REQUEST['PageContent'].'", "'.$_REQUEST['PageDescription'].'")'
        );
        $_REQUEST['id'] = mysql_insert_id();
    }
    else // –едактирование страницы
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
        <p>’оть сайт и позвол€ет редактировать странички в пол€х ниже, более
        правильным будет редактировать текст во внешнем редакторе, что
        обеспечит меньшую веро€тнось ошибок в тексте разметки.</p>
    </div>
    <a href="/Pages.php?id=<? echo $_REQUEST['id']; ?>">ѕросмотреть страницу</a>
    <form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
        <? if($_REQUEST['id'] != 0): ?>
            <input type="button" name="DeletePage" value="”далить страницу">
        <? endif; ?>
        <input type="hidden" name="SavingPage" value="yes" />
        <input type="hidden" name="id" value="<? echo $_REQUEST['id']; ?>" />
        <h2>«аголовок</h2>
        <input class="longinput" value="<? echo $S['Title']; ?>" type="text" name="PageTitle">
        <h2>ќписание</h2>
        <textarea name="PageDescription"><? echo $S['Description'] ?></textarea>
        <h2>—одержимое</h2>
        <textarea name="PageContent" class="editPage"><? echo str_replace('&quot', "\"", $S['Content']); ?></textarea>
        <input type="submit" value="—охранить"/>
    </form>
  <? else: ?>
      <div class="textOfPage">
          «десь вы можете отредактировать страницы сайта. Ћибо 
          <a href="/SitePages/editsitepages.php?id=0">создать новую</a>
          страничку, либо отредактировать уже существующую, список которых
          предоставлен ниже:
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