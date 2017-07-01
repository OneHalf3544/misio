<? $Title = "Карта сайта" ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?> 

<!-- Body --> 
<div class="textOfPage">
  <h1><? echo $Title; ?></h1>
  <ul>
      <li>Страницы сайта</li>
    <ul>
    <? 
      $Res = mysql_query('SELECT `id`, `Title` FROM `Pages` ORDER BY `Title`');
      while(($S = mysql_fetch_assoc($Res)) != false) {
        echo "<li><a href=\"/Pages.php?id=".$S['id']."\">".$S['Title']."</a></li>";
      }
    ?>
        <li><a href="/SitePages/editsitepages.php">Редактор страниц</a></li>
    </ul>
    
    <li> Преподаватели
    <ul>
        <li> <a href="/prepods/prepods.php">Список преподавателей</a></li>
        <li> <a href="/prepods/prepodedit.php">Создание описания преподавателя</a></li>
        <li> <a href="/prepods/depts.php">Список кафедр университета</a></li>
    </ul>
    
    <li> Расписание
    <ul>
      <li> <a href="/schedule/raspisanie.php">Расписание групп</a></li>
      <li> <a href="/schedule/schededit.php">Редактор расписания</a></li>
      <li> <a href="/specmaker.php">Редактор специальностей</a></li>
    </ul>
    
    <li> Пользователи сайта
    <ul>
      <li> <a href="/Users/users.php">Список пользователей</a></li>
      <li> <a href="/Users/register.php">Ваш профиль</a></li>
    </ul>
    
    <li> Файлы
    <ul>
      <li> <a href="/files/files.php">Файлы</a></li>
      <li> <a href="/files/addfile.php">Добавить файл</a></li>
    </ul>
    
  </ul>
</div>
<!-- /Body -->

<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php") ?>