<?php
namespace App;

require_once __CONF_DIR__ . '/Config.php';
require_once __CONF_DIR__ . '/DbConfig.php';
require_once __CONF_DIR__ . '/WxConfig.php';
require_once __COMMON_DIR__ . '/ErrorCode.php';
require_once __COMMON_DIR__ . '/Tools.php';
require_once __LIB_DIR__ . '/medoo/meedo.php';

//require_once __APP_DIR__ . '/Config/Error.php';
//require_once __APP_DIR__ . '/Config/Code.php';
require_once __LIB_DIR__ . '/Utils.php';

use ArrayAccess;
use DB\DbConfig;
use Error\ErrorCode;
use Error\ErrorObject;
use Wxpay\Log;


//function zaddslashes(&$string, $force = 0, $strip = FALSE)
//{
//    if (is_array($string)) {
//        foreach ($string as $key => $val)
//        {
//            $string[$key] = zaddslashes($val, $force, $strip);
//        }
//    }
//    else
//    {
//        //$string = ($strip ? stripslashes($string) : $string);
//        $string = addslashes($string);
//    }
//    return $string;
//}
//
//zaddslashes($_GET);
//zaddslashes($_POST);


class Engine
{
    public static $instance = null;

    public static function route()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uris = explode('?', $uri);
        $argsStr = "";
        if (count($uris) > 1) {
            $argsStr = $uris[1];
        }
        $path = $uris[0];
        $paths = explode('/', $path);

        //$controller = 'index';        //暂时设置默认主控制器
        $controller = 'index';
        $action = 'index';
        if (!empty($paths[1])) {
            $controller = $paths[1];
        }
        if (!empty($paths[2])) {
            $action = $paths[2];
        }
        return [$controller, $action, [$argsStr]];
    }

    public static function run()
    {
        $route = self::route();
        $controllerName = '\\Controller\\' . $route[0];
        $actionName = $route[1];
        try {
            $controller = new $controllerName();
            $controller->$actionName();
            if (method_exists($controller, "render")) {
                $controller->render();
            }
        } catch (ErrorObject $error) {
            echo '{"code":' . $error->getCode() . ',"message":"' . $error->getMessage() . '","result":[]}';
        }
    }
}

class Di implements ArrayAccess
{
    public static $instance = null;
    private $container = [];

    public function __construct()
    {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = $this;
        return $this;
    }

    public static function get()
    {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new self();

        return self::$instance;
    }

    public function offsetSet($offset, $value = null)
    {
        $this->container[$offset] = $value;
    }

    public function offsetGet($offset)
    {
        if (!isset($this->container[$offset])) {
            $service = '\\Service\\' . $offset;
            $this->container[$offset] = new $service();
        }

        return $this->container[$offset];
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
}

//controller
class Controller
{
    //页面渲染需要的数据访问的数据
    protected $para = [];

    private $data = [];
    public $view;
    //private $code = 0;
    //private $message = "";
    private $content = [];

    //private $pattern = "/[^0-9.]/u";     //匹配除(数字.)之外的字符
    //private $pattern = "/[^a-zA-Z0-9.]/u";     //匹配除(数字字母.)之外的字符
    //private $pattern = "/[^a-zA-Z0-9.\/]/u";     //匹配除(数字字母./)之外的字符
    //private $pattern = "/[^\w.\-\/\x{4e00}-\x{9fa5}]/u";     //匹配除(字母数字_.-/汉字)之外的字符
    private $pattern = "/[^\w.,:\"\-\/\x{4e00}-\x{9fa5}\[\]\{\}]/u";    //匹配除(字母数字_.,:"-/汉字[]{})之外的字符

    //导入Di
    public function __construct()
    {
        $this->di = Di::get();

        //设置Client信息
        if (isset($_SESSION["CLIENT"])) {
            $this->para["client"] = $_SESSION["CLIENT"];
        }
    }

//------------------------页面式
    //渲染方法
    public function render()
    {
        if ($this->view) {
            //若指定了要渲染的view
            $para = $this->para;
            include_once __VIEW_DIR__ . $this->view . ".php";
        } else {
            //否则返回接口数据
            $this->echoJson();
        }
    }

    /**
     * 验证请求方式
     */
    protected function verifyPost()
    {
        if ($_SERVER['REQUEST_METHOD'] != "POST" || count($_POST) == 0) {
            throw new ErrorObject(ErrorCode::REQUEST_METHOD);
        }
        return true;
    }

