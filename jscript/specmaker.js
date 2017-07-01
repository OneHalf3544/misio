/* 
 * ����� ���������� ������� ��� ��������� ��������������.
 */
$(document).ready(function(){
    addInteractivePosibility('.specCurr div.subject', true);

  $('#createButton').click(function () {
    /*
     * ���������� ������� �� ������ �������� ��������
     */
    //�������� ����� ��������
    if ( $('#createForm').length == 0) {
        $(this).after(
          $('<div id="createForm">���������<br />'+
            '<input id="subjCreateTitle" class="longinput" type="text"><br />'+
            '��������<br />'+
            '<textarea id="subjCreateDescr"></textarea>'+
            '<input id="btnSubjCreate" type="button" value="���������">'+
            '<input id="btnSubjCreateCancel" type="button" value="��������">'+
          '</div>')
        );
        
        $('#btnSubjCreate').click(function(){
          //������������ html ���� � ��������� ����������, ������ �����.
          //��������� ������ �� ���������� � ���� ������
          $.post("/test2.php", {act:'createSubject', title:$('#subjCreateTitle').val(), descr:$('#subjCreateDescr').val()}, function(data){
            data = removeAdvertisement(data); //�������� �������
            id = eval('('+data+')');
            //�������� ���� id � ��� html
            var objsubj = {id:id, subject:new Array($('#subjCreateTitle').val()),
              descr:new Array($('#subjCreateDescr').val())};
            addSubjects(objsubj, '.subjAll');
            $('#createForm').remove(); //������� ������
          });
        });

        $('#btnSubjCreateCancel').click(function(){
            // ������ �������� ��������
            $('#createForm').remove(); // ������� ������
        });
    }
  });
});

/*
 * �� ������� �� ������ ��������
 * ������� ������� �� �������� ��������
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
 * ��������� ������� � �������������
 */
function subjectApply(){
    if(($('#Semestr').val() == 0) || ($('#Speciality').val() == 0)) {
        alert('������� �������� ������������� � �������');
    }
    else {
        $('.specCurr').prepend($(this).parents('.subject')); // ���������� ����
        
        $.post('/test2.php', {
            act  : 'adding',
            Spec : $('#Speciality').val(),
            Sem  : $('#Semestr').val(),
            id   : $(this).parents('.subject').attr('id')
        });

        // ������ ������ �� �������
        $(this).before($('<img class="delSubject" src="/img/specmaker/del.png">'));
        $(this).prev('img').click(removeSubject);
        $(this).remove();
    }
}

/*
 * ���������� ����� ��������� � ���� ������ �� �������
 */
function searchSubjects() {
  $('#searchForm').nextAll().remove();
  $.post("/test2.php", {act:'findSubject', findStr : $('#findStr').val()}, function(data){
    data = removeAdvertisement(data); //�������� �������
    var objsubj = eval("("+data+")");
    $('#searchForm').nextAll().remove();
    addSubjects(objsubj, '.subjAll');
  });
}

function addSubjects(objsubj, tlocation) { //objsubj - ������ � �������, tlocation - �������� ����, ���� ���������
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
    //������� �� ������ "���������" � ����� 
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
    if (imgButtonDelete == true) { // ������ ���� �������, ���� ������. ������ ���� ���������
        $(target+' .title').append(
            $('<img alt="�������" class="delSubject" src="/img/specmaker/del.png">')
        );
        $(target+' .title img').click(removeSubject);
    } else {
        $(target+' .title').append(
            $('<img alt="��������" class="applySubject" src="/img/specmaker/add.png">'));
        $(target+' .title img').click(subjectApply);
    }
    $(target+' .title').dblclick(function(){ // ����������� ������� �� �������� ����� �� ���������
        if ($(this).next('div.content').css('display') != "none") { 
          // ������ ����� ��������������, ���� ��� ��� �� �������
          $(this).parent().append($(
              '<form class="subjectMiniForm">'+
                '<input class="longinput" type="text">'+
                '<textarea></textarea>'+
                '<input type="button" name="applyText"    value="���������">'+
                '<input type="button" name="cancelChange" value="��������" >'+
              '</form>'
          ));
          // �������� ����� � ��������������� ���� �����
          $(this).nextAll('.subjectMiniForm').children('input[type="text"]:first').val($(this).text());
          $(this).nextAll('.subjectMiniForm').children('textarea:first').val($(this).next('div.content').text());
          $(this).next('div.content').hide();

          $(this).nextAll('.subjectMiniForm').children('input[name="applyText"]').click(ApplyButtonClick);
          $(this).nextAll('.subjectMiniForm').children('input[name="cancelChange"]').click(function() {
              // �� ������� �� ������ ������ ������� �����, � ���������� ��, ��� ����
              $(this).parents('.subject').children('.content').show();
              $(this).parents('.subjectMiniForm').remove();
          });
        }
    });
}