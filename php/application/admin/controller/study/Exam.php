<?php

namespace app\admin\controller\study;

use app\common\controller\Backend;
use app\admin\model\Tk as TkModal;
use function fast\e;

/**
 * 考试管理
 *
 * @icon fa fa-circle-o
 */
class Exam extends Backend
{
    
    /**
     * Exam模型对象
     * @var \app\admin\model\Exam
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Exam;
        // 取科目分类
        $this->view->assign("subjectList", $this->model->getSubjectList());
      }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['subject'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['subject'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $key => $row) {
              if ($row['start'] > time()) {
                $list[$key]['overtime'] = 0;
              } else {
                $list[$key]['overtime'] = 1;
              }
              $list[$key]['start'] = date('Y-m-d H:i:s', $row['start']);
              $list[$key]['end'] = date('Y-m-d H:i:s', $row['end']);
              $list[$key]['timelong'] = $row['timelong'] / 60;
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 添加
     */
    public function add()
    {
      if ($this->request->isPost()) {
        $params = $this->request->post("row/a");
        if ($params) {
          if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
            $params[$this->dataLimitField] = $this->auth->id;
          }
          $params['info'] = [];
          $subject_id = $params['subject_id'];
          // 所选科目下面的题够不够
          $tkModel = model('Tk');
          // 单选 $num, $type, $subject_id
          $danxuanType = $tkModel->typeMap['danxuan'];
          $danxuanTid = $tkModel->autoMakeTiId($params['danxuanNumber'], $danxuanType,$subject_id);
          if (empty($danxuanTid)) {
            $this->error('题库中单选题的数量不足'.$params['danxuanNumber'].'道');
          }
          // 多选 $num, $type, $subject_id
          $duoxuanType = $tkModel->typeMap['duoxuan'];
          $duoxuanTid = $tkModel->autoMakeTiId($params['duoxuanNumber'], $duoxuanType,$subject_id);
          if (empty($duoxuanTid)) {
            $this->error('题库中多选题的数量不足'.$params['duoxuanNumber'].'道');
          }
          // 判断 $num, $type, $subject_id
          $panduanType = $tkModel->typeMap['panduan'];
          $panduanTid = $tkModel->autoMakeTiId($params['panduanNumber'], $panduanType, $subject_id);
          if (empty($panduanTid)) {
            $this->error('题库中判断题的数量不足'.$params['panduanNumber'].'道');
          }
          // 单选
          $params['info'][$danxuanType] = ['type' => $danxuanType, 'score' => $params['danxuanScore'], 'info' => $params['danTypeExplain'], 'num' =>$params['danxuanNumber'],'tid'=>$danxuanTid];
          // 多选
          $params['info'][$duoxuanType] = ['type' => $duoxuanType, 'score' => $params['duoxuanScore'], 'info' => $params['duoTypeExplain'], 'num' =>$params['duoxuanNumber'],'tid'=>$duoxuanTid];
          // 判断
          $params['info'][$panduanType] = ['type' => $panduanType, 'score' => $params['panduanScore'], 'info' => $params['panTypeExplain'], 'num' =>$params['panduanNumber'],'tid'=>$panduanTid];
          // 总分
          list($tk, $totalScore) = $this->model->getTkAndScoreFromInfo($params['info']);
//          pre($tk);
//          pre($totalScore);exit;
          if(empty($tk)){
            $this->error('试卷不能没有题目');
          }
          $params['fullmark'] = $totalScore;
          // 开始时间转为字符戳
          $params['start'] = strtotime($params['start']);
          // 结束时间
          $params['end'] = strtotime($params['end']);
          // 考试时长
          $params['timelong'] = $params['timelong'] * 60;
          $params['info'] = json_encode($params['info']);

          try {
            //是否采用模型验证
            if ($this->modelValidate) {
              $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
              $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
              $this->model->validate($validate);
            }
            $result = $this->model->allowField(true)->save($params);
            if ($result !== false) {
              $this->success();
            } else {
              $this->error($this->model->getError());
            }
          } catch (\think\exception\PDOException $e) {
            $this->error($e->getMessage());
          } catch (\think\Exception $e) {
            $this->error($e->getMessage());
          }
        }
        $this->error(__('Parameter %s can not be empty', ''));
      }
      return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
      $row = $this->model->get($ids);
      $row['start'] = date('Y-m-d H:i:s', $row['start']);
      $row['end'] = date('Y-m-d H:i:s', $row['end']);
      $row['timelong'] = $row['timelong']/60;
      $info = json_decode($row['info'], true);
      $tkModel = model('Tk');
      // 单选 $num, $type, $subject_id
      $danxuanType = $tkModel->typeMap['danxuan'];
      $duoxuanType = $tkModel->typeMap['duoxuan'];
      $panduanType = $tkModel->typeMap['panduan'];
      $row['danxuanNumber'] = $info[$danxuanType]['num'];
      $row['danxuanScore'] = $info[$danxuanType]['score'];
      $row['danTypeExplain'] = $info[$danxuanType]['info'];
      $row['duoxuanNumber'] = $info[$duoxuanType]['num'];
      $row['duoxuanScore'] = $info[$duoxuanType]['score'];
      $row['duoTypeExplain'] = $info[$duoxuanType]['info'];
      $row['panduanNumber'] = $info[$panduanType]['num'];
      $row['panduanScore'] = $info[$panduanType]['score'];
      $row['panTypeExplain'] = $info[$panduanType]['info'];

