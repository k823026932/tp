<?php
namespace app\index\controller;

class UserInfo
{
	public function test($name, $age)
	{
		echo request()->url();
		echo request()->domain();
		echo request()->module() . '<br>';
		dump(request()->param()) . '<br >';
		echo request()->action() . '<br>';
		return '我是UserInfo控制器下面的test方法'. $name . $age; 
	}
}