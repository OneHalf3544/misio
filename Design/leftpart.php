<?php /*
���� ���� �������� ����� ������� �����. �.�. ������� ���� (������������), ����� �����, ���������, ���� � �����-������
*/ ?>
<td id="leftcolumn" class="menuColumn">
<!-- Site Menu -->
<div class="vBlock" id="vertMenu">
    <div class="title">���� �����</div>
    <div class="content">
    <ul>
        <li><a href="/" title="�� ������� �������� �����">�������</a></li>
        <li><a href="/specmaker.php">�������� ��������������</a></li>
        <li><a href="/sitemap.php">����� �����</a></li>
        <li><a href="/guestbook.php">�������� �����</a></li>
        <li><a href="/Pages.php?id=5">�� ������������</a></li>
        <li><a href="/Pages.php?id=4">��������������</a></li>
        <li><a href="http://www.spgmtu.ru/forum/">�����</a></li>
        <li><a href="/stats.php">����������</a></li>
        <li><a href="/files/addfile.php">�������� ����</a></li>
        <li><a href="/addfoto.php">�������� ����</a></li>
        <li><a href="/Users/users.php">������������</a></li>
        <li><a href="/schedule/raspisanie.php">����������</a></li>
        <li><a href="/prepods/prepods.php">�������������</a></li>
    </ul>
    </div>
</div>
<!-- /Site Menu -->

<!-- ����� ����� -->
<?php if(!isset($_COOKIE['login'])): ?>
  <div class="vBlock">
    <div class="title">����� �����</div>
    <div class="content">
      <form action="/login.php" method=POST>
        <label>�����:<br />
        <input id="LogName" type="text" name="LogName" value="������� ���..."></label>
        <label>������:<br>
        <input id="LogPass" type="password" name="LogPass" value=""></label><br>
        <label><input type="checkbox" id="RemMe" name="RememberMe" value="0">��������� ����</label><br />
        <input id="loginButton" type=submit value="����"><br />
        <script type="text/javascript">
          $('#loginButton').click(function() { // ��������� �����/������
            var RM = 0;
            if($('#RemMe').attr('checked') == 'checked') 
              RM = 1;
            $.post("/login.php", {LogName:$('#LogName').val(), LogPass:$('#LogPass').val(), 
                RememberMe:RM, AJAXused: true}, function(data){
                data = removeAdvertisement(data);
              if (data == "Success") {
                window.location.reload(); //���� ����������� �������, ��������� ��������.
              }
              else { // ����� ���������� �����, ��� �� ���
                alert(data); 
              }
            });
            return false;
          });
        </script>
        <a href="/">����� ������</a><br />
        <a href="/Users/register.php">������������������</a>
      </form>
    </div>
  </div>
<?php endif ?>
<!-- /����� ����� -->
 
<!-- Friends -->
<div class="vBlock" id="friendSites">
<div class="title">�����</div>
<div class="content">
 <ul>
  <li><a href="http://misio.ucoz.ru" target="_blank" title="������ ����: misio.ucoz.ru">����� �� ucoz'�</a></li>
  <li><a href="http://www.smtu.ru/index2.html" target="_blank" 
    title="����� ���, ������, �����. ������� ���� ��� ����� ���. www.smtu.ru">����������� ���� �������</a></li>
  <li><a href="http://vkontakte.ru/club22883" target="_blank" title="������ ���������.">������������������� ��������� ���������</a></li>
  <li><a href="http://vt.homisoft.com" target="_blank" title="���� ������ ������������� '�������������� �������'">���� ������ ��</a></li>
  <li><a href="http://www.student-mtu.narod.ru/findex.htm" target="_blank" title="���� ������ ���������� ����������� ���������� � ���������� (������������ - ������� �������������� ���������, �����). ������� ���� ���������, ��� ����� �������� ������ �� �������, ������� � �.�. � ������ �� ����� ��� ���� �������.">������������� ���� ��������� �������</a></li>
  <li><a href="http://dieseldvs.ru/" target="_blank" title="��� ���� ���� ������ � ���������� ���������� � ����������. �� ������������� '��������� ����������� ��������'. ������� ������ ��� ���� ��������, ��������� ���� ����.">���� ����������</a></li>
  <li><a href="http://smtueco.ru/index.shtml" target="_blank">���� ��������</a></li>
  <li><a href="http://www.twirpx.com" target="_blank">�������� ������������ ����������</a></li>
  <li><a href="http://www.iti.spb.ru/rus/index.shtml" target="_blank">�������� �������������� ���������� �������</a></li>
 </ul>
 </div>
</div>
<!-- /Friends -->

<!-- ��������� ����������� -->
<div class="minibanner">
  <!-- Intuit -->
  <a title="��������-�����������" href="http://www.intuit.ru">
    <img alt="Intuit" border="0" src="http://www.intuit.ru/partner/88x31_6.gif">
  </a>
  <!-- /Intuit -->
  <!-- Hut -->
  <a href="http://www.hut.ru">
    <img src="http://www.hut.ru/imgs/ban/hutm1.gif" alt="������ ���������� �������">
  </a>
  <!-- /Hut -->
</div>
<!-- /��������� ����������� -->
</td>