      if (!$row)
        $this->error(__('No Results were found'));
      $adminIds = $this->getDataLimitAdminIds();
      if (is_array($adminIds)) {
        if (!in_array($row[$this->dataLimitField], $adminIds)) {
          $this->error(__('You have no permission'));
        }
      }
      if ($this->request->isPost()) {
        $params = $this->request->post("row/a");
        if ($params) {
          $params['info'] = [];
          $subject_id = $params['subject_id'];
          // 所选科目下面的题够不够
          $tkModel = model('Tk');
          // 单选 $num, $type, $subject_id
          $danxuanType = $tkModel->typeMap['danxuan'];
          $danxuanTid = $tkModel->autoMakeTiId($params['danxuanNumber'], $danxuanType,$subject_id);
          if (empty($danxuanTid)) {
            $this->error('题库中单选题的数量不足'.$params['danxuanNumber'].'道');
          }
          // 多选 $num, $type, $subject_id
          $duoxuanType = $tkModel->typeMap['duoxuan'];
          $duoxuanTid = $tkModel->autoMakeTiId($params['duoxuanNumber'], $duoxuanType,$subject_id);
          if (empty($duoxuanTid)) {
            $this->error('题库中多选题的数量不足'.$params['duoxuanNumber'].'道');
          }
          // 判断 $num, $type, $subject_id
          $panduanType = $tkModel->typeMap['panduan'];
          $panduanTid = $tkModel->autoMakeTiId($params['panduanNumber'], $panduanType, $subject_id);
          if (empty($panduanTid)) {
            $this->error('题库中判断题的数量不足'.$params['panduanNumber'].'道');
          }
          // 单选
          $params['info'][$danxuanType] = ['type' => $danxuanType, 'score' => $params['danxuanScore'], 'info' => $params['danTypeExplain'], 'num' =>$params['danxuanNumber'],'tid'=>$danxuanTid];
          // 多选
          $params['info'][$duoxuanType] = ['type' => $duoxuanType, 'score' => $params['duoxuanScore'], 'info' => $params['duoTypeExplain'], 'num' =>$params['duoxuanNumber'],'tid'=>$duoxuanTid];
          // 判断
          $params['info'][$panduanType] = ['type' => $panduanType, 'score' => $params['panduanScore'], 'info' => $params['panTypeExplain'], 'num' =>$params['panduanNumber'],'tid'=>$panduanTid];
          // 总分
          list($tk, $totalScore) = $this->model->getTkAndScoreFromInfo($params['info']);
//          pre($tk);
//          pre($totalScore);exit;
          if(empty($tk)){
            $this->error('试卷不能没有题目');
          }
          $params['fullmark'] = $totalScore;
          // 开始时间转为字符戳
          $params['start'] = strtotime($params['start']);
          // 结束时间
          $params['end'] = strtotime($params['end']);
          // 考试时长
          $params['timelong'] = $params['timelong'] * 60;
          $params['info'] = json_encode($params['info']);
          try {
            //是否采用模型验证
            if ($this->modelValidate) {
              $name = basename(str_replace('\\', '/', get_class($this->model)));
              $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
              $row->validate($validate);
            }
            $result = $row->allowField(true)->save($params);
            if ($result !== false) {
              $this->success();
            } else {
              $this->error($row->getError());
            }
          } catch (\think\exception\PDOException $e) {
            $this->error($e->getMessage());
          } catch (\think\Exception $e) {
            $this->error($e->getMessage());
          }
        }
        $this->error(__('Parameter %s can not be empty', ''));
      }
      $this->view->assign("row", $row);
      return $this->view->fetch();
    }
}
