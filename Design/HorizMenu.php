<?php /*
���� ���� �������� ������� �������������� ����. 
*/ ?>
<!-- Horiz Menu -->
<div class="hMenuDiv"> 
  <table class="hMenuTable">
  <tbody>
    <tr>
      <td class="hMenuLeft"> </td>
      <td class="hMenuTd"><a href="http://misio.hut1.ru/">�� �������</a></td>
      <td class="hMenuSeparator"> </td>
      <td class="hMenuTd"><a href="/files/files.php">�����</a></td>
      <td class="hMenuSeparator"> </td>
    <?php
    if(isset($_COOKIE['login'])): ?>
      <td class="hMenuTd"><a href="/photo.php">����������</a></td>
      <td class="hMenuSeparator"> </td>
      <td class="hMenuTd"><a href="/Users/profile.php?LogName=<? echo $_COOKIE['login']; ?>">��� �������</a></td>
      <td class="hMenuSeparator"> </td>
      <td class="hMenuTd"><a href="/logout.php" onclick="return logout()">�����</a></td>
    <? else: ?>
      <td class="hMenuTd"><a href="/Users/register.php">�����������</a></td>
    <? endif; ?>
      <td class="hMenuRight"> </td>
    </tr>
  </tbody>
  </table>

<? /* ������� ����������� � ���� */ ?>
<p class="greatings">����������� ���, 
<? if(isset($_COOKIE['login'])): ?>
    <b><? echo $_COOKIE['login'] ?></b>;
<? else: ?>
    <b>�����</b>
<? endif; ?>
������� ����: <i> <? echo date("d.m.y").', '.date("H:i"); ?>
</i></p>
</div>
<!-- /Horiz Menu -->