<? // TODO Доделать страничку специальностей
$Title = "Специальности" ?>
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?>
<!-- Body -->
<h1><? echo $Title; ?></h1>
<?
    $Res = mysql_query(
        'SELECT `Speciality`.*, `Facults`.`FacultName` FROM `Speciality`
        LEFT JOIN `Facults` ON `Speciality`.`FacultId` = `Facults`.`id`
        ORDER BY `Speciality`.`Spec`'
    );
    while(($S = mysql_fetch_assoc($Res)) != false): ?>
        <div class="speciality">
          <h2 class="title">
            <? echo $S['Spec']; ?>
          </h2>
          <div class="content">
            <? echo $S['Description']; ?>
              <h3>Факультет: </h3><? echo $S['FacultName']; ?>
          </div>
        </div>
    <? endwhile; ?>
<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php")?>