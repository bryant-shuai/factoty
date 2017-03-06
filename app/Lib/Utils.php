<?php

function datetime()
{
  return date('Y/m/d H:i:s');
}

function errlog($err)
{
    \error_log("\n" . print_r($err, true) . "\n", 3, __ERRLOG__);
}

function mkarr($v, $k)
{
    if (empty($v[$k])) {
        $v[$k] = [];
    }
}

function err($res)
{
    if ($res && !empty($res['err'])) {
        return true;
    }
    return false;
}

function en($arr)
{
    return json_encode($arr, JSON_UNESCAPED_UNICODE);
}

function toMap($objs)
{
    $r = [];
    foreach ($objs as $k => $v) {
        $r[$k] = $v->data;
    }
    return $r;
}

function toList($objs)
{
    $r = [];
    foreach ($objs as $k => $v) {
        $r[] = $v->data;
    }
    return $r;
}

function except($code, $msg = null)
{
    if (null === $msg) $msg = \CODE::$MSG[$code];
    throw new \Exception($msg, $code);
}

function dft(&$arr, $k, $v)
{
    if (empty($arr[$k])) {
        $arr[$k] = $v;
    }
}

function ppp($arr, $text = null)
{
    if (null !== $text) echo '<hr />' . $text . '<br />';
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

function needArgs($arr, $needs, $canBeNull = 'can_not_be_null')
{
    $code = \CODE::PARAMETER_ERROR;
    $err = false;
    foreach ($needs as $k => $v) {
        if (!isset($arr[$v])) {
            $err = true;
        } else if (trim($arr[$v]) == '' && $canBeNull !== 'can_be_null') { // && $canBeNull!=='can_be_null'
            $err = true;
        }
        if ($err) {
            $e = new \Exception(\CODE::$MSG[$code] + ':' + $v, $code);
            // print_r($e);
            throw $e;
        }
    }
    return true;
}

function parseArgs(&$target, $arr, $needs, $canBeNull = 'can_not_be_null')
{
    \vd($needs, 'needs');
    \vd($arr, 'arr');

    $code = \CODE::PARAMETER_ERROR;
    $err = false;
    foreach ($needs as $k => $v) {
        if (!isset($arr[$v])) {
            $err = true;
        } else if (trim($arr[$v]) == '' && $canBeNull !== 'can_be_null') { // && $canBeNull!=='can_be_null'
            $err = true;
        }
        if ($err) {
            $e = new \Exception(\CODE::$MSG[$code] + ':' + $v, $code);
            // print_r($e);
            throw $e;
        }
        $target[$v] = $arr[$v];
    }
    \vd($target, '$target');
    return $target;
}

function de($str)
{
    try {
        $de = json_decode($str, true);
    } catch (\Exception $e) {
        $de = false;
    }
    return $de;
}

function arrRmEmpty($array)
{
    $array = array_filter($array, create_function('$v', 'return !empty($v);'));
    return $array;
}

function arrRmDup($array)
{
    $arr = [];
    foreach ($array as $k => $v) {
        $arr[$v] = $v;
    }
    return array_values($arr);
}

function addCacheKey($key)
{
    global $cache_keys;
    if (isset($cache_keys[$key])) {
        exit('添加了重复的key');
    }
    $cache_keys[$key] = $key;
}

function parseCacheKey($key, $args)
{
    global $cache_keys;
}


//unset($_GET['debug']);
if (isset($_GET['debug'])) {
    // echo '--------------------';
    // vd($_GET, 'get');
    // vd($_POST, 'post');
    // vd(\meedo::$SQLS, 'sqls');
    // vd($_SESSION,'session');


    // $sqls=explode("\n",\meedo::$SQLS);
    // foreach($sqls as &$sql){
    //     $sql=substr($sql,32);
    // }
    // $sqlstr=implode("\n",$sqls);
    // if(isset($_GET['debug'])) echo '<pre style="text-align:left;">'. $sqlstr .'</pre>';
}

///////////////////////////////////////////////////////// 打印调试堆栈用

/**
 * 打印调试，能显示相关位置，避免打多了不知道去哪删.
 */
function vdx($v, $str = '', $printobject = false)
{
    vd($v, $str, $printobject);
    exit('end');
}

global $_idx;
$_idx = 0;
function vd($v, $str = '', $printobject = false)
{
    global $_idx;
    // if (__MODE__ !== 'dev') {
    //     return;
    // }
    if (!isset($_GET['debug'])) {
        return;
    }

    ++$_idx;
    if ($_idx < 2) {
        echo '<style>
        *{
            // font-size:36px;
        }
        xmp, pre, plaintext {
          display: block;
          font-family: -moz-fixed;
          white-space: pre;
          margin: 1em 0;

          white-space: pre-wrap;
          word-wrap: break-word;

        }
        table
        {
            border-color: #600;
            border-width: 0 0 1px 1px;
            border-style: solid;
        }

        td
        {
            border-color: #600;
            border-width: 1px 1px 0 0;
            border-style: solid;
            margin: 0;
            padding: 4px;
            background-color: #FFC;
        }

        </style>';
        echo '
        <script src="/lib/jquery.js"></script>
        <script>
        var onoff = function(idx){
            // alert(idx)
            $("#"+idx).toggle()
        }
        </script>
        ';
    }

    // if( empty($_GET['debug']) ) return;
//    if(!isset($_GET['debug'])) return ;
    global $application_folder;
    $trace = $backtrace = debug_backtrace();
    $line = 0;
    $file = '';
    $filepath = null;

    // echo '<pre>';print_r($trace);echo '</pre>';
    $his = [];
    $tmp_file = null;
    $tmp_line = null;
    foreach ($trace as $a => $b) {
        if (!empty($b['file'])) {
            $file = $b['file'];
            if (!$filepath) {
                $filepath = $file;
            }
        }
        // if( isset($b['line']) && !$line ) $line = $b['line'];

        $trace[$a]['file'] = $file;
        if (isset($b['file']) && strrpos($b['file'] . ',', 'index.php,') > 0) {
            // $file = $b['file'];
            array_shift($trace);
        } else {
            if (isset($b['line']) && !$line) {
                $line = $b['line'];
            }
        }
        unset($trace[$a]['args']);
        if (!$printobject) {
            unset($trace[$a]['object']);
        }
        unset($b['args']);
        unset($b['object']);
        // echo '<br>'.print_r($b,true).'<br>';

        if ($tmp_file) {
            $his[] = [
                'file' => $tmp_file,
                'line' => $tmp_line,
                'class' => $b['class'],
                'function' => $b['function'],
            ];
        }

        $tmp_file = $b['file'];
        $tmp_line = $b['line'];
    }

    if (empty($trace[0]['class'])) {
        $trace[0]['class'] = '';
    }
    if (empty($trace[1]['class'])) {
        $trace[1]['class'] = '';
    }
    if (empty($trace[0]['function'])) {
        $trace[0]['function'] = '';
    }
    if (empty($trace[2]['class'])) {
        $trace[2]['class'] = '';
    }

    echo '<hr /><pre style="text-align: left;">';
    echo '<p onClick="onoff(\'idx_' . $_idx . '\')">' . substr($filepath, strlen(__BASE_DIR__) + 4) . ':<font color="red">' . $line . '</font> ' . $trace[0]['class'] . '::' . $trace[0]['function'] . ' <b> ' . $str . '</b></p>';

    $__content_v__ = $v;
    if ($__content_v__ === null) {
        $__content_v__ = '<font color="blue"><b>NULL</b></font>';
    }

    echo '<table id="idx_' . $_idx . '" style="display:none;margin-top:-10px;margin-bottom:20px;"><tr><td>file</td><td>class</td><td>function</td><td>line</td></tr>';
    foreach ($his as $k => $v) {
        $filepath = $v['file'];
        echo '
        <tr>
            <td>' . substr($filepath, strlen(__BASE_DIR__) + 5) . '</td>
            <td>' . $v['class'] . '</td>
            <td>' . $v['function'] . '</td>
            <td>' . $v['line'] . '</td>
        </tr>
        ';
    }
    echo '</table>';

    print_r($__content_v__);

    echo '<br></pre><br>';
}

function vds($v)
{
    foreach ($v as $a => $b) {
        vd($b->stored);
    }
}