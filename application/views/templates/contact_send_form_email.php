<!DOCTYPE HTML>

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body>
<?
foreach($contact_fields as $field_id => $value)
{
	?><strong><?= ucfirst($field_id)?>: </strong> <?= $value?>.<br><?	
}
?>
<br><br>
<?= $signature?>

</body>

</html>