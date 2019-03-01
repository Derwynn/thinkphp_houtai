<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
class Login extends Controller
{
	public function login(){
		return $this->fetch('./login');
	}

	public function dologin(){
		$data = request()->post();

		if(!captcha_check($data['code'])){
			return $this->error('验证码错误！');
		};

		$info = Db::name('user')->where('username',$data['username'])->where('password',$data['password'])->find();

		if(empty($info)){
			return $this->error('请输入正确的用户名或密码！');
		}else{
			session('username',$info['username']);
			session('photo',$info['photo']);
			session('id',$info['id']);
			session('groupid',$info['group_id']);
			return $this->success('登录成功！','/index/index/index');
		}
	}

	public function out(){
		session('username',null);
		session('photo',null);
		session('id',null);
		session('groupid',null);
		return $this->redirect('/index/login/login');
	}
}

 ?>