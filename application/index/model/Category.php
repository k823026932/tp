<?php

namespace app\index\model;
use think\Model;
use think\Controller;
use think\Db;
use traits\model\SoftDelete;
class Category extends Model
{
	 //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
	//查询大板块名
	public function selcat()
	{
		return Db::name('category')->where('parentid','=',0)->select();
	}
	//查询所有版块
	public function selcat1()
	{
		return Db::name('category')->select();
	}
	//根据大板块id查询小板块名
	public function selxiao($cid)
	{
		return Db::name('category')->where('parentid','=',$cid)->select();
	}
	//根据小板块id查询大板块名
	public function selda($cid)
	{
		return Db::name('category')->where('cid','=',$cid)->select();
	}
	//查询所有小板块
	public function xiao()
	{
		return Db::name('category')->where('parentid != 0')->select();
	}

}