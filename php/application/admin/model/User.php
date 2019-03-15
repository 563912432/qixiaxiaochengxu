<?php

namespace app\admin\model;

use think\Model;
use app\admin\model\Branch as BranchModel;

class User extends Model
{
    // 表名
    protected $name = 'user';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'gender_text'
    ];
    

    
    public function getGenderList()
    {
        return ['1' => __('Gender 1'),'2' => __('Gender 2')];
    }     


    public function getGenderTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['gender']) ? $data['gender'] : '');
        $list = $this->getGenderList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getBranchList($index = null)
    {
      $list =  BranchModel::all();
      $arr = [];
      if ($list) {
        foreach ($list as $key => $val) {
          $arr[$val['id']] = $val['title'];
        }
      }
      if ($index == null) {
        array_key_exists($index, $arr) ? $arr[$index]: $arr;
      }
      return $arr;
    }


    public function branch()
    {
        return $this->belongsTo('Branch', 'branch_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
