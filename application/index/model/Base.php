<?php 
namespace  app\index\model;

use think\Model;
use think\Db;
class Base extends  Model
{

	//获取所有数据
	public  function  getAll($where=[],$field='*'){
		$info = Db::name($this->name)->where($where)->field($field)->select();

		return $info;
	}

	// 获取一条数据
	
	public  function  getOne($where=[],$field="*"){
		$info = Db::name($this->name)->where($where)->field($field)->find();

		return $info;
	}

	// 获取所有数据，带分页
	public function  getAllPage($where=[],$field="*",$num,$orderby='id asc'){
		$info  =  Db::name($this->name)->where($where)->field($field)->order($orderby)->paginate($num,false,['query'=>request()->param()]);

		return $info;
	}
}



?>