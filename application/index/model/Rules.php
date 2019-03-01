<?php 
	namespace  app\index\model;

	// use think\Model;
	// use think\Db;
	use app\index\model\Base;
	class Rules extends  Base
	{
		protected  $name='permission';

		// //获取所有数据
		// public  function  getAll($where=[],$field='*'){
		// 	$info = Db::name($this->name)->where($where)->field($field)->select();

		// 	return $info;
		// }
	}



?>