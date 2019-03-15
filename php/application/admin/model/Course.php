<?php

namespace app\admin\model;

use think\Model;

class Course extends Model
{
    // 表名
    protected $name = 'course';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'filetype_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getFiletypeList()
    {
        return ['1' => __('Filetype 1'),'2' => __('Filetype 2'),'3' => __('Filetype 3')];
    }     


    public function getFiletypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['filetype']) ? $data['filetype'] : '');
        $list = $this->getFiletypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function subject()
    {
        return $this->belongsTo('Subject', 'subject_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
