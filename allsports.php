<?php 
error_reporting(-1); 

include_once("includes/functions.php");
	
$sportActive = get_active_sports();
$csport = (!empty($sportActive)) ?$sportActive[0] : 'Basketball';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="refresh" content="1800">

    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>MSW Pre-Match Odds</title>

  </head>

  <body style="margin: 0;">

    <iframe src="https://<?=$_SERVER['SERVER_NAME']?>/mswodds/prematch/?sport=<?=$csport?>" title="MSW Pre-Match Odds" style="width: 100%; min-height: 99vh; border: none;"></iframe>

  </body>

</html>
