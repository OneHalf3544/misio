<script type="text/javascript">
  function addSubjects(objsubj, tlocation) {
    $('#Subject').empty();
    $('#Subject').append($('<option value="">Все</option><option value="0">Не указан</option>'));
    if (objsubj.id != "") {
      $('#Subject').attr('disabled', '');
      for(var i = 0; i < (objsubj.id.length); i++) {
        $('#Subject').append($('<option value="'+objsubj.id[i]+'">'+objsubj.subject[i]+'</option>'));
      }
    }
  }
  
  $(document).ready(function() {
    $('#slideFilterForm').click(function() {
      $('#FilterForm').slideToggle('slow');
      $('#slideFilterForm').toggleClass('slideFilterFormOpened');
      $('#slideFilterForm').toggleClass('slideFilterFormClosed');
    });
  })
</script>
<script type="text/javascript" src="/jscript/selectcascade.js"></script>
<form id="FilterForm" action="files.php" method="get">
<table class="optionsArrayTable">
<tbody>
<tr>
    <td>Факультет: </td>
    <td><?
        if(isset($_REQUEST['Facult']))
            echoFacultSelect($_REQUEST['Facult']);
        else
            echoFacultSelect(); ?>
    </td>
</tr>
<tr>
    <td>Специальность: </td>
    <td><?
        if(isset($_REQUEST['Speciality']))
            echoSpecialitySelect($_REQUEST['Facult'], $_REQUEST['Speciality']);
        else
            echoSpecialitySelect(); ?>
    </td>
</tr>
<tr>
    <td>Семестр: </td>
    <td><? 
        if(isset($_REQUEST['Semestr']))
            echoSemestrNamesSelect($_REQUEST['Semestr']);
        else
            echoSemestrNamesSelect(); ?>
    </td>
</tr>
<tr><td>Предмет: </td>
    <td>
    <?
    if(isset($_REQUEST['Speciality']) && isset($_REQUEST['Semestr']))
        echoSubjectSelect($_REQUEST['Subject'], $_REQUEST['Speciality'],
        $_REQUEST['Semestr']);
    elseif(isset($_REQUEST['Speciality']))
        echoSubjectSelect($_REQUEST['Subject'], $_REQUEST['Speciality']);
    else
        echoSubjectSelect($_REQUEST['Subject']);
    ?></td></tr>
<tr><td>Преподаватель: </td>
    <td><? echo PrepodSelect($_REQUEST['PrepodId']) ?></td></tr>
<tr><td>Тип материала: </td>
  <td><select name="MaterialType" class="shortselect">
    <option value="0">Все</option>
    <? foreach($FilesType as $key => $value): ?>
        <option value="<? echo $key; ?>"
            <? if($key == $_REQUEST['MaterialType']) echo 'selected="selected"'; ?>>
            <? echo $value; ?>
        </option>
    <? endforeach; ?>
  </select></td></tr>
<tr><td>Сортировать по: </td>
  <td><select id="Sort" name="Sorting" class="shortselect">
    <option value="1" <? if($_REQUEST['Sorting'] == 1) echo ' selected="selected"'; ?>>Дате</option>
    <option value="2" <? if($_REQUEST['Sorting'] == 2) echo ' selected="selected"'; ?>>Названию</option>
    <option value="3" <? if($_REQUEST['Sorting'] == 3) echo ' selected="selected"'; ?>>Размеру</option>
    <option value="4" <? if($_REQUEST['Sorting'] == 4) echo ' selected="selected"'; ?>>Типу</option>
    <option value="5" <? if($_REQUEST['Sorting'] == 5) echo ' selected="selected"'; ?>>Комментариям</option>
    <option value="6" <? if($_REQUEST['Sorting'] == 6) echo ' selected="selected"'; ?>>Загрузкам</option>
  </select></td></tr>
<tr><td colspan="2"><label>
    <input type="checkbox" name="ShowUnmarkedFiles" value="yes"
        <? if($_REQUEST['ShowUnmarkedFiles'] == "yes") echo 'checked="checked"' ?> />
    Показывать файлы с неуказанными категориями
</label></td></tr>
</tbody>
</table>
<input type="submit" value="Фильтровать">
</form>
<span id="slideFilterForm" class="slideFilterFormClosed">Фильтрация</span>