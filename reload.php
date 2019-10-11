<?php
mysql_connect("localhost","root", "");
mysql_select_db("hsr");


$xmlstring=file_get_contents("http://www.cbr.ru/scripts/XML_daily.asp");
$xml = simplexml_load_string($xmlstring);
$json = json_encode($xml);
$array = json_decode($json,TRUE);

$result=mysql_query("DELETE FROM currency WHERE 1");
$id=0;

foreach($array['Valute'] as $currency)
{
	$id++;
	$name=$currency['Name'];
	$currency_value=str_replace(',', '.', $currency['Value']);
	$result=mysql_query("INSERT into currency (id, name, rate) values ($id, '$name', $currency_value)");
}

echo 'Курсы обновлены'; 

mysql_close();
?>
