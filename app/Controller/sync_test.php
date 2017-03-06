<?php

namespace Controller;

use App\Controller;
use Error\ErrorCode;
use Log\LogConfig;

class sync_test extends Controller
{

  function client() {
    $id = $_GET['id'];
    $this->di['SyncHandleService']->sync_client($id);
    $this->data(['ok'=>1]);
  }



  function deal_test() {
    $res = \de('{"id":"2","client_id":"1","code":"100","amount":"10.00","operator":"1","create_at":"1470134330","update_at":"0","delete_at":"0","deleted":"0","extra":"{"金额调整":"+10","客户":"沈阳天山路店","添加前金额":"350.00","修改后金额":360}","manual":"1"}');
    \vd($res);
    exit();
    $this->di['SyncService']->dealItemsFromLocal();
  }



  function deal_client_byid() {
    $id = $_GET['id'];
    $oSyncFromLocal = \Model\SyncFromLocal::loadObj($id);
    // $type = $oSyncFromLocal
    $data = $oSyncFromLocal->data;
    $clientData = \de($data['data']);
    \vd($data);
    $id = $clientData['id'];
    $type = $data['type'];
    $this->di['SyncHandleService']->_deal_client('',$id,$clientData,$type);
  }

}