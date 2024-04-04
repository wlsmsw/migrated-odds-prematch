<?

switch($_SERVER['SERVER_NAME']){
    case 'mswlive.com':
        $db_user = "mswliv5_api_prematch_mswdev";
        $db_pass = "OY&57(d-&W94";
        $db_host = "localhost";
        $db_name = "mswliv5_api_prematch_sports";
        break;
    case 'mswsites.com':
        $db_user = "api_prematch_sports";
        $db_pass = "2^f2~A.eqwiZ";
        $db_host = "localhost";
        $db_name = "api_prematch_sports";
        break;
    default:
        $db_user = "";
        $db_pass = "";
        $db_host = "localhost";
        $db_name = "";
}

$sv_conn = new mysqli($db_host,$db_user,$db_pass);
if (!$sv_conn) {
    die('Not connected : ' . $sv_conn->error);
}

$db_conn = $sv_conn->select_db($db_name);

if(!$db_conn){
  die('Cannot Select DB: '. $sv_conn->error);
}

?>
