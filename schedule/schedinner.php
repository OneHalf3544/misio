<?
if(@$_REQUEST['Printable'] == 'yes')
    echo '<link type="text/css" rel="StyleSheet" href="/css/schedule.css" />';
require_once ($_SERVER["DOCUMENT_ROOT"]."/config.php");
if(isset($UseDefaultGroupId)) {
    $GroupId = intval($_COOKIE['GroupId']);
}
else {
    $GroupId = intval($_GET['Group']);
}
if(!isset($conn))
    $conn2 = db_connect ();

$SubjType = array('none' => '', 'Lection' => 'Лекция', 'Lab' => 'Лабораторная', 'Pract' => 'Практика');
$LectTime = array();
$LectTime[0] = array('8:30', '10:10', '11:50', '13:30', '15:10', '16:40', '18:20', '20:00');
$LectTime[1] = array('8:30', '10:10', '11:50', '14:00', '15:40', '17:20', '19:00', '20:40');

if(isset($_REQUEST['PrepodId'])):
    $Res = mysql_query( // Расписание преподавателя
        'SELECT `Schedule`.*, `Subjects`.`Title` AS SubjTitle, `Prepods`.`PrepodName`
        FROM `Schedule` LEFT JOIN `Subjects` ON `Subjects`.`id` = `Schedule`.`SubjectId`
        LEFT JOIN `Prepods` ON `Schedule`.`PrepodId` = `Prepods`.`id`
        WHERE `PrepodId`="'.intval($_REQUEST['PrepodId']).'" ORDER BY WeekDay, NLesson, Week'
    );
else :
    $Res = mysql_query( // Расписание группы
        'SELECT `Schedule`.*, `Subjects`.`Title` AS SubjTitle, `Prepods`.`PrepodName`
        FROM `Schedule` LEFT JOIN `Subjects` ON `Subjects`.`id` = `Schedule`.`SubjectId`
        LEFT JOIN `Prepods` ON `Schedule`.`PrepodId` = `Prepods`.`id`
        WHERE `GroupId`="'.$GroupId.'" ORDER BY WeekDay, NLesson, Week'
    );
endif;
if (!isset($_REQUEST['Printable'])) // Добавляем ссылку на версию для печати
    if (isset($_REQUEST['PrepodId']))
        echo '<a href="/schedule/schedinner.php?PrepodId='.$_REQUEST['PrepodId'].'&Printable=yes">Версия для печати</a><br />';
    else
        echo '<a href="/schedule/schedinner.php?GroupId='.$GroupId.'&Printable=yes">Версия для печати</a><br />';

if (mysql_num_rows($Res) == 0)
    echo "Нет данных";
while(($S = mysql_fetch_assoc($Res)) != false):
    $SubjStr = $S['SubjTitle'].'. '.$SubjType[$S['Type']].'<br>'.$S['PrepodName'];
    $Location = $S['Location'];
    $SubjArray[$S['WeekDay']-1][$S['NLesson']][$S['Week']]
        = array("SubjStr" => $SubjStr, "Location" => $Location, "Antract" => $S['Antract']);
endwhile;

for($i = 0; $i <= 5; $i++):
    if(isset($SubjArray[$i])): ?>
<div class="schedule">
    <div class="title"><? echo $WeekDays[$i] ?></div>
    <div class="content">
        <table class="scheduleWeekDay">
            <colgroup>
                <col width="50px">
                <col width="*">
                <col width="50px">
            </colgroup>
            <tbody>
            <tr>
                <th>Время</th>
                <th>Предмет</th>
                <th>Ауд.</th>
            </tr>
            <? for($j = 1; $j <= 8; $j++):
                if (isset($SubjArray[$i][$j])) :
                    if(isset($SubjArray[$i][$j][0])):
                        $tmp = $SubjArray[$i][$j][0]; ?>
                        <tr>
                            <td><? echo $LectTime[$tmp['Antract']][$j-1] ?></td>
                            <td><? echo $tmp["SubjStr"] ?></td>
                            <td><? echo $tmp['Location'] ?></td>
                        </tr>
                    <? else :
                        $tmp1 = $SubjArray[$i][$j][1];
                        $tmp2 = $SubjArray[$i][$j][2];
                        $t = $LectTime[$tmp1['Antract']][$j-1];
                        if (!isset($t))
                            $t = $LectTime[$tmp2['Antract']][$j-1]; ?>
                        <tr>
                            <td rowspan="2"><? echo $t ?></td>
                            <td>
                                <? if(!isset($tmp1)) echo '<br>';
                                echo $tmp1["SubjStr"] ?>
                            </td>
                            <td>
                                <? echo $tmp1['Location'] ?>
                            </td>
                        </tr>
                        <tr>
                          <!--<td rowspan="2"></td>-->
                            <td><? if(!isset($tmp2)) echo '<br>';
                                echo $tmp2["SubjStr"] ?></td>
                            <td><? echo $tmp2['Location'] ?></td>
                        </tr>
                    <? endif;
                endif;
            endfor; ?>
            </tbody>
        </table>
    </div>
</div>  
    <?  endif;
endfor;
if(isset($conn2))
    mysql_close($conn2);
?>
