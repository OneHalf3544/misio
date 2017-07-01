<?php /*
Этот блок содержит верхнее горизонтальное меню. 
*/ ?>
<!-- Horiz Menu -->
<div class="hMenuDiv"> 
  <table class="hMenuTable">
  <tbody>
    <tr>
      <td class="hMenuLeft"> </td>
      <td class="hMenuTd"><a href="http://misio.hut1.ru/">На главную</a></td>
      <td class="hMenuSeparator"> </td>
      <td class="hMenuTd"><a href="/files/files.php">Файлы</a></td>
      <td class="hMenuSeparator"> </td>
    <?php
    if(isset($_COOKIE['login'])): ?>
      <td class="hMenuTd"><a href="/photo.php">Фотоальбом</a></td>
      <td class="hMenuSeparator"> </td>
      <td class="hMenuTd"><a href="/Users/profile.php?LogName=<? echo $_COOKIE['login']; ?>">Мой профиль</a></td>
      <td class="hMenuSeparator"> </td>
      <td class="hMenuTd"><a href="/logout.php" onclick="return logout()">Выход</a></td>
    <? else: ?>
      <td class="hMenuTd"><a href="/Users/register.php">Регистрация</a></td>
    <? endif; ?>
      <td class="hMenuRight"> </td>
    </tr>
  </tbody>
  </table>

<? /* Выводим приветствие и дату */ ?>
<p class="greatings">Приветствую Вас, 
<? if(isset($_COOKIE['login'])): ?>
    <b><? echo $_COOKIE['login'] ?></b>;
<? else: ?>
    <b>Гость</b>
<? endif; ?>
Текущая дата: <i> <? echo date("d.m.y").', '.date("H:i"); ?>
</i></p>
</div>
<!-- /Horiz Menu -->