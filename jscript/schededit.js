/*
 *  Сворачиваем/разворачиваем на один/два предмета (числитель/знаменатель)
 */
function split_collapse_subj (row, act) {
    // act = toggle, split, collapse
    //Надо бы заменить индикаторы состояния на другие. Вдруг я захочу картинки другие
    if ((act == 'collapse' && $(row[0].cells[1]).children('img').attr('src') == '/img/split.png')
        || (act == 'split' && $(row[0].cells[1]).children('img').attr('src') == '/img/collapse.png'))
        return;

    for(var i = 2; i <= 5; i++) {
        if ($(row[0].cells[1]).children('img').attr('src') == '/img/split.png') {
            var sel = $(row[0].cells[i]).html();
            $(row[0].cells[i]).append('<br>'+sel);
        }
        else {
            $(row[0].cells[i]).find('br').nextAll().remove();
            $(row[0].cells[i]).find('br').remove();
        }
    }
    if ($(row[0].cells[1]).children('img').attr('src') == '/img/split.png') {
        $(row[0].cells[1]).children('img').attr('src', '/img/collapse.png');
    }
    else {
        $(row[0].cells[1]).children('img').attr('src', '/img/split.png');
    }
}

$(document).ready(function(){
    //var WeekDays = new array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");

    // Загружаем расписание
    $('#group').change(function() {
        if ($('#group').val() != 0) {
            $.get("/test2.php", {act:"scheduleGet", Group:$('#group').val()}, function(data){
                data = removeAdvertisement(data); //Отрезаем рекламу
                var sch = eval("("+data+")");
                clearFields();
                for(var i = 0; i <= sch.length-1; i++) {
                    var s = sch[i];
                    var currLesson = '.scheduleWeekDay:eq('+(s.WeekDay-1)+') tr:eq('+(s.NLesson)+')';
                    if (s.Week == 0) {
                        split_collapse_subj($(currLesson), 'collapse');
                    }
                    else {
                        split_collapse_subj($(currLesson), 'split');
                        s.Week--;
                    }
                    $(currLesson+' .subjSelect option[@value="'+s.SubjectId+'"]:eq('+s.Week+')').attr("selected", "selected");
                    // "Опасные" куски кода. Сломается, если поменять число столбцов
                    $(currLesson+' td:eq(3) option[@value="'+s.Type+'"]:eq('+s.Week+')').attr("selected", "selected");
                    $(currLesson+' td:eq(4) option[@value="'+s.PrepodId+'"]:eq('+s.Week+')').attr("selected", "selected");
                    $(currLesson+' td:eq(5) input:eq('+s.Week+')').val(s.Location);
                    $('.scheduleWeekDay:eq('+(s.WeekDay-1)+') .antract '+
                        +'option[@value="'+s.Antract+'"]:eq('+s.Antract+')').attr("selected", "selected");
                }
            });
        }
        else {
            clearFields();
        }
    });

    $('#btnCreateGroup').click(function(){ //создание группы
        if($('#Speciality').val() == 0 || $('#newGroupName').val() == '') {
            alert('Сначала выберите специальность и введите название группы');
            return false;
        }
        $.post("/test2.php", {
            act:"createGroup",
            GroupName:$('#newGroupName').val(),
            SpecId:$('#Speciality').val()
        }, function(data){
            data = removeAdvertisement(data);
            $('#group').append($('<option value="'+data+'">'+$('#newGroupName').val()+'</option>'))
            $('#group').attr('disabled', '');
            $('#group').val($('#newGroupName').val());
            $('#newGroupName').val('');
        });
    });

    $('.antract').change(function() { // Меняем время лекций при смене выбора 13:30/14:00
        var LectTime = new Array (
        new Array('8:30', '10:10', '11:50', '13:30', '15:10', '16:40', '18:30', '20:10'),
        new Array('8:30', '10:10', '11:50', '14:00', '15:40', '17:20', '19:00', '20:40')
    );
        // Меняем время следующее за select'ом
        var tbl = $(this).parents('.scheduleWeekDay');
        for (var i = 4; i <= 7; i++) {
            $(tbl[0].rows[i+1].cells[0]).text(LectTime[$(this).val()][i]);
        }
    });

    $('.schedule .content img').click(function() { // Делим на числитель и знаменатель
        split_collapse_subj($(this).parents('tr:first'), 'toggle');
    });

    $('#btnSave').click(function() {
        if ($('#group').val() == 0) {
            alert('Должна быть выбрана группа');
            return false;
        }
        var GroupId = $('#group').val();
        var SubjectId = [];
        var NLesson = [];
        var Week = [];
        var WeekDay = [];
        var Antract = [];
        var PrepodId = [];
        var Type = [];
        var Location = [];
        for(var i = 0; i <= 5; i++) { // Цикл по дням
            var tbl = $('.schedule:eq('+i+') table');
            for(var j = 1; j <= 8; j++) { // Цикл по парам
                var ChZnLen = $(tbl[0].rows[j].cells[2]).children('select').length; // Число select'ов в ячейке
                for(var k = 0; k < ChZnLen; k++) {
                    if ($(tbl[0].rows[j].cells[2]).children('select:eq('+k+')').val() != "0") {
                        var ChZn;
                        if(ChZnLen == 1)
                            ChZn = 0;
                        else
                            ChZn = k+1;
                        SubjectId = SubjectId + '|' +$(tbl[0].rows[j].cells[2]).children('select:eq('+k+')').val();
                        NLesson = NLesson + '|' +j;
                        Week = Week + '|' +ChZn;
                        WeekDay = WeekDay + '|' +(i+1);
                        Antract = Antract + '|' +$('.schedule:eq('+i+') .antract').val();
                        PrepodId = PrepodId + '|' +$(tbl[0].rows[j].cells[4]).children('select:eq('+k+')').val();
                        Type = Type + '|' +$(tbl[0].rows[j].cells[3]).children('select:eq('+k+')').val();
                        Location = Location + '|' +$(tbl[0].rows[j].cells[5]).children('input:eq('+k+')').val();
                    }
                }
            }
        }
        // Отправляем собранные эначения на сервер
        $.post("/test2.php", {act:"scheduleSave", Group:GroupId, SubjectId:SubjectId, NLesson:NLesson,
            Week:Week, WeekDay:WeekDay, Antract:Antract, PrepodId:PrepodId, Type:Type, Location:Location},
        function (data) {
            alert("Сохранено");
        });
    });

    $(".schedule .title").click(function(){
        $(this).next(".content").slideToggle("slow");
    });
});

function clearFields() {
    $('.scheduleWeekDay option[@value="0"]').attr("selected", "selected");
    $('.scheduleWeekDay option[@value="none"]').attr("selected", "selected");
    $('.scheduleWeekDay input').val("");
}

function addSubjects(objsubj, tlocation) {
    $('.subjAll .subjSelect').empty();
    $('.subjAll .subjSelect').append($('<option value="0">Не выбран</option>'));
    if (objsubj.id != "") {
        for(var i = 0; i < (objsubj.id.length); i++) {
            $('.subjAll .subjSelect').append($('<option value="'+objsubj.id[i]+'">'+objsubj.subject[i]+'</option>'));
        }
    }
    $.get("/test2.php", {act:"groupGet", SpecId:$('#Speciality').val()}, function(data) {
        data = removeAdvertisement(data); //Отрезаем рекламу
        var gr = eval("("+data+")");
        $('#group').attr('disabled', '');
        $('#group').empty(); $('#group').append($('<option value="0">- Группа -</option>'));
        for(var i = 0; i < gr.length; i++)
            $('#group').append(
                $('<option value="'+gr[i].id+'">'+gr[i].Title+'</option>')
            );
    });
}