/* 
 * Здесь содержатся функции для редактора специальностей.
 */
$(document).ready(function(){
    addInteractivePosibility('.specCurr div.subject', true);

  $('#createButton').click(function () {
    /*
     * Определяем реакцию на кнопку создания предмета
     */
    //Добавить форму создания
    if ( $('#createForm').length == 0) {
        $(this).after(
          $('<div id="createForm">Заголовок<br />'+
            '<input id="subjCreateTitle" class="longinput" type="text"><br />'+
            'Описание<br />'+
            '<textarea id="subjCreateDescr"></textarea>'+
            '<input id="btnSubjCreate" type="button" value="Применить">'+
            '<input id="btnSubjCreateCancel" type="button" value="Отменить">'+
          '</div>')
        );
        
        $('#btnSubjCreate').click(function(){
          //Сформировать html блок с введенным содержимым, убрать форму.
          //Отправить запрос на добавление в базу данных
          $.post("/test2.php", {act:'createSubject', title:$('#subjCreateTitle').val(), descr:$('#subjCreateDescr').val()}, function(data){
            data = removeAdvertisement(data); //Отрезаем рекламу
            id = eval('('+data+')');
            //Добавить этот id в код html
            var objsubj = {id:id, subject:new Array($('#subjCreateTitle').val()),
              descr:new Array($('#subjCreateDescr').val())};
            addSubjects(objsubj, '.subjAll');
            $('#createForm').remove(); //Удаляем лишнее
          });
        });

        $('#btnSubjCreateCancel').click(function(){
            // Отмена создания предмета
            $('#createForm').remove(); // Удаляем лишнее
        });
    }
  });
});

/*
 * По нажатию на кнопку удаления
 * Удаляет предмет из текущего семестра
 */
function removeSubject() {
    $(this).parents('.subject').fadeOut("normal");
    var specVal = $('#Speciality').val();
    var semVal = $('#Semestr').val();
    $.post('/test2.php', {
        act: 'removing',
        Spec: specVal,
        Sem: semVal,
        id: $(this).parents('.subject').attr('id')}
    );
}

/*
 * Добавляет предмет к специальности
 */
function subjectApply(){
    if(($('#Semestr').val() == 0) || ($('#Speciality').val() == 0)) {
        alert('Сначала выберите специальность и семестр');
    }
    else {
        $('.specCurr').prepend($(this).parents('.subject')); // Перемещаем блок
        
        $.post('/test2.php', {
            act  : 'adding',
            Spec : $('#Speciality').val(),
            Sem  : $('#Semestr').val(),
            id   : $(this).parents('.subject').attr('id')
        });

        // Меняем плюсег на крестег
        $(this).before($('<img class="delSubject" src="/img/specmaker/del.png">'));
        $(this).prev('img').click(removeSubject);
        $(this).remove();
    }
}

/*
 * Производит поиск предметов в базе данных по запросу
 */
function searchSubjects() {
  $('#searchForm').nextAll().remove();
  $.post("/test2.php", {act:'findSubject', findStr : $('#findStr').val()}, function(data){
    data = removeAdvertisement(data); //Отрезаем рекламу
    var objsubj = eval("("+data+")");
    $('#searchForm').nextAll().remove();
    addSubjects(objsubj, '.subjAll');
  });
}

function addSubjects(objsubj, tlocation) { //objsubj - массив с данными, tlocation - селектор того, куда добавлять
  if (objsubj.id != "") {
    for(var i = 0; i < (objsubj.id.length); i++) {
        $(tlocation).append(
            $('<div id="subjId'+objsubj.id[i]+'" class="subject">'+
                '<div class="title">'+objsubj.subject[i]+'</div>'+
                '<div class="content">'+objsubj.descr[i]+'</div>'+
            '</div>')
        );
      addInteractivePosibility(tlocation+' div.subject:last', tlocation == '.specCurr');
    }
  }
}

function ApplyButtonClick () {
    //Нажатие на кнопку "Применить" в форме 
    var CurSubj = $(this).parents('.subject');
    var CurForm = $(CurSubj).children('.subjectMiniForm');
    $(CurSubj).children('div.title').text($(this).prevAll('input:last[type="text"]').val());
    $(CurSubj).children('div.content').show();
    $(CurSubj).children('div.content').text(
      $(CurForm).children('textarea').val() //'<p>'+ ... .split('\n').join('</p><p>')+'</p>'
    );
    $.post('/subjectedit.php', {id : $(CurSubj).attr("id"),
      subject : $(CurSubj).children('div.title').text(),
      descr :   $(CurSubj).children('div.content').text()}
    );
    $(CurForm).remove();
}

function addInteractivePosibility(target, imgButtonDelete) {
    if (imgButtonDelete == true) { // Рисуем либо крестик, либо плюсик. Смотря куда добавляем
        $(target+' .title').append(
            $('<img alt="Удалить" class="delSubject" src="/img/specmaker/del.png">')
        );
        $(target+' .title img').click(removeSubject);
    } else {
        $(target+' .title').append(
            $('<img alt="Добавить" class="applySubject" src="/img/specmaker/add.png">'));
        $(target+' .title img').click(subjectApply);
    }
    $(target+' .title').dblclick(function(){ // Редактируем предмет по двойному клику на заголовке
        if ($(this).next('div.content').css('display') != "none") { 
          // Рисуем форму редактирования, если она еще не создана
          $(this).parent().append($(
              '<form class="subjectMiniForm">'+
                '<input class="longinput" type="text">'+
                '<textarea></textarea>'+
                '<input type="button" name="applyText"    value="Применить">'+
                '<input type="button" name="cancelChange" value="Отменить" >'+
              '</form>'
          ));
          // Копируем текст в соответствующие поля ввода
          $(this).nextAll('.subjectMiniForm').children('input[type="text"]:first').val($(this).text());
          $(this).nextAll('.subjectMiniForm').children('textarea:first').val($(this).next('div.content').text());
          $(this).next('div.content').hide();

          $(this).nextAll('.subjectMiniForm').children('input[name="applyText"]').click(ApplyButtonClick);
          $(this).nextAll('.subjectMiniForm').children('input[name="cancelChange"]').click(function() {
              // По нажатию на кнопку отмены удаляем форму, и показываем то, что было
              $(this).parents('.subject').children('.content').show();
              $(this).parents('.subjectMiniForm').remove();
          });
        }
    });
}