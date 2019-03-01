<?php 
namespace app\index\controller;

// 个人资料控制器

use think\Controller;
use think\Db;
use app\index\controller\Base;

class Profile extends Base
{
	
	public function index()
	{
		return $this->fetch('profile/index');
	}
}
 ?>