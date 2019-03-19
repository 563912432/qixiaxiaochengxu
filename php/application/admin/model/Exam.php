<?php

namespace app\admin\model;

use think\Model;
use app\admin\model\Subject as SubjectModal;

class Exam extends Model
{
    // 表名
    protected $name = 'exam';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];

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

    /**从试卷 info 中获取 试卷的题目id
     * @param $examInfo
     * @return array
     */
    public function getTkAndScoreFromInfo($examInfo){
      $tkIds = [];
      $totalScore = 0;
      if(!empty($examInfo)){
        // 题型:{tm:[],score:[]}
        foreach($examInfo as $info){
          $tkIds = array_merge($tkIds, (array)$info['tid']);
          $totalScore += (intval($info['score']) * count($info['tid']));
        }
      }
      return [$tkIds, $totalScore];
    }
}
