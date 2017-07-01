<?php
// session_start();
// Работа с cookie должна быть произведена до заголовка
require_once 'config.php';
$conn = db_connect();
// Берем хеш пароля из базы
$LogName = $_POST['LogName'];
$SqlStr = "SELECT `Passwd` FROM `Users` WHERE `NickName` = \"$LogName\"";
$Res = mysql_query($SqlStr);
if(mysql_num_rows($Res) == 0) {
    $OutputStr = "В базе нет такого пользователя";
}
else {
    $md5hash = mysql_result($Res, 0); 
    $password = $_POST['LogPass'];
    preg_match('/(\$1\$[^\$]+)/',$md5hash,$salt);
    $salt = $salt[0];
    if ($md5hash == crypt($password,$salt)) {
        //Если пароль верный:
        if(@$_REQUEST['AJAXused'] == true)
            $OutputStr = "Success";
        else
            $OutputStr = "Авторизация прошла успешно";
        setcookie("login", $_POST['LogName'], 1000000000*$_POST['RememberMe']);
        setcookie("guest", ""); //Удалим куку с номером гостя

        // Записываем в переменные сессии факультет и специальность юзера
        $S = mysql_fetch_assoc(mysql_query(
            'SELECT `Users`.`SpecId`, `Speciality`.`FacultId`, `Users`.`SemestrId`, `Users`.`GroupId` FROM `Users`
            LEFT JOIN `Speciality` ON `Speciality`.`id` = `Users`.`SpecId`
            WHERE `NickName` = "'.$_REQUEST['LogName'].'"'
        ));

        setcookie('FacultId',  $S['FacultId'],  1000000000*$_POST['RememberMe']);
        setcookie('SemestrId', $S['SemestrId'], 1000000000*$_POST['RememberMe']);
        setcookie('SpecId',    $S['SpecId'],    1000000000*$_POST['RememberMe']);
        setcookie('GroupId',   $S['GroupId'],   1000000000*$_POST['RememberMe']);
    }
    else
        $OutputStr = 'Пароль неверный';
}
mysql_close($conn);
// 

if(@$_REQUEST['AJAXused'] == true):
    echo win_utf8($OutputStr);
else: ?>
<HTML>
    <HEAD>
        <META HTTP-EQUIV="REFRESH" CONTENT="1; URL=http://misio.hut1.ru">
    </HEAD>
    <body>
        <p><? echo $OutputStr; ?></p>
    </body>
</HTML>
<? endif; ?>