<? // TODO ���������� ��������� �� JavaScript
  function getDay($day, $mon, $year) {
    $day = (int)$day; //���� ���� �������������� � <10 
    $mon = (int)$mon; //���� ����� �������������� � <10 
    $a = (int)((14 - $mon) / 12);
    $y = $year - $a;
    $m = $mon + 12 * $a - 2;
    $d = (7000 + (int)($day+$y+ (int)($y/4) - (int)($y/100) + (int)($y/400) + (31*$m)/12))%7;
    return $d;
  }
  $days = array("�����������", "�����������", "�������", "�����", "�������", "�������", "�������");
  if(isset($_REQUEST['month'])) 
      $month = $_REQUEST['month'];
  else 
      $month = (int)date("m");
  if(isset($_REQUEST['year'])) 
      $year = $_REQUEST['year'];
  else 
      $year = (int)date("Y");
  $monthLen  = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  $monthName = array(
    '������',   '�������',  '����', 
    '������',   '���',      '����', 
    '����',     '������',   '��������', 
    '�������',  '������',   '�������'
  );
  
  if ($year % 4 == 0) $monthLen[1]++;
  $shift = getDay(1, $month, $year)-1; 
?>
  <div class="vBlock">
    <div class="title">��������� ��������</div>
    <div class="content">
      <table class="calendar">
      <tr>
        <td colspan="7">
        << <? echo $monthName[$month-1]; ?> >>
        </td>
      </tr>
      <tr>
        <th>��</th>
        <th>��</th>
        <th>��</th>
        <th>��</th>
        <th>��</th>
        <th>��</th>
        <th>��</th>
      </tr>
      <tr>
<?
  for($i = 1; $i <= $shift; $i++) 
    echo "<td></td>";
  for($i = 1; $i <= $monthLen[$month]; $i++) {
    if($month == (int)date('m') && $year == (int)date('Y') && $i == (int)date('d')) 
        echo "<td class=\"today\">$i</td>";
    else
        echo "<td>$i</td>";
    if((($i+$shift) % 7 == 0) && ($i != $monthLen[$month])) 
      echo "</tr><tr>";
  }
  for($i = 1; $i <= 6 - ($monthLen[$month]+$shift-1) % 7; $i++) 
    echo "<td></td>"; 
?>
  </tr></table>
  </div>
</div>
