<?php

namespace app\index\model;
use think\Model;
use think\Controller;
use think\Db;
use traits\model\SoftDelete;
class Video extends Model
{

	public function selcat()
	{
		return Db::name('category')->where('parentid','<>',0)->select();
	}
	//轮播图查询
	public function selvid1()
	{
		return Db::name('video')->where('lunbo','=',1)->select();
	}
	//6个视频查询
	/*public function selvid6()
	{
		return Db::name('video')->limit(6)->select();
	}*/
	/*public function insuser($title,$fenqu,$vimg,$content,$vpath)
	{

		return Db::table('think_video')->insert(['title'=>$title, 'fenqu'=>$fenqu, 'vimg'=>$vimg, 'content'=>$content, 'vpath'=>$vpath]);
	}*/
	function videoAll($where)
    {
        return Db::name('video')->where($where)->select();
    }

    //视频查询
    function videoLimit($limit,$where)
    {
        return Db::name('video')->where($where)->limit($limit)->select();
    }
    //视频条件查询
    function order2($limit,$order)
    {
        return Db::name('video')->limit($limit)->order("$order desc")->select();
    }
    //所有视频查询
    function allvideo()
    {
        return Db::name('video')->select();
    }
    //视频条件查询所有
    function orderall($order)
    {
        return Db::name('video')->order("$order desc")->select();
    }

    //vid查询视频
    public function shipin($vid)
    {
        return Db::name('video')->where('vid','=',$vid)->select();

    }


    
    function order1($where,$limit,$order)
    {
        return Db::name('video')->where($where)->limit($limit)->order("$order desc")->select();
    }

    function sou($where)
    {
        return Db::name('video')->where($where)->select();

    }
}