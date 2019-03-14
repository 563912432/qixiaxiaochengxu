<?php

namespace app\admin\model;

use think\Model;

class Ad extends Model
{
	const LUN_BO = 1;
	const YOU_LIAN = 2;
	const MINGHUA_LUNBO = 3;
	const CREATE_VIDEO = 4;
	const ABOUT_US = 5;
	const APP_FEATURE = 6;
  const TRAVEL = 7;
  const ANCHOR = 8;
	// 表名
	protected $name = 'ad';

	// 自动写入时间戳字段
	protected $autoWriteTimestamp = 'int';

	// 定义时间戳字段名
	protected $createTime = 'createtime';
	protected $updateTime = 'updatetime';

	// 追加属性
	protected $append = [

	];

	public static function getPosition($ind = null)
	{
		$arr = [
			self::LUN_BO => '首页轮播',
      self::YOU_LIAN => '合作伙伴',
      self::MINGHUA_LUNBO => '名画展示',
      self::CREATE_VIDEO => '原创ip视频',
      self::ABOUT_US => '关于我们视频',
      self::APP_FEATURE => 'APP特色',
      self::TRAVEL => '魔法行程单',
      self::ANCHOR => '主播入口'
		];
		if ($ind == null) {
			array_key_exists($ind, $arr) ? $arr[$ind] : $arr;
		}
		return $arr;
	}


}
