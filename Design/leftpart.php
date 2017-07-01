<?php /*
Этот блок содержит левую колонку сайта. Т.е. главное меню (Вертикальное), форму входа, категории, теги и сайты-друзья
*/ ?>
<td id="leftcolumn" class="menuColumn">
<!-- Site Menu -->
<div class="vBlock" id="vertMenu">
    <div class="title">Меню сайта</div>
    <div class="content">
    <ul>
        <li><a href="/" title="На главную страницу сайта">Главная</a></li>
        <li><a href="/specmaker.php">Редактор специальностей</a></li>
        <li><a href="/sitemap.php">Карта сайта</a></li>
        <li><a href="/guestbook.php">Гостевая книга</a></li>
        <li><a href="/Pages.php?id=5">Об университете</a></li>
        <li><a href="/Pages.php?id=4">Администраторы</a></li>
        <li><a href="http://www.spgmtu.ru/forum/">Форум</a></li>
        <li><a href="/stats.php">Статистика</a></li>
        <li><a href="/files/addfile.php">Добавить файл</a></li>
        <li><a href="/addfoto.php">Добавить фото</a></li>
        <li><a href="/Users/users.php">Пользователи</a></li>
        <li><a href="/schedule/raspisanie.php">Расписание</a></li>
        <li><a href="/prepods/prepods.php">Преподаватели</a></li>
    </ul>
    </div>
</div>
<!-- /Site Menu -->

<!-- Форма входа -->
<?php if(!isset($_COOKIE['login'])): ?>
  <div class="vBlock">
    <div class="title">Форма входа</div>
    <div class="content">
      <form action="/login.php" method=POST>
        <label>Логин:<br />
        <input id="LogName" type="text" name="LogName" value="Введите имя..."></label>
        <label>Пароль:<br>
        <input id="LogPass" type="password" name="LogPass" value=""></label><br>
        <label><input type="checkbox" id="RemMe" name="RememberMe" value="0">Запомнить меня</label><br />
        <input id="loginButton" type=submit value="Вход"><br />
        <script type="text/javascript">
          $('#loginButton').click(function() { // Проверяем логин/пароль
            var RM = 0;
            if($('#RemMe').attr('checked') == 'checked') 
              RM = 1;
            $.post("/login.php", {LogName:$('#LogName').val(), LogPass:$('#LogPass').val(), 
                RememberMe:RM, AJAXused: true}, function(data){
                data = removeAdvertisement(data);
              if (data == "Success") {
                window.location.reload(); //Если авторизация успешна, обновляем страницу.
              }
              else { // Иначе показываем юзеру, что не так
                alert(data); 
              }
            });
            return false;
          });
        </script>
        <a href="/">Забыл пароль</a><br />
        <a href="/Users/register.php">Зарегистрироваться</a>
      </form>
    </div>
  </div>
<?php endif ?>
<!-- /Форма входа -->
 
<!-- Friends -->
<div class="vBlock" id="friendSites">
<div class="title">Сайты</div>
<div class="content">
 <ul>
  <li><a href="http://misio.ucoz.ru" target="_blank" title="Старый сайт: misio.ucoz.ru">МИСиО на ucoz'е</a></li>
  <li><a href="http://www.smtu.ru/index2.html" target="_blank" 
    title="Админ там, похоже, помер. Поэтому инфы там почти нет. www.smtu.ru">Официальный сайт СПбГМТУ</a></li>
  <li><a href="http://vkontakte.ru/club22883" target="_blank" title="Группа вконтакте.">Приборостроительный факультет вконтакте</a></li>
  <li><a href="http://vt.homisoft.com" target="_blank" title="Сайт группы специальности 'Вычислительная техника'">Сайт группы ВТ</a></li>
  <li><a href="http://www.student-mtu.narod.ru/findex.htm" target="_blank" title="Сайт группы факультета корабельной энергетики и автоматики (специальнось - судовые энергетические установки, вроде). Советую туда заглянуть, там много полезных статей об универе, общагах и т.д. И файлов по учебе там тоже навалом.">Неофициальный сайт студентов СПбГМТУ</a></li>
  <li><a href="http://dieseldvs.ru/" target="_blank" title="Еще один сайт группы с факультета энергетики и автоматики. Со специальности 'Двигатели внутреннего сгорания'. Каталог файлов там тоже обширный, загляните туда тоже.">Сайт дизелистов</a></li>
  <li><a href="http://smtueco.ru/index.shtml" target="_blank">Сайт экологов</a></li>
  <li><a href="http://www.twirpx.com" target="_blank">Общирная студенческая библиотека</a></li>
  <li><a href="http://www.iti.spb.ru/rus/index.shtml" target="_blank">Институт информационных технологий СПбГМТУ</a></li>
 </ul>
 </div>
</div>
<!-- /Friends -->

<!-- Рекламные минибаннеры -->
<div class="minibanner">
  <!-- Intuit -->
  <a title="Интернет-Университет" href="http://www.intuit.ru">
    <img alt="Intuit" border="0" src="http://www.intuit.ru/partner/88x31_6.gif">
  </a>
  <!-- /Intuit -->
  <!-- Hut -->
  <a href="http://www.hut.ru">
    <img src="http://www.hut.ru/imgs/ban/hutm1.gif" alt="Лучший бесплатный хостинг">
  </a>
  <!-- /Hut -->
</div>
<!-- /Рекламные минибаннеры -->
</td>