<?php


$curl = curl_init();

curl_setopt_array($curl, array(

  CURLOPT_URL => "http://110.50.232.252/abp/m/ept",
  //CURLOPT_URL => "https://msw.ph/m/ept?lineId=2&originId=3",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYHOST=> 0,
  CURLOPT_SSL_VERIFYPEER=> 0,
  CURLOPT_HEADER=> 0,
  CURLOPT_NOBODY=> 0,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  /*CURLOPT_HTTPHEADER => array(
    'Content-type: application/json',
	  'X-LVS-HSToken: W5vYoFoA7zggZoPVwsv0QLowsyNuBof7Dgo2xw0BF9wlfJelcrFevA1p8f8PTjaeTqcc_nEtM1wdjOKfCQUFgtUuuX9IgO48G3dZIzUklGu92y6Jjt5triBrohERmRtaGGDajd_QtyB3O7MjuInbuQ=='
  ),*/
  CURLOPT_HTTPHEADER => array(
    'Content-type: application/json',
  'X-LVS-HSToken: nhqekre3Fs-i0OlaP0hKcQeTF8H4d2ueLwYGGzb00IXrFG5iOk_rnLLGy1VchdF4ctsZOHv_AzSbJpblfNIm9DaipF6og6mQFK8jA60sgF8yik2uRRUvINafZ7Oz-c3V'
  ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo '<pre>';
  print_r(json_decode($response,1));
  echo '</pre>';
}





?>