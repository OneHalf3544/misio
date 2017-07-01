<? // TODO Сделать PageSelector
$Title = "Управление комментариями"; ?>

<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php") ?>
<!-- Body -->
<h1><? echo $Title; ?></h1>
<?
    $Res = mysql_query('SELECT * FROM `Comments` ORDER BY `id` DESC');
    while(($S = mysql_fetch_assoc($Res)) != false): ?>
        <div class="comments">
            <div class="title">
                <a href="$Profile"><? echo profileURL($S['Author']); ?></a>
                <span class="time"><? echo date("d-m-Y H:i", $S['DateOfAdding']) ?></span>
            </div>
            <div class="content">
                <? echo $S['Text'] ?>
                <div class="Details">
                    <a href="<? echo materialURL($S['TypeOfMat'], $S['NMaterial']); ?>">
                        Материал (<? echo $MaterialType[$S['TypeOfMat']]; ?>)
                    </a>
                </div>
            </div>
        </div>
    <? endwhile;
    // IP
    // Rating
?>
<!-- /Body -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php") ?>