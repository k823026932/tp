<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\index\model\User as UserModel;
use app\index\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;
class Index extends Controller
{
	//首页
    public function index()
    {
    	$video = new VideoModel();
    	$user = new UserModel;
    	$category = new CategoryModel();
    	//查询一级分类
   		$lala = $category->where('parentid','=',0)->select();
    	$this->assign('lala',$lala);
    	//查询所有分类
    	$lala1 = $category->select();
    	$this->assign('lala1',$lala1);

    	//轮播图查询
    	$lunbo = $video->where('lunbo','=',1)->select();
		$this->assign('lunbo',$lunbo);
		//轮播图右面的6个视频
		$order = 'bofangliang';
    	$shi6 = $video->limit(6)->order("$order desc")->select();
    	$this->assign('shi6',$shi6);
    	//查询全部用户资料
    	$qbzl = $user->select();
	    $this->assign('qbzl',$qbzl);
	    //推荐视频
		$order1 = 'danmu';
    	$tuijian = $video->order("$order desc")->select();
    	$this->assign('tuijian',$tuijian);
        //排行视频
        $order1 = 'bofangliang';
        $paihang = $video->order("$order desc")->select();
        $this->assign('paihang',$paihang);
        //fuzhi
        $i = 0;
        $this->assign('i',$i);
        $j = 1;
        $this->assign('j',$j);
    	if (!empty(Session::get('uid'))) {
    		$uid = Session::get('uid');

	        $ziliao = $user->where('uid',$uid)->find();
            //var_dump($ziliao);die;
	        $this->assign('ziliao',$ziliao);
    	}
        //判断是不是管理
        $quanxian = Session::get('quanxian');
        $this->assign('quanxian',$quanxian);
    	return $this->fetch();
    }
    //头部
    public function top()
    {
    	$category = new CategoryModel();
   		$lala = $category->selcat();

    	$this->assign('lala',$lala);
    	return $this->fetch();
    }
    //下拉栏
    public function topxia()
    {
    	$category = new CategoryModel();
   		$cid = $_POST['cid'];
    	$xiao = $category->where('parentid','=',$cid)->select();
    	$data['xiao'] = $xiao;
    	echo json_encode($data);

    }
    //退出登录
    public function clear()
    {
    	Session::clear();
    	$this->success('退出成功', 'index/index/index');
    }

}
