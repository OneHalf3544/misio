<div class="vBlock" id="polls">
  <script type="text/javascript">
  $(document).ready(function(){
    $('#btnPollApply').click(function () {
      $.get("/test2.php", {act:"pollSave", PollId:$('input[name=PollId]').val(), 
        pollsSelect:$(":radio[name=pollsSelect]").filter(":checked").val()}, function(data){
          data = removeAdvertisement (data);
          $('#btnPollApply').parents('.vBlock').after($(data));
          $('#btnPollApply:first').parents('.vBlock').remove();
      });
      return false;
    });
  });
  </script>

  <div class="title">Наш опрос</div>
  <div class="content">
    <form action="/poll.php">
    <? 
      if (isset($_REQUEST['PollId'])) {
        $Res = mysql_query('SELECT * FROM `Polls` WHERE `id` = '.intval($_REQUEST['PollId']));
      }
      else {
        $Res = mysql_query('SELECT COUNT(*) FROM `Polls`');
        $Number = mysql_result($Res, 0);
        $Res = mysql_query('SELECT * FROM `Polls` LIMIT '.mt_rand(0, $Number-1).', 1');
      }
      $PollArray = mysql_fetch_assoc($Res);
      if ($PollArray == false) 
        echo 'Нет такого опроса';
      echo $PollArray['Question'], "<br>";
      $V = explode('|', $PollArray['Variants']);
      $R = explode('|', $PollArray['Result']);
      
      $alreadyVoted = (@$_REQUEST['act'] == 'pollSave') || in_array($PollArray['id'], explode('Q', @$_COOKIE['polls']));
      if ($alreadyVoted) 
        echo '<ul>';
      for($i = 0; $i < sizeof($V); $i++) {
        if($V[$i] == '') break;
        if (!$alreadyVoted)
          echo '<label><input type="radio" name="pollsSelect" value="'.$i.'">'.$V[$i].'</label><br>';
        else {
            echo '<li>'.$V[$i].
              '<img style="display:block" src="/img/pollResultBar.png" width="'. $R[$i] / array_sum($R) * 100*0.75 .'%" height="8px">'.
              '<span class="voteNumber">(Голосов: '.$R[$i].')</span>';
          }
      }
    
      if (!$alreadyVoted) { ?>
        <input name="PollId" type="hidden" value="<? echo $PollArray['id'] ?>">
        <input id="btnPollApply" type="submit" value="Ответить">
      <? } 
      else 
        echo '</ul>';
      ?>
    </form>
  </div>
</div>