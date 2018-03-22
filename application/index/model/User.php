<?php

namespace app\index\model;
use think\Model;
use think\Controller;
use think\Db;
use traits\model\SoftDelete;
class User extends Model
{
	//4.软删除
	use SoftDelete;
	protected static $deleteTime = 'delete_time';

	//获取器
	public function getSexAttr($value)
	{
		$flags = [0=>'未知', 1=>'男', 2=>'女'];
		return $flags[$value];
	}
	//修改器
    /*public function setUsernameAttr($data)
    {
        return strtoupper($data);
    }*/
    //查询用户是否存在
    function panduan($name)
    {
        return Db::name('user')->where('username',$name)->find();
    }
    //查询用户资料
    function selu($uid)
    {
        return Db::name('user')->where('uid',$uid)->find();
    }
    //增加用户
    function add($name,$password,$phone)
    {
        return Db::name('user')->insert(['username'=>$name, 'password'=>$password,'phone'=>$phone]);
    }

        //查询用户所有信息
    function userList()
    {
        return Db::name('user')->select();
    }
}