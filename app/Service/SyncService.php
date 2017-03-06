<?php

namespace Service;

use App\Service;

class SyncService extends Service
{
    // public $received_ids = [];
    public $dealed_ids = [];
    public $remote_items = [];
    public $pull_dealed_ids = [];


    function saveItemsFromLocal($items)
    {
        $ids = [];
        if (!$items) return;
        foreach ($items as $k => $item) {
            $ids['' . $item['id']] = $item['id'];
        }
        $idsstr = implode(',', $ids);

        //获得数据
        $finds = \Model\SyncFromLocal::finds('where id IN (' . $idsstr . ') ', '*');
        $findsMap = [];
        \vd($finds,'$finds');
        foreach ($finds as $k => $v) {
            if (((int)$v['sync_status']) > 0) {
                $findsMap['' . $v['id']] = $v['id'];
            }
        }

        \vd($findsMap, '$findsMap');


        foreach ($items as $k => $item) {
            // $item = [
            //   'id' => $id,
            //   'key' => 'key:'.$id,
            //   'data' => '{"test":1}',
            //   'type' => 'edit',
            // ];
            $status = 0;

            //判断是否添加过，如果添加过，忽略
            // $find = \Model\SyncFromLocal::loadObj($item['id']);
            // if(!$find){
            if (empty($findsMap['' . $item['id']])) {
                $sql = 'REPLACE INTO njzs_sync_from_local SET
          `key`=\'' . $item['key'] . '\',
          `data`=\'' . str_replace("\\","\\\\",$item['data'])  . '\',
          `type`=\'' . $item['type'] . '\',
          `sync_status`=' . $status . ',
          `id`=' . $item['id'] . ' ';
                \vd($sql);
                \Model\SyncFromLocal::sqlQuery($sql);
            }

        }
    }

    function getDealedItemsFromLocal()
    {
        //获得最近1小时更新成功的id，返回
        $items = \Model\SyncFromLocal::finds('where sync_status=9  order by id desc limit 100 ', '*');
        //如果已经处理，直接返回
        // \vd($items);
        foreach ($items as $v) {
            // \vd($v);
            $this->dealed_ids['' . $v['id']] = $v['id'];
        }
        // \vd($this->dealed_ids,'$this->dealed_ids$this->dealed_ids$this->dealed_ids$this->dealed_ids');
    }


    function setSyncStatusToLocal($ids)
    {
        \Model\SyncToLocal::update(['id' => $ids], ['sync_status' => 9]);
        $this->pull_dealed_ids = $ids;
    }

    //处理新获取的信息
    function dealItemsFromLocal($limit = 50)
    {
        $items = \Model\SyncFromLocal::finds('where sync_status=0 order by id asc limit ' . $limit . ' ', '*');
        $ids = [];
        foreach ($items as $v) {
            $ids[] = $v['id'];
        }

        //标记正在处理
        \Model\SyncFromLocal::update(['id' => $ids], ['sync_status' => 1]);

        //如果已经处理，直接返回
        \vd($items);
        foreach ($items as $v) {
            \vd($v);
            $keys = explode(':', $v['key']);
            $mod = $keys[0];
            $id = $keys[1];
            $data = \de($v['data']);
            $type = $v['type'];
            \vd("+++++++++++++++++++++++++++");
            \vd($v,'$v');
            \vd($id,'$id');
            \vd($data,'$data');
            \vd($type,'$type');

            $method = '_deal_' . $mod;
            $r = $this->di['SyncHandleService']->$method($v['id'], $id, $data, $type);
            // if($r) {
            //   \Model\SyncFromLocal::update(['id'=>$id], ['sync_status'=>9]);
            // }
        }

        \Model\SyncFromLocal::update(['id' => $ids], ['sync_status' => 9]);

        foreach ($ids as $k => $v) {
            $this->dealed_ids['' . $v] = $v;
        }

    }


















    // //获得上次微信端推过去的处理结果
    // function dealItemsSyncResultToLocal() {
    //   // $items = \Model\SyncToLocal::finds('where sync_status=0 limit '.$limit.' ', '*');
    //   // $this->remote_items = $items;

    //   //把本地处理完的标记成9
    // }


    //将新的微信和帐号数据推给本地
    function sendItemsToLocal($limit = 50)
    {
        //取为0 的，处理，结果改成1
        $items = \Model\SyncToLocal::finds('where sync_status=0 order by id ASC limit ' . $limit . ' ', '*');

        $this->remote_items = $items;
        return;

    }


}
