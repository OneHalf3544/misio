<?php

if (!isset($_REQUEST['act']))
    exit('Не указан тип операции');

require_once 'config.php';
$conn = db_connect();

switch ($_REQUEST['act']) {
    case 'getFacultSpeciality':
        /*
         * Получить специальности факультета
         */
        $IdArray   = array(0);
        $SpecArray = array(win_utf8(' - Специальность - '));

        $Res = mysql_query(
            'SELECT `id`, `Spec` FROM `Speciality` '.
            'WHERE `FacultId` = "'.$_REQUEST['Fac'].'" ORDER BY `Spec` ASC'
        );

        while(($S = mysql_fetch_assoc($Res)) != false) {
            $IdArray[]   = $S['id'];
            $SpecArray[] = win_utf8($S['Spec']);
        }
        echo '{"id"        : ["'.implode('", "', $IdArray).  '"], '.
             '"speciality" : ["'.implode('", "', $SpecArray).'"] }';
    break;

    case 'findSubject' :
        /*
         * Поиск предмета по запросу
         */
        $Res = mysql_query(
            'SELECT `id`, `Title`, `Description` FROM `Subjects` '.
            'WHERE `Title` LIKE "%'.utf8_win($_REQUEST['findStr']).'%" '.
            'OR  `Description` LIKE "%'.utf8_win($_REQUEST['findStr']).'%" '.
            'ORDER BY `Title` LIMIT 30'
        );
        $id    = array();
        $subj  = array();
        $desc  = array();
        while(($S = mysql_fetch_row($Res)) != false) {
            $id[]    = $S[0];
            $subj[]  = win_utf8($S[1]);
            $desc[]  = win_utf8(add_p_Tag($S[2]));
        }
        echo '{"id"    : ["'.implode('", "', $id  ).'"], '.
            '"subject" : ["'.implode('", "', $subj).'"], '.
            '"descr"   : ["'.implode('", "', $desc).'"]}';
    break;

    case 'DeletePage' :
        mysql_query('DELETE FROM `Pages` WHERE `id` = '.intval($_REQUEST['id']));
        echo win_utf8("Страница удалена");
    break;

    case 'getSubjectList' :     // Если нужно получить список предметов
        if($_REQUEST['Spec'] == "0")
            echo '{"id":["0"], "subject":[" - Предмет - "], "descr":["Описание"]}';
        else {
            $QueryStr = 'SELECT `Subjects`.`id`, `Subjects`.`Title`, `Subjects`.`Description` FROM `Subjects` '.
                'JOIN `SpecNSubj` ON `SpecNSubj`.`SubjectId` = `Subjects`.`id` '.
                'WHERE (`SpecNSubj`.`SpecId` = "'.utf8_win($_REQUEST['Spec']).'") ';
            if($_REQUEST['Sem'] != "0") {
                $QueryStr .= 'AND (`SpecNSubj`.`Semestr` = '.utf8_win($_REQUEST['Sem']).') ';
            }
            $QueryStr .= 'ORDER BY `Subjects`.`Title` ASC';
            //echo $QueryStr;
            $Res = mysql_query($QueryStr);
            $id    = array();
            $subj  = array();
            $desc  = array();
            while(($SubjArray = mysql_fetch_row($Res)) != false) {
                $id[] = $SubjArray[0];
                $subj[] = win_utf8($SubjArray[1]);
                $desc[] = win_utf8(add_p_Tag($SubjArray[2]));
            }
            echo '{ "id" : ["'.implode('", "', $id).  '"], '.
             ' "subject" : ["'.implode('", "', $subj).'"], '.
             ' "descr"   : ["'.implode('", "', $desc).'"] }';
        }
    break;

    case 'removing' :  // Если удаляется соответствие специальностей и семестров
        mysql_query(
            'DELETE FROM `SpecNSubj` '.
            'WHERE `SubjectId` = '.str_replace('subjId', '', $_REQUEST['id']).
            ' AND `Semestr`= "'.$_REQUEST['Sem'].'" AND `SpecId`= "'.$_REQUEST['Spec'].'"'
        );
    break;

    case 'adding' :
        mysql_query(
            'INSERT INTO `SpecNSubj` (`SubjectId`, `Semestr`, `SpecId`) '.
            'VALUES ('.str_replace('subjId', '', $_REQUEST['id']).', '.
            $_REQUEST['Sem'].', '.$_REQUEST['Spec'].')'
        );
    break;

    case 'createSubject' :
        $Title = htmlspecialchars(utf8_win($_REQUEST['title']));
        $Descr = htmlspecialchars(utf8_win($_REQUEST['descr']));
        mysql_query("INSERT INTO `Subjects` (`Title`, `Description`) VALUES (\"$Title\", \"$Descr\")");
        echo "[".mysql_insert_id()."]";
    break;

    case 'scheduleSave' :
        mysql_query('DELETE FROM `Schedule` WHERE `GroupId` = "'.$_REQUEST['Group'].'"');

        $SubjectId  = explode('|', $_REQUEST['SubjectId']);
        $NLesson    = explode('|', $_REQUEST['NLesson']);
        $Week       = explode('|', $_REQUEST['Week']);
        $WeekDay    = explode('|', $_REQUEST['WeekDay']);
        $Antract    = explode('|', $_REQUEST['Antract']);
        $PrepodId   = explode('|', $_REQUEST['PrepodId']);
        $Type       = explode('|', utf8_win($_REQUEST['Type']));
        $Location   = explode('|', utf8_win($_REQUEST['Location']));

        for($i = 1; $i < sizeof($SubjectId); $i++) {
            $QueryStr = 'INSERT INTO `Schedule` ('.
                '`GroupId`, `SubjectId`, `NLesson`, `Week`, `WeekDay`, `Antract`, `PrepodId`, `Type`, `Location`) '.
                'VALUES ("'.$_REQUEST['Group'].'", "'.$SubjectId[$i].'", "'.$NLesson[$i].'", "'.
                $Week[$i].'", "'.$WeekDay[$i].'", "'.$Antract[$i].'", "'.
                $PrepodId[$i].'", "'.$Type[$i].'", "'.htmlspecialchars($Location[$i]).'")';
            echo $QueryStr;
            mysql_query($QueryStr);
        }
    break;

    case 'scheduleGet' : // Передаем клиенту информацию о расписании
        $Res = mysql_query(
            'SELECT * FROM `Schedule`
              WHERE `GroupId`="'.$_REQUEST['Group'].'" ORDER BY WeekDay, NLesson, Week'
        );

        $JSONStr = "[";
        while(($S = mysql_fetch_assoc($Res)) != false):
            $JSONStr .= '{"SubjectId": "'.$S['SubjectId'].'", ';
            $JSONStr .= '"Type": "'      .$S['Type'].'", ';
            $JSONStr .= '"PrepodId": "'  .$S['PrepodId'].'", ';
            $JSONStr .= '"Location": "'  .$S['Location'].'", ';
            $JSONStr .= '"WeekDay": "'   .$S['WeekDay'].'", ';
            $JSONStr .= '"NLesson": "'   .$S['NLesson'].'", ';
            $JSONStr .= '"Week": "'      .$S['Week'].'", ';
            $JSONStr .= '"Antract": "'   .$S['Antract'] .'"}, ';
        endwhile;
        $JSONStr .= "]";
        $JSONStr = str_replace(', ]', ']', $JSONStr);
        echo $JSONStr;
    break;

    case 'groupGet' : // Получаем список групп
        $Res = mysql_query('SELECT * FROM `Groups` WHERE `SpecId` = '.$_REQUEST['SpecId']);
        $Groups   = array();

        while(($S = mysql_fetch_assoc($Res)) != false) {
            $Groups[]   = '{"id": "'.$S['id'].'", "Title": "'.$S['Title'].'"}';
        }
        echo '['.implode(', ', $Groups).']';
    break;

    case 'createGroup': // Создать группу
        mysql_query(
            'INSERT INTO `Groups` (`Title`, `SpecId`) VALUES ("'.
            utf8_win($_REQUEST['GroupName']).'", "'.$_REQUEST['SpecId'].'")'
        );
        echo mysql_insert_id();
    break;

    case 'miniChatAddPost': // Добавить сообщение в миничат
        mysql_query(
            'INSERT INTO `MiniChat` (`NickName`, `Content`) VALUES ("'.$_COOKIE['login'].'", "'.
            htmlspecialchars(utf8_win($_REQUEST['postTextarea'])).'")'
        );
    break;

    case 'loadDeptPrepList' : // Пишем список преподов кафедры
        $Res = mysql_query(
            'SELECT `Prepods`.`id`, `Prepods`.`PrepodName` FROM `Prepods` WHERE `DeptId` = '.$_REQUEST['DeptId']
        );
        while(($S = mysql_fetch_assoc($Res)) != false) {
            echo '<li><a href="/oneofmaterial.php?TypeOfMat=Prepods&NMaterial='.$S['id'].'">'.$S['PrepodName'].'</a>';
        }
    break;

    case 'pollSave': // Сохранить выбор голосования
        $PollArray = mysql_fetch_assoc(mysql_query('SELECT * FROM `Polls` WHERE `id` = '.intval($_REQUEST['PollId'])));
        $R = explode('|', $PollArray['Result']);
        $R[$_REQUEST['pollsSelect']]++;
        mysql_query('UPDATE `Polls` SET `Result` = "'.implode('|', $R).'" WHERE `id` = '.intval($_REQUEST['PollId']));
        $arr = explode('Q', $_COOKIE['polls']);
        $arr[] = $_REQUEST['PollId'];
        setcookie("polls", implode('Q', $arr), 9999999999999999);
        include $_SERVER["DOCUMENT_ROOT"]."/poll.php";
    break;

    default:
        echo 'Неправильно указан тип операции';
    break;
}

mysql_close($conn);
?>