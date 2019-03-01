<?php 
namespace app\index\controller;

// 用户管理控制器
// 用户增删改查

use think\Controller;
use think\Db;
use app\index\controller\Base;

class Member extends Base
{
	
	protected $model;

	public function _initialize(){
		parent::_initialize();

		$this->model = model('Member');
	}

	public function index() 
	{
		
		//索索条件
		$data = request()->get();
		if(empty(request()->get()['field'])){
			$where = [];
		}else{
			$where = [$data['field']=>['like','%'.$data['keyword'].'%']];
		}

		if(empty($data['order'])){
			 $order = 'id  asc';
		}else{
			$order = "ctime ".$data['order'];
		}

		//获取所有的用户
		$users = $this->model->getAllPage($where,'*',3,$order);

		$groups = Db::name('group')->column(['id','name']);

		return $this->fetch('member/index',['users'=>$users,'groups'=>$groups]);
	}

	public function add() 
	{
		$r = request();
		if($r->isGet()){
			$groups = model('Groups')->getAll(['status'=>1]);
			return $this->fetch('member/add',['groups'=>$groups]);
		}elseif($r->isPost()){
			$data = input('post.');

			//密码加密
			// $data['password'] = md5($data['password']);
			
			// 插入时间
			$data['ctime'] = time();

			// 获取上传文件
			$file = request()->file('photo');
			if($file){
				$info = $file->validate(['ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public/uploads');
				if($info){
					$data['photo'] = $info->getSaveName();
				}else{
					return $this->error('头像上传失败！');
				}
			}else{
				$data['photo'] = null;
			}

			$res = $this->model->save($data);
			if($res){
				return $this->success('添加成功！','/index/member/index');
			}else{
				if($file){
					unset($info);
					unlink(ROOT_PATH.'public/uploads/'.$data['photo']);
				}
				return $this->error('添加失败！');
			}
			
		}
		
	}


	public function del(){
		//获取id
		$id = request()->post('id');
		//获取要删除的信息
		$info = $this->model->getOne(['id'=>$id]);
		//执行删除
		$res = $this->model->destroy($id);

		if($res){
			//删除成功
			if(!empty($info['photo'])){
				//删除图片
				unlink(ROOT_PATH.'public/uploads/'.$info['photo']);
			}
			return $this->success('删除成功！');
		}else{
			return $this->error('删除失败！');
		}


	}

	public function edit() 
	{
		$r = request();

		if($r->isGet()){
			//加载修改页面
			$groups = model('Groups')->getAll(['status'=>1]);
			//获取修改页面
			$info = $this->model->getOne(['id'=>$r->get('id')]);
			return $this->fetch('member/edit',['groups'=>$groups,'info'=>$info]);
		}elseif($r->isPost()){
			//执行修改操作
			$data = $r->except('oldPhoto');
			// 获取上传文件
			$file = request()->file('photo');

			if($file){
				//有上传文件
				$info = $file->validate(['ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH.'public/uploads');

				if($info){
					//上传成功
					$data['photo'] = $info->getSaveName();
				}else{
					return $this->error('头像上传失败！');
				}

			}else{
				//没有上传文件
				$data['photo'] = $r->only('oldPhoto')['oldPhoto'];
			}

			$res = $this->model->save($data,$r->get('id'));

			if($res){
				// 数据库修改成功，
                //销毁原来的头像  ,根据 如果原来有头像，并还有新的上传头像
                if($r->only('oldPhoto')['oldPhoto'] && $file){
                	unlink(ROOT_PATH.'public/uploads/'.$r->only('oldPhoto')['oldPhoto']);
                }
                return $this->success('修改成功！','index/member/index');
			}else{
				// 数据库修改失败
                // 把新的上传头像销毁掉
                if($file){
                	unset($info);
                	unlink(ROOT_PATH.'public/uploads/'.$data['photo']);
                }
                return $this->error('修改失败！');
			}
		}
		
	}
}




 ?>