<? $Title = "Расписание"; ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 
<!-- Body --> 
<h1><? echo $Title ?></h1>
<script type="text/javascript">
    $(document).ready(function(){
        //var WeekDays = new Array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");
        var LectTime = new Array (
            new Array('8:30', '10:10', '11:50', '13:30', '15:10', '16:40', '18:30', '20:10'),
            new Array('8:30', '10:10', '11:50', '14:00', '15:40', '17:20', '19:00', '20:40')
        );

        $("#group").change(function(){
            window.location.href = '/schedule/raspisanie.php?Group='+$('#group').val();
//            $.get("/schedule/schedinner.php", {GroupId:$('#group').val()}, function(data){
//                data = removeAdvertisement(data); //Отрезаем рекламу
//                $('.subjAll').empty();
//                $('.subjAll').append(data);
//            });
        });

        $(".schedule .title").click(function(){
            $(this).next(".content").slideToggle("slow");
        });
    });
</script>
<form action="/schedule/raspisanie.php" method="get">
<table class="optionsArrayTable">
<colgroup>
  <col width="150px">
  <col width="*">
</colgroup>
<tbody><tr>
  <td>Группа: </td>
    <td>
        <? if(isset($_GET['Group']))
            echoGroupSelect($_GET['Group']);
        else
            echoGroupSelect(); ?>
        <noscript><input type="submit" value="Перейти" /></noscript>
    </td></tr>
<tbody></table>
</form>

<table class="tableSpec">
<tr>
  <th>Расписание <a class="editlink" href="/schedule/schededit.php">[Редактировать]</a></th>
</tr>
<tr>
    <td class="subjAll">
        <?
        if (!isset($_GET['Group']) && isset($_COOKIE['GroupId'])) {
            $UseDefaultGroupId = true;
        }
        if (isset($_COOKIE['GroupId']) || isset($_GET['Group']))
            include ($_SERVER["DOCUMENT_ROOT"]."/schedule/schedinner.php");
        ?>
    </td>
</tr></table>

<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php")?>