<?php
  session_start(); // Запускаем сессию PHP

require_once ($_SERVER["DOCUMENT_ROOT"].'/config.php');
$conn = db_connect ();

if (!isset($_COOKIE["login"]) && !isset($_COOKIE["guest"])) {
  $Res = mysql_query("SELECT count(*) FROM `WhoOnLine` WHERE `NickName` like \"guest%\"");
  setcookie("guest", mysql_result($Res, 0));
}
?>
<!DOCTYPE HTML PUBLIC  "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML><head>
<title><? echo $Title.CONSTANT_TITLE_PART ?></title>
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="google-site-verification" content="gI01DeZ3wkemD49TmTvg09n2jEVtA_q0g-RJEqk_1gc" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta http-equiv="Content-Language" content="ru">
<meta name="description" http-equiv="description" content="">
<meta name="keywords" http-equiv="keywords" content="">
<link type="text/css" rel="StyleSheet" href="/css/my.css" />
<script type="text/javascript" src="/jscript/jquery.js"></script>
<script type="text/javascript" src="/jscript/config.js"></script>

<!-- Put this script tag to the <head> of your page -->
<script type="text/javascript" src="http://vkontakte.ru/js/api/openapi.js?9" charset="windows-1251"></script>

</head>

<body>
<script type="text/javascript">
  function logout() {
    $.get("/logout.php", function(){ 
      window.location.reload();
    });
    return false;
  }
  
  jQuery(document).ready(function(){
    jQuery(".vBlock .title").click(function(){
      $(this).next(".content").slideToggle("slow");
    }); 
  });
</script>

<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/HorizMenu.php") ?> 

<table class="mainTable">
<tbody>
<tr>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/leftpart.php") ?> 

<td class="mframe">