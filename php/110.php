<?php
error_reporting(0);
$ts = $_GET['ts'];
if(!$ts){
  $id = isset($_GET['id'])?$_GET['id']:'xmyd';
  $n = [
       'xmws' => 84,
       'xm1' => 16,
       'xm2' => 17,
       'xmyd' => 138
       ];
  $url = 'http://mapi1.kxm.xmtv.cn/api/v1/channel.php?channel_id='.$n[$id];
  $live = json_decode(file_get_contents($url))[0]->channel_stream[0]->m3u8;
  $burl = 'https://'.parse_url($live)['host'];
  if($id=='xmyd'){
    header('Content-Type: application/vnd.apple.mpegurl');
    print_r(preg_replace("/(.*?.ts)/i","http://".$_SERVER[HTTP_HOST].$_SERVER[PHP_SELF]."?ts=$burl$1",m3u8($live)));
    } else {
      header('Content-Type: application/vnd.apple.mpegurl');
      print_r(preg_replace("/\.\.\/\.\.\//", "", preg_replace("/(.*?.ts)/i","http://".$_SERVER[HTTP_HOST].$_SERVER[PHP_SELF]."?ts=$burl$1",m3u8($live))));
      }
} else {
      $data = ts($ts);
      header('Content-Type: video/MP2T');
      echo $data;
      }

function m3u8($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_REFERER, 'https://kxmapp.mapi1.kxm.xmtv.cn/');
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function ts($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_REFERER, 'https://kxmapp.mapi1.kxm.xmtv.cn/');
    $result = curl_exec($ch);
    curl_close($ch);
}
?>