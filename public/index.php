<?php
// print_r($_SERVER);
function lllog($msg) {
  \error_log("\n" . $msg . "\n", 3, '/var/log/njzs-wechat.log'); 
}

if($_SERVER['SERVER_NAME']!='nj.tiangoutech.com' && $_SERVER['SERVER_ADDR']!='127.0.0.1'){
  \lllog($_SERVER['SERVER_NAME']);
  exit();
}
ini_set('date.timezone', 'Asia/Shanghai');
header('Content-Type: text/html; charset=UTF-8');
// echo '/tmp/' . $_SERVER["HTTP_HOST"];
// print_r($_SERVER);
$pos1 = strpos($_SERVER['REQUEST_URI'],'sync/get_items_from_remote');
$pos2 = strpos($_SERVER['REQUEST_URI'],'sync/push_state_to_remote');
$pos3 = strpos($_SERVER['REQUEST_URI'],'sync/save_items_from_local');
if( $pos1>0 || $pos2>0 || $pos3>0 ){
}else{

  \lllog('[session:]'.$_SERVER['SERVER_NAME'].'--'.$_SERVER['REQUEST_URI']);

  $session_save_path = '/tmp/' . $_SERVER["HTTP_HOST"];
  //检查session存储目录,若不存在则创建
  if (!is_dir($session_save_path)) {
      $umask = umask();
      umask(00);
      mkdir($session_save_path, 01777, true);
      umask($umask);
  }

  ini_set('session.use_cookies', '1');            //使用cookie
  ini_set('session.cookie_lifetime', '172800');   //设置session有效时间20天。60*60*24*20=172800。
  ini_set('session.gc_maxlifetime', '172800');    //设置session清除时间20天。60*60*24*20=172800。
  ini_set('session.save_path', $session_save_path);
  ini_set('date.timezone', 'Asia/Shanghai');

  session_start();
}

header('Content-Type: text/html; charset=UTF-8');
//header('Conteindexnt-Type: text/plain; charset=UTF-8');
//header('Content-Type: text/event-stream; charset=UTF-8');
header('access-control-allow-origin: *');
// error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE);
$__BASE_PATH__ = rtrim(realpath(__FILE__), '/');
$__BASE_DIR__ = realpath(substr($__BASE_PATH__, 0, strrpos($__BASE_PATH__, '/') + 1) . '/../');

define('__BASE_DIR__', $__BASE_DIR__);                                              //根地址
define('__APP_DIR__', realpath($__BASE_DIR__ . '/app'));                            //App路径
define('__CONTROLLER_DIR__', realpath($__BASE_DIR__ . '/app/Controller') . "/");    //Controller路径
define('__Service_DIR__', realpath($__BASE_DIR__ . '/app/Service') . "/");          //Service路径
define('__MODEL_DIR__', realpath($__BASE_DIR__ . '/app/Model') . "/");              //Model路径
define('__VIEW_DIR__', realpath($__BASE_DIR__ . '/app/Views') . "/");               //View路径
define('__COMMON_DIR__', realpath($__BASE_DIR__ . '/app/Common') . "/");            //工具类路径
define('__CONF_DIR__', realpath($__BASE_DIR__ . '/app/Config') . "/");              //数据库配置路径

define('__LIB_DIR__', realpath($__BASE_DIR__ . '/app/Lib/'));                       //三方库路径
define('__WX_DIR__', realpath($__BASE_DIR__ . '/wx/'));                             //微信路径
define('__WX_CERT_DIR__', realpath($__BASE_DIR__ . '/wx/cert/'));                   //微信证书路径

define('__PUBLIC_DIR__', realpath($__BASE_DIR__ . '/public/'));                     //开放路径
define('__ERRLOG__', '/var/log/php.njzs-wechat.log');                               //错误文件

require __APP_DIR__ . '/app.php';

\vd($_SERVER,'$_SERVER');

try {
    \app\Engine::run();
} catch (\Exception $error) {
    echo '{"code":' . $error->getCode() . ',"message":"' . $error->getMessage() . '","result":[]}';
    exit();
}


// function _in_data($data)
// {
//     $data = str_replace(array(';', ' and ', ' or '), array('[;]', ' [and] ', ' [or] '), $data);

//     return $data;
// }

// function _out_data($data)
// {
//     $data = str_replace(array('[;]', '[and]', '[or]'), array(';', 'and', 'or'), $data);

//     return $data;
// }


//unset($_GET['debug']);
if (isset($_GET['debug'])) {
    vd($_GET, 'get');
    vd($_POST, 'post');
    // vd($_SESSION,'session');

    // $sqls=explode("\n",\M\DB::get()->log());
    // foreach($sqls as &$sql){
    //     $sql=substr($sql,32);
    // }
    // $sqlstr=implode("\n",$sqls);
    // if(isset($_GET['debug'])) echo '<pre style="text-align:left;">'. $sqlstr .'</pre>';
}
