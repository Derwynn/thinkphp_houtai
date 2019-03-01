<?php 
	namespace app\index\validate;

	use think\Validate;

	class Group  extends  Validate
	{

		//1.定义规则
		protected $rule = [
          'name'=>'require|max:10',
          'status'=>'number'
        ];
		//2.定义返回信息
        protected  $message = [
          'name.require'=>'当前角色名不能为空！',
          'name.max'=>'当前角色名长度不能超过10！',
          'status.number'=>'当前内容必须是数字！'
       ];
		
	}


?>