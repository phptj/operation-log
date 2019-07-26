<?php
namespace TangJun\SysAuditLog\Repositories;


use TangJun\SysAuditLog\Services\clientService;
use Auth;
use TangJun\SysAuditLog\Entities\SysAuditLog;

class SysAuditLogRepository {


    /**
     * 记录用户操作日志
     * @param $data
     * @param $type
     * @return bool
     */
    public function createActionLog($data,$type)
    {
        switch($type){
            case 'update':
                self::handleUpdate($data,$type);
                break;
            case 'delete':
                self::handleDelete($data,$type);
                break;
            default:
                break;
        }

        return true;
    }

    /**
     * 处理更新日志
     * @param $data
     * @param $type
     * @return bool
     */
    private function handleUpdate($data,$type){
        $sysAuditModel = new SysAuditLog;
        $insertArray = [];
        $userInfo = session('user_info');
        $vendorId = session('aDecryptVendorId');
        if(isset($userInfo->id)){
            $insertArray['create_id'] = $userInfo->id;
        }elseif(empty($vendorId)){
            $insertArray['create_id'] = $vendorId;
        }

        $tableFunction = function() {return $this->table;};
        $table = $tableFunction->call($data);

        $attributesFunction = function() {return $this->attributes;};
        $attributes = $attributesFunction->call($data);

        $originalFunction = function() {return $this->original;};
        $original = $originalFunction->call($data);

        $insertArray['table_name'] = !empty($table)?$table:'';
        $attributes = !empty($attributes)?$attributes:[];
        $original = !empty($original)?$original:[];

        $updateArray = self::checkAttributesChange($attributes,$original);

        if(!empty($updateArray) && is_array($updateArray)){
            foreach ($updateArray as $key => $val){
                $insertArray['action'] = $type;
                $insertArray['key_column'] = $val['key_column'] ;
                $insertArray['key_id'] = $val['key_id'] ;
                $insertArray['orig_value'] = $val['orig_value'];
                $insertArray['new_value'] = $val['new_value'];
                $insertArray['create_batch_mark'] = $val['create_batch_mark'];
                $insertArray['create_time'] = time();
                $sysAuditModel->create($insertArray);
            }
        }
        return true;
    }

    /**
     * 处理删除日志
     * @param $data
     * @param $type
     * @return bool
     */
    private function handleDelete($data,$type){
        $sysAuditModel = new SysAuditLog;
        $insertArray = [];
        if(Auth::guard('admin')->user()){
            $adminId = @Auth::guard('admin')->user()->id;
            $insertArray['create_id'] = self::getCreateId($adminId);
        }else{
            $insertArray['create_id'] = 0;
        }
        $insertArray['table_name'] = !empty($data['table'])?$data['table']:'';
        $insertArray['action'] = $type;
        $insertArray['key_column'] = '';
        $insertArray['key_id'] = !empty($data['attributes']['id'])?$data['attributes']['id']:0;
        $insertArray['orig_value'] = '';
        $insertArray['new_value'] = '';
        $insertArray['create_batch_mark'] = microtime(true);
        $insertArray['create_time'] = time();
        $sysAuditModel->create($insertArray);
        return true;
    }

    /**
     * 获取修改者的vendor_id
     * @param $adminId
     * @return int
     */
    private function getCreateId($adminId){
        return $adminId;
    }

    /**
     * 检查哪些字段被修改
     * @param $attributes
     * @param $original
     * @return array
     */
    private function checkAttributesChange($attributes,$original){
        if(empty($attributes) && !is_array($attributes)){
            return [];
        }
        if(empty($original) && !is_array($original)){
            return [];
        }

        $updateArray = [];

        $createBatchMark = microtime(true);

        foreach ($attributes as $key => $val){
            //创建时间、更新时间、删除时间字段值的变更不记录
            if(in_array($key,['created_at','updated_at','deleted_at'])){
                continue;
            }
            if(isset($original[$key]) && $val != $original[$key]){
                $updateArray [] = [
                    'key_column'=>$key,
                    'key_id'=>$original['id'],
                    'new_value'=> $val,
                    'orig_value'=> $original[$key],
                    'create_batch_mark'=>$createBatchMark,
                ];
            }
        }
        return $updateArray;
    }


}