    /**
     * 取指定数据
     * @param $method string [GET|POST] 请求方法名字:GET, POST[不区分大小写]
     * @param $keys array 要获取的参数名字
     * @param bool $block 若未发现指定参数是否阻断执行并报参数错误[会结束执行并返回]
     * @return array 获取的参数, 关联数组
     * @throws ErrorObject
     */
    protected function fetchData($method, $keys, $block = true)
    {
        $method = strtoupper($method);
        if ($method == 'POST') {
            $method = $_POST;
        } elseif ($method == 'GET') {
            $method = $_GET;
        } else {
            if ($block) {
                throw new ErrorObject(ErrorCode::REQUEST_METHOD);
            }
        }

        $data = [];
        foreach ($keys as $key) {
            if (isset($method[$key]) && is_array($method[$key])) {
                $data[$key] = $method[$key];
            } elseif (isset($method[$key])) {
                $data[$key] = preg_replace($this->pattern, "", $method[$key]); //过滤除(数字字母.)之外的字符
                //$data[$key] = stripslashes($method[$key]);
            } elseif ($block) {
                throw new ErrorObject(ErrorCode::PARAMETER_ERROR);
            }
        }

        return $data;
    }
//------------------------

//------------------------接口式
    //返回JSON信息
    public function echoJson()
    {
        $this->content['code'] = 0;
        $this->content['message'] = "";
        $this->content['result'] = $this->data;
        echo json_encode($this->content, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 调用echoJson,返回data信息
     * @param array $data
     */
    public function data($data = [])
    {
        $this->data = $data;
        $this->echoJson();
        exit();
    }
//------------------------

}

//Model
class Model
{
    public static $table = '';
    public static $db = null;
    public static $cache = [];
    public static $key = 'id';
    public $id = null;
    public $di = null;
    public $data = [];
    public $column_key = null;
    public $column_value = null;


    public function __construct()
    {
        $this->di = di::get();
    }

    //数据库连接
    public static function connect()
    {
        if (!self::$db) {
            self::$db = new \medoo(DbConfig::$mysql);
        }
        return self::$db;
    }

    public function __set($name, $value)
    {
        // echo "Setting '$name'\n";
        $this->callMethod('parse');
        $this->callMethod('_set_' . $name);
        if (!isset($this->$name)) {
            $this->$name = $value;
        }
        return $this;
    }

    public function __get($name)
    {
        // echo "Getting '$name'\n";
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        // echo "Setting '$name'\n";

        $this->callMethod('parse');
        $this->callMethod('_set_' . $name);

        if (isset($this->$name)) return $this->$name;
        return null;
    }


    public function callMethod($method)
    {
        if (is_callable(array($this, $method))) {
            // echo "Call '$method'\n";
            $this->$method();
        }
    }


    public static function loadObjByData($data = [], $column_key = null, $column_value = null)
    {
        $obj = new static();
        $obj->data = $data;

        if (!$column_key) $column_key = 'id';
        $obj->column_key = $column_key;

        if (!$column_value) $column_value = $obj->data['id'];
        $obj->column_value = $column_value;

        return $obj;
    }


    public static function loadObj($id = 0, $key = null)
    {
        if (!$key) $key = static::$key;
        if (!$key) $key = 'id';

        if ($key == 'id') {
            $keystr = static::$table . '_' . $key . '_' . $id;
            if (!empty(self::$cache[$keystr])) {
                return self::$cache[$keystr];
            }
        }

        self::connect();
        $r = self::$db
            ->get(static::$table, '*', [
                '' . $key => $id,
            ]);
        \vd($r, 'rrrrrrrrrrrrrrrrrrrrrrrrrrrr');
        $obj = new static;
        $obj->column_key = $key;
        $obj->column_value = $id;
        // foreach ($r as $k => $v) {
        //     $obj->data[$k] = $v;
        // }
        if ($r) {
            $obj->data = $r;
            $obj->column_value = $id;
            if ($key == 'id') self::$cache[$keystr] = $obj;
            return $obj;
        }
        return null;
    }

    public function add()
    {
        return static::insert($this->data);
    }

    public function save($data = [], $param = ['key' => null, 'value' => null])
    {
        // \vd($data, 'data');
        self::connect();
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }

        \vd($this->data, '$this->data');

        // \vd($this->column_key, '$this->column_key');
        // \vd($this->column_value, '$this->column_value');

        if (empty($this->column_key) && empty($this->column_value)) {
            $id = static::insert($this->data);
            \vd($id, '$id');
            if ($id !== false) {
                $this->id = $id;
                $this->data['id'] = $id;
                $this->column_key = 'id';
                $this->column_value = $id;
                // $keystr = static::$table . '_' . $this->column_key . '_' . $id;

//                return $result;
            } else {
                // throw new ErrorObject(ErrorCode::LOG_QUERY_FAIL);

            }
            $this->column_key = $param['key'];
            $this->column_value = $param['value'];
            return $this;
        }

        \vd($this->data, '$this->data');
        $r = self::$db->update(static::$table, $this->data, [
            '' . $this->column_key . '' => $this->column_value
        ]);

        if (!empty($data['deleted']) && $data['deleted'] == '1') {
            $this->clearcache();
        }
        return $this;
    }

    public static function deleteById($id)
    {
        self::connect();
        return static::delete(["id" => $id]);
    }

    public static function load($v = 0, $key = 'id')
    {
        // $keystr = static::$table . '_' . $key . '_' . $v;
        // if (!empty(self::$cache[$keystr])) {
        //     return self::$cache[$keystr];
        // }
        self::connect();
        $r = self::$db
            ->get(static::$table, '*', [
                $key => $v,
            ]);
        \vd($r, 'r');

        return $r;
    }

    public function clearcache()
    {
        if (!empty($this->data['id'])) {
            $id = $this->data['id'];
            $keystr = static::$table . '_' . $this->column_key . '_' . $id;
            if (isset(self::$cache[$keystr])) {
                self::$cache[$keystr] = null;
                unset(self::$cache[$keystr]);
            }
        }
    }

    public function rm($force = 0)
    {
        if ($force === 'forcedelete') {
            if ($this->column_value && $this->column_key) {
                self::$db->delete(static::$table, [$this->column_key => $this->column_value]);
                $this->data = null;
            }
        } else {
            $this->data['deleted'] = 1;
            $this->save();
        }
        $this->clearcache();
    }


    public static function count($where = [])
    {
        self::connect();

        return self::$db->count(static::$table, $where);
    }

    public static function sum($where = [], $column)
    {
        self::connect();

        return self::$db->sum(static::$table, $column, $where);
    }


    public static function sqlQuery($sql)
    {
        self::connect();

        $data = self::$db->query($sql);
        if (is_numeric($data) || is_bool($data)) {
            return $data;
        } else {
            return $data->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

    public static function execSql($sql1, $sql2)
    {
        $prefix = DbConfig::$mysql['prefix'];
        $sql = $sql1 . ' ' . $prefix . static::$table . ' ' . $sql2;
        return static::sqlQuery($sql);
    }

    public static function execCount($sql)
    {
        $prefix = DbConfig::$mysql['prefix'];
        $count = Log::sqlExec("SELECT count(*) as count from " . $prefix . static::$table . " WHERE $sql ");
        if ($count && is_array($count) && count($count) > 0 && isset($count[0]['count'])) {
            $count = $count[0]['count'];
            return $count;
        }
        return 0;
    }

    public static function log()
    {
        return self::$db->log();
    }


    public static function find($where = '', $column = '*')
    {
        $data = self::finds($where, $column);

        return count($data) <= 0 ? [] : $data[0];
    }

    public static function finds($where = '', $columns = '*')
    {
        self::connect();
        $prefix = \DB\DbConfig::$mysql['prefix'];
        $sql = 'SELECT ' . $columns . ' FROM ' . $prefix . static::$table . ' ' . $where;
        $tmp = self::$db->query($sql);
        if ($tmp) {
            $data = $tmp->fetchAll();
        } else {
            $data = [];
        }

        return count($data) <= 0 ? [] : $data;
    }


    /**
     * 数据库查询
     * @param $column
     * @param array $where
     * @return mixed
     */
    public static function query($column, $where = [])
    {
        self::connect();
        return self::$db->select(static::$table, $column, $where);
    }

    /**
     * 数据库join查询
     * @param array $where
     * @param array $column
     * @param array $join
     * @return mixed
     */
    public static function queryJoin($where = [], $column = [], $join = [])
    {
        self::connect();
        return static::$db->get(static::$table, $join, $column, $where);
    }

    /**
     * 数据库插入
     * @param array $data
     * @return mixed
     */
    public static function insert($data = [])
    {
        self::connect();
        return self::$db->insert(static::$table, $data);
    }

    /**
     * 数据库更新
     * @param array $where
     * @param array $data
     * @return mixed
     */
    public static function update($where = [], $data = [])
    {
        self::connect();
        $result = self::$db->update(static::$table, $data, $where);
        return $result;
    }

    /**
     * 数据库删除,不建议使用,建议使用假删除
     * @param array $where
     * @return mixed
     */
    public static function delete($where = [])
    {
        self::connect();
        return self::$db->delete(static::$table, $where);
    }

    /**
     * sql语句执行
     * @param $sql
     * @return mixed
     */
    public static function sqlExec($sql)
    {
        self::connect();
        $data = self::$db->query($sql);
        if ($data) {
            return $data->fetchAll();
        } else {
            return false;
        }
    }
}

//Service
class Service
{
    public function __construct()
    {
        $this->di = Di::get();
    }
}

//设置Loader
class Loader
{
    public static function __autoloader($class)
    {
        $splits = explode('\\', $class);
        $class = implode('/', $splits);
        $filename = __APP_DIR__ . '/' . $class . '.php';

        if (file_exists($filename)) {
            require_once $filename;

            return true;
        }

        return false;
    }

    public static function __libsloader($class)
    {
        $splits = explode('\\', $class);
        $class = implode('/', $splits);
        $filename = __APP_DIR__ . '/Lib/' . $class . '.php';
        if (file_exists($filename)) {
            require_once $filename;

            return true;
        }

        return false;
    }
}

spl_autoload_register(array('\app\Loader', '__autoloader'));
spl_autoload_register(array('\app\Loader', '__libsloader'));
