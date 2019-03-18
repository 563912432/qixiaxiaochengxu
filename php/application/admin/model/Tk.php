<?php

namespace app\admin\model;

use think\Model;
use app\admin\model\Subject as SubjectModal;


class Tk extends Model
{
    // 表名
    protected $name = 'tk';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['1' => __('Type 1'),'2' => __('Type 2'),'3' => __('Type 3')];
    }     


    public function getTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function subject()
    {
        return $this->belongsTo('Subject', 'subject_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function getSubjectList($index = null)
    {
      $list = SubjectModal::all();
      $arr = [];
      if ($list) {
        foreach ($list as $key =>$value)
        {
          $arr[$value['id']] = $value['title'];
        }
      }
      if ($index == null){
        array_key_exists($index, $arr) ? $arr[$index]: $arr;
      }
      return $arr;
    }
}
