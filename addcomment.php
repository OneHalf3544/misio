<? // Переменные TypeOfMat и NMaterial присылаются в запросе
if(isset($_REQUEST['CommText']) || isset($_REQUEST['CommTextNoAJAX'])) {
    if(isset($_REQUEST['CommText'])) {
        require_once 'config.php';
        $conn = db_connect();
        $CommText = utf8_win($_REQUEST['CommText']);
    }
    else
        $CommText = $_REQUEST['CommTextNoAJAX'];

    $QueryStr = "INSERT INTO `Comments` (`Text`, `DateOfAdding`, `Author`, `TypeOfMat`, `NMaterial`, `IP`) ";
    $QueryStr .= 'VALUES ("'.htmlspecialchars($CommText).'", "'.time().'", "'.$_COOKIE['login'].'", "';
    $QueryStr .= $_REQUEST['TypeOfMat'].'", "'.$_REQUEST['NMaterial'].'", "'.$_SERVER['REMOTE_ADDR'].'")';
    
    mysql_query($QueryStr);
    // echo $QueryStr;

    //Обновляем количество комментариев
    $Res = mysql_query(
      'SELECT COUNT(*) FROM `Comments` WHERE `TypeOfMat` = "'.$_REQUEST['TypeOfMat'].'" 
      AND `NMaterial` = "'.$_REQUEST['NMaterial'].'"'
    );

    switch ($_REQUEST['TypeOfMat']) {
        case 'SitePages':
            mysql_query(
              'UPDATE `Pages`
              SET `CommCount` = '.mysql_result($Res, 0).'
              WHERE `id` = '.intval($_REQUEST['NMaterial'])
            );
            break;

        default:
            mysql_query(
              'UPDATE `'.$_REQUEST['TypeOfMat'].'`
              SET `CommCount` = '.mysql_result($Res, 0).'
              WHERE `id` = '.intval($_REQUEST['NMaterial'])
            );
            break;
    }
    if(isset($_REQUEST['CommText'])) {
        // Если запрос был подан AJAX'ом, закрываем базу данных
        mysql_close($conn);
        exit();
    }
    else {
        // Иначе рисуем комментарий ?>
        <div class="comments">
          <div class="title">
            <? echo profileURL($_COOKIE['login']); ?>
            <span class="time"><? echo date("d-m-Y h:i") ?></span>
          </div>
          <div class="content">
            <? echo $_REQUEST['CommTextNoAJAX']; ?>
          </div>
        </div>
    <? }

  }
?>
<script type="text/javascript"> <? // Переменные TypeOfMat и NMaterial определяются в модуле OneOfMaterial ?>
function addComm(){
  $.post('/addcomment.php', {CommText:$('#CommText').val(), TypeOfMat:"<? echo $TypeOfMat ?>", NMaterial: "<? echo $NMaterial ?>"}, function (data){
    //Добавляем комментарий методами JavaScript, чтобы не обновлять страницу.
    $('#commBlock').before($('<div class="comments"></div>'));
    $('.comments:last').append($('<div class="title"><? echo $_COOKIE['login'] ?></div>'));
    $('.comments:last').append($('<div class="content">'+$('#CommText').val()+'</div>'));
    $('#CommText').val("");
  });
}
</script>

<? if(isset($_COOKIE['login'])): // Зарегистрирован ли юзер? ?>
<form id="commBlock" action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="TypeOfMat" value="<? echo $TypeOfMat ?>" />
    <input type="hidden" name="NMaterial" value="<? echo $NMaterial ?>" />
  <div>Добавить комментарий</div>
    <textarea id="CommText" name="CommTextNoAJAX"></textarea>
    <input type="submit" value="Добавить" onclick="addComm();return false;">
  </form>
<? else: ?>
  <div class="textForGuest">Добавлять комментарии могут только зарегистрированные пользователи</div>
<? endif; ?>