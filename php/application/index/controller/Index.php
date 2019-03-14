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
    echo '欢迎进入栖霞前台！！';
	}


}
