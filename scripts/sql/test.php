<?php 
include("simple_html_dom.php");
$html = file_get_html("http://www.fakenamegenerator.com/gen-random-us-us.php");
$name = $html->find("div[class=address] h3",0)->innertext;
$address = $html->find("div[class=adr]",0)->innertext;
$phone = $html->find("dl[class=dl-horizontal] dd",3)->innertext;
$email = $html->find("dl[class=dl-horizontal] dd",8)->innertext;

$address = preg_split('/<br[^>]*>/i', $address);
$city = explode(",", $address[1]);
$state = explode(" ", $city[1]);
$zip = $state[2];
$email = explode(" ", $email);
 $name = explode(" ", $name);


echo $name[0];
echo "<br/>";
echo $name[1][0];
echo "<br/>";
echo $name[2];
echo "<br/>";
echo  $address[0];
echo "<br/>";
echo $city[0];
echo "<br/>";
echo $state[1];
echo "<br/>";
echo $zip;
echo "<br/>";
echo $phone;
echo "<br/>";
echo $email[0];