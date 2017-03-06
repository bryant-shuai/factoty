<?php
namespace Controller;

use app\Controller;
use Error\ErrorCode;

class sync extends Controller
{
    public function test()
    {
        $this->di['SyncService']->dealItemsFromLocal();
    }

    //同步密码
    public function save_items_from_local()
    {
      $syncService = $this->di['SyncService'];
      \vd($_POST,'_POST_POST_POST_POST_POST');
      $items = \de($_POST['items']);
      \vd($items,'items');

      //获得本地推送过来的数据
      $syncService->saveItemsFromLocal($items);
      $syncService->getDealedItemsFromLocal();
      $syncService->dealItemsFromLocal();
      

      $this->data([
        'dealed_ids'=>$syncService->dealed_ids,
      ]);
    }



    //被拉
    public function get_items_from_remote()
    {
      $syncService = $this->di['SyncService'];
      \vd($_POST,'_POST_POST_POST_POST_POST');

      //获得上次微信端推过去的处理结果
      $syncService->getDealedItemsFromLocal();
      
      //将新的微信和帐号数据推给本地
      $syncService->sendItemsToLocal();


      $this->data([
        'remote_items'=>$syncService->remote_items,
        'dealed_ids'=>$syncService->dealed_ids,
      ]);
    }

    public function push_state_to_remote() {
      $syncService = $this->di['SyncService'];
      \vd($_POST,'_POST_POST_POST_POST_POST');

      $ids = \de($_POST['dealed_pullremote_ids']);
      $syncService->setSyncStatusToLocal($ids);

      $this->data([
        'ok'=>1,
        'dealed_ids'=>$syncService->pull_dealed_ids,
      ]);

    }

    public function sms() {
        // error_log("\n>>query\n" . $query . "\n\n\n", 3, '\');
    }

    public function products() {
      $products = $this->di["ProductService"]->queryWithPrice();
      $list = array();
      foreach ($products as $p) {
        $p['sku'] = $p['id'];
        $list[] = $p;
      }
      $this->data([
          'products' => $list,
      ]);
    }
}
