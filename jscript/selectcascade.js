$(document).ready(function(){
  // $('#Facult').val(0);
  $('#Facult').change(function(){ // ��� ����� ���������� ��������� select ������� ��������������
    var facultVal = $('#Facult').val();
    $.get("/test2.php", {act: 'getFacultSpeciality', Fac:facultVal}, function(data){
      data = removeAdvertisement(data);
      $('#Speciality').empty();
      var opt = eval("("+data+")");
      for(var i = 0; i < opt.id.length; i++) {
        $('#Speciality').append($('<option value="'+opt.id[i]+'">'+opt.speciality[i]+'</option>'));
      }
      $('#Speciality').attr('disabled', '');
    });
  });
  
  $('#Speciality').change(changeSubjectList);
  $('#Semestr').change(changeSubjectList);
  
});

function changeSubjectList(){ // ������ ������ ��������� �������������
  var specVal = $('#Speciality').val();
  var semVal = $('#Semestr').val();
  $.get("/test2.php", {act:'getSubjectList', Spec:specVal, Sem:semVal}, function(data){
    data = removeAdvertisement(data); //�������� �������
    var objsubj = eval("("+data+")");
    $('.specCurr').empty();
    // addSubjects - ������ ������������ � ������ ������, ������� ����� �������
    // ��������� � �������
    if(typeof(addSubjects) == 'function')
        addSubjects(objsubj, '.specCurr');
  });
}