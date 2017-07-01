<html>
<head>
    <title>Ссылки на расписания групп</title>
</head>
<body>
<?
    require_once $_SERVER["DOCUMENT_ROOT"].'/config.php';
    $conn = db_connect();
    $Res = mysql_query('SELECT id, Title FROM Groups');
    while(($S = mysql_fetch_assoc($Res)) != false): ?>
        <a href="/schedule/raspisanie.php?Group=<? echo $S['id'] ?>">
            Группа <? echo $S['Title'] ?>
        </a><br>
    <? endwhile;
    mysql_close($conn); ?>
</body>
</html>