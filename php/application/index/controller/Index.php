<?php

namespace app\index\controller;

use app\admin\model\Ad;
use app\common\controller\Frontend;
use app\common\library\Token;
use think\Collection;

class Index extends Frontend
{

	protected $noNeedLogin = '*';
	protected $noNeedRight = '*';
	protected $layout      = '';

	public function _initialize()
	{
		parent::_initialize();
	}

	public function index()
	{


		$lunbo  = Ad::where('title', Ad::LUN_BO)->select();
		$huoban = Ad::where('title', Ad::YOU_LIAN)->select();
		$minghua = Ad::where('title', Ad::MINGHUA_LUNBO)->select();
		$create = Ad::where('title', Ad::CREATE_VIDEO)->select();
		$aboutus = Ad::where('title', Ad::ABOUT_US)->find();
		$appfeature = Ad::where('title', Ad::APP_FEATURE)->select();
    $travel = Ad::where('title', Ad::TRAVEL)->find();
    $anchor = Ad::where('title', Ad::ANCHOR)->find();
		$this->assign([
			'lunbo' => $lunbo,
			'huoban' => $huoban,
      'minghua' => $minghua,
      'create' => $create,
			'aboutus' => $aboutus,
			'appfeature' => $appfeature,
      'travel' => $travel,
      'anchor' => $anchor,
			'createJson' => Collection($create)->toJson()
		]);
//		if(is_mobile()) {
//			return $this->view->fetch('mobile');
//		} else {
//			return $this->view->fetch('index');
//		}
    return $this->view->fetch('index');
	}


}
