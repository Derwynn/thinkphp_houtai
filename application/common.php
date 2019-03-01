<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function getLevel($info,$pid=0,$level=0){
	static $tree = [];

	foreach($info as $k=>$v){
		$v['level'] = $level+1;

		$v['Rule_name'] = $v['pid'] == 0 ? $v['Rule_name'] : str_repeat('&nbsp;&nbsp;',$v['level']-1)."┗".str_repeat("━",$v['level']-1).$v['Rule_name'];

		if($v['pid'] == $pid){
			$tree[] = $v;
			getLevel($info,$v['id'],$v['level']);
		}
	}
	return $tree;
}


function getTree($data,$pid=0){
	$tree = [];

	foreach($data as $k=>$v){
			if($v['pid'] == $pid){
			$v['list'] = getTree($data,$v['id']);
			$tree[] = $v;
		}
	}
	return $tree;
	
}
