<?php 
namespace app\index\controller;

// 角色管理控制器
// 角色增删改查

use think\Controller;
use think\Db;
use app\index\controller\Base;
class Group extends Base
{
	protected $model=null;

	public function _initialize(){
		parent::_initialize();
		$this->model = model('Groups');
	}

	public function index()
	{
		// 获取当前所有角色
		// $info = Db::name('group')->paginate(4);
		$info = $this->model->getAllPage([],'*',4);
		// 加载当前角色列表
		return $this->fetch('group/index',['info'=>$info]);
	}

	public function add()
	{
		$r = request();

		if($r->isGet()){
			//获取权限
			$rules = model('Rules')->getAll(['status'=>1]);
			$rules = getTree($rules);
			// 加载页面
			return $this->fetch('group/add',['rules'=>$rules]);
		}elseif($r->isPost()){
			// 获取验证数据
			$data = input('post.');
			$data['rule'] = implode(',',$data['rule']);
			// 验证
			$ress = $this->validate($data,'Group');
			if($ress!==true){
				return $this->error($ress);
			}
			//插入数据库
			// $res = Db::name('group')->insert($data);
			$res = $this->model->save($data);

			if($res){
				return $this->success('角色添加成功！','/index/group/index');
			}else{
				return $this->error('角色添加失败！','/index/group/add');
			}
		}
		
	}

	public function del(){
		// 获取id
		$r = request();
		$id = $r->post('id');
		if(!$id){
			return $this->error('当前参数不能为空！');
		}
		// $res = Db::name('group')->where('id',$id)->delete();
		$res = $this->model->destroy($id);

		if($res){
			return $this->success('删除成功！');
		}else{
			return $this->error('删除失败！');
		}
	}

	public function edit(){
		$id = request()->get('id');

		if(request()->isGet()){
			// $info = Db::name('group')->where('id',$id)->find();
			$info =$this->model->getOne(['id'=>$id]);
			$ruleArr = explode(',', $info['rule']);
			//获取权限
			$rules = model('Rules')->getAll(['status'=>1]);
			foreach($rules as $key => $value){
				if(in_array($value['id'],$ruleArr)){
					$rules[$key]['k'] = 1; //1表示有权限
				}else{
					$rules[$key]['k'] = 2;//2表示没有权限
				}
			}

			$rules = getTree($rules);

			return $this->fetch('group/edit',['info'=>$info,'rules'=>$rules]);
		}elseif(request()->isPost()){
			$data = request()->post();

			$data['rule'] = implode(',',$data['rule']);
			// 验证
			$ress = $this->validate($data,'Group');
			if($ress !== true){
				return $this->error($ress);
			}

			$res = $this->model->save($data,['id'=>$id]);
			if($res){
				return $this->success('修改成功！','/index/group/index');
			}else{
				return $this->error('修改失败！');
			}

		}
		

	}
}

 
 ?>