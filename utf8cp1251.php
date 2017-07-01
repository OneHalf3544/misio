<?php
function utf8_win ($s){
$out="";
$c1="";
$byte2=false;
for ($c=0;$c<strlen($s);$c++){
$i=ord($s[$c]);
if ($i<=127) $out.=$s[$c];
if ($byte2){
$new_c2=($c1&3)*64+($i&63);
$new_c1=($c1>>2)&5;
$new_i=$new_c1*256+$new_c2;
if ($new_i==1025){
$out_i=168;
}else{
if ($new_i==1105){
$out_i=184;
}else {
$out_i=$new_i-848;
}
}
$out.=chr($out_i);
$byte2=false;
}
if (($i>>5)==6) {
$c1=$i;
$byte2=true;
}
}
return $out;
}


function win_utf8 ($in_text){
$output="";
$other[1025]="¨";
$other[1105]="¸";
$other[1028]="ª";
$other[1108]="º";
//$other[1030]="I";
//$other[1110]="i";
$other[1031]="¯";
$other[1111]="¿";

for ($i=0; $i<strlen($in_text); $i++){
if (ord($in_text{$i})>191){
  $output.="&#".(ord($in_text{$i})+848).";";
} else {
  if (array_search($in_text{$i}, $other)===false){
   $output.=$in_text{$i};
  } else {
   $output.="&#".array_search($in_text{$i}, $other).";";
  }
}
}
return $output;
}
?>