<?

require_once('mobile-api.php');
$API = new mobileAPI();
$_REQ = $_POST;


echo 'OK: '. date("F j, Y, g:i a").' | '. $API->reqToken();

