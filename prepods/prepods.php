<?
  $Title = "�������������";
  include $_SERVER["DOCUMENT_ROOT"]."/Design/beforebody.php"; 
?>
<h1><? echo $Title; ?></h1>
<script type="text/javascript">
  $(document).ready(function(){
    $(".prepods .title").click(function(){
      $(this).next(".content").slideToggle("fast");
    });
    
    /*$(".prepods .title:first").addClass("active");
    $(".prepods .content").hide();
    $(".prepods .title").click(function(){
        $(this).parents(".prepods").siblings(".prepods").children(".content:visible").slideUp("slow");
        $(this).next(".content").slideToggle("slow");
     });*/

  });
</script>
<div class="textOfPage">
  <p> �� ������ ��������� ������������� ���������� � �������������� ������ ������������. �� ������ <a href="/prepods/prepodedit.php">�������� ����� ��������</a>, ��� ��������������� ������.
  <p> ����� ������, ��� ������������� �������� � ����� ���������� ������������� � ������������, ������������� ���������� �� <a href="/schedule/raspisanie.php">���������� �������</a>, <a href="/schedule/schededit.php">������������ �� ��������������� ���������</a>.
  <p> P.S. ���������� ������ ���������� ������ ���� ������������� ��� �������� �� ���� ������.
</div>
<?
  $Res = mysql_query(
    "SELECT * FROM `Prepods` 
    ORDER BY `PrepodName`"
  );
  for($Prepods = mysql_fetch_assoc($Res); $Prepods != false; $Prepods = mysql_fetch_assoc($Res)):
    include $_SERVER["DOCUMENT_ROOT"]."/prepods/oneprepod.php";
  endfor;
?>
<? include $_SERVER["DOCUMENT_ROOT"]."/Design/afterbody.php"?>