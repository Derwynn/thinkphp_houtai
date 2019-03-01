<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
class Base extends Controller
{
	public function _initialize(){
		//判断用户是否登录
		if(empty(session('username'))){
			return $this->error('请登录！','/index/login/login');
		}
		//获取权限数据
		$m = model('Rules');
		//判断当前登录用户是否为超级管理员
		
		if(session('groupid')!=1){
			//普通用户
			$strRule = Db::name('group')->where('id',session('groupid'))->value('rule');
			$arrRule = explode(',',$strRule);

			$where = [
				'status'=>1,
				'Is_show'=>1,
				'id'=>['in',$arrRule]
			];

			$path = request()->path();

			$permissionid = Db::name('permission')->where('address','=',$path)->value('id');

			$brr = $arrRule;
			$brr[] = [23,25];
			if(!in_array($permissionid,$brr)){
				return $this->error('您没有权限访问！');
			}
		}else{
			$where = [
				'status'=>1,
				'Is_show'=>1
			];
		}
		
		$leftRules = $m->getAll($where);

		$leftRules = getTree($leftRules);

		$this->assign('leftRules',$leftRules);
	}
}

 ?>