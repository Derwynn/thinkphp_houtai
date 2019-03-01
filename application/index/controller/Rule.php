<?php 
namespace app\index\controller;

// 权限规则控制器
// 权限规则增删改查

use think\Controller;
use think\Db;
use app\index\controller\Base;
class Rule extends Base
{
	// 使用model模板
	protected $model;

	public function _initialize(){
		parent::_initialize();
		$this->model = model('Rules');
	}

	public function index(){
		$info = $this->model->getAll();
		$info = getLevel($info);
		return $this->fetch('rule/index',['info'=>$info]);
	}

	public function add(){
		$r = request();
		if($r->isGet()){

			$where = [
				'status'=>['=',1],
				'Is_show'=>['=',1]
			];

			$rules = $this->model->getAll($where);
			$rules = getLevel($rules);

			return $this->fetch('rule/add',['rules'=>$rules]);
		}elseif($r->isPost()) {
			$data = input('post.');
			$info = [
            'pid'=>$data['pid'],
            'Rule_name'=>$data['Rule_name'],
            'address'=>$data['address'],
            'icon'=>$data['icon'],
            'status'=>$data['status'],
            'sort'=>$data['sort'],
            'Is_show'=>$data['Is_show']
        ];
			$rule = [
				'Rule_name'=>'require'
			];
			$message = [
				'Rele_name.require'=>'权限名称不能为空！'
			];
			$res = $this->validate($info,$rule,$message);
			if($res !== true){
				return $this->error($res);
			}
			$result = $this->model->save($info);

			if($result){
				return $this->success('添加成功!','index/rule/index');
			}else{
				return $this->error('添加失败!');
			}

		}
	}

	public function del(){
		$id = request()->post('id');
		if(!$id){
			return $this->error('当前参数不能为空！');
		}
		$rule = $this->model->getAll(['pid'=>$id]);
		if(!empty($rule)){
			return $this->error('当前权限下有子权限，不允许删除！','','1');
		}
		$res = $this->model->destroy($id);
		if($res){
			return $this->success('删除成功！','','2');
		}else{
			return $this->error('删除失败！','','3');
		}
	}

	public function edit(){
		$id = request()->get('id');

		$info = $this->model->getOne(['id'=>$id]);
		$where = [
			'id'=>['<>',$id],
			'pid'=>['<>',$id],
			'status'=>1,
			'Is_show'=>1
		];
		
		$rules = $this->model->getAll($where,'id,Rule_name,pid');
		$rules = getLevel($rules);
		return $this->fetch('rule/edit',['info'=>$info,'rules'=>$rules]);

	}

	public function  updates(){
	      $data  = request()->post();
	      $id = $data['id'];

	      $info = [
	          'pid'=>$data['pid'],
	          'Rule_name'=>$data['Rule_name'],
	          'address'=>$data['address'],
	          'icon'=>$data['icon'],
	          'status'=>$data['status'],
	          'sort'=>$data['sort'],
	          'Is_show'=>$data['Is_show']
	      ];
	      
	      $res = $this->model->save($info,['id'=>$id]);

	      // dump($res);
	      if($res){
	           return  $this->success('修改成功！','/index/rule/index');
	      }else{
	          return  $this->error('修改失败！');
	      }
    }
}

 ?>