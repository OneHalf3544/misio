<script type="text/javascript">
$(document).ready(function(){
    $('#miniChatSubmit').click(function (){
        if ($('#postTextarea').val() == "")
            return false;
        $.post(AJAXBackendURL, {act:"miniChatAddPost", postTextarea:$('#postTextarea').val()}, function (data){
            //Добавляем комментарий методами JavaScript, чтобы не обновлять страницу.
            $('#mchatFrame').prepend($(
            '<div class="mchatpost">'+
                '<div class="title2"><? echo $_COOKIE['login'] ?> </div>'+
                '<div class="content">'+$('#postTextarea').val()+'</div>'+
                '</div>'));
            $('#postTextarea').val("");
        });
    return false;
    });
});
</script>

<!-- Site chat -->
<div class="vBlock">
  <div class="title">Мини-чат</div>
  <div class="content">
    <div id="mchatFrame">
      <?
      $Res = mysql_query('SELECT * FROM `MiniChat` ORDER BY `id` DESC LIMIT 30');
      for($S = mysql_fetch_assoc($Res); $S != false; $S = mysql_fetch_assoc($Res)) : 
        $dtst = $S['DateOfAdding'];
        $year = substr($dtst, 0, 4);
        $mounth = substr($dtst, 4, 2);
        $day = substr($dtst, 6, 2);
        $hour = substr($dtst, 8, 2);
        $minute = substr($dtst, 10, 2);
        $profile = '<a href="/Users/profile.php?LogName='.$S['NickName'].'">'.$S['NickName'].'</a>'; ?>
        
        <div class="mchatpost">
          <div class="title2"><? echo $profile ?>  <? echo "$day/$mounth/$year $hour:$minute" ?> </div>
          <div class="content"><? echo $S['Content']?></div>
        </div>
      <? endfor; ?>
    </div>
    <? if (isset($_COOKIE['login'])) : ?>
    <form action="/minichat.php">
      <textarea id="postTextarea"></textarea>
      <input id="miniChatSubmit" type="button" value="Отправить">
    </form>
    <? else: ?>
      <div class="textForGuest">Чтобы добавлять сообщения, вам необходимо войти на сайт под своим логином.</div>
    <? endif ?>
  </div>
</div>
<!-- /Site chat -->