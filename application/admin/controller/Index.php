<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\index\model\User as UserModel;
use app\index\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;
class Index extends Controller
{
	public function index()
	{


        if(empty(Session::get('rid')))
        {
            $this->error('请登录','/admin/login/login');
        }



        $username = Session::get('username');
        $this->assign('username',$username);


         $uid = Session::get('uid');



         $username = Session::get('username');

         //var_dump($rid);die;
         //
         // 查询用户权限
         /*$result = Db::name('access')->alias('r')->where('role_id',"$rid")->join('node n','r.node_id=n.id')->select();*/
         $this->assign('username',$username);

        return $this->fetch();

	}

    public function index1()
    {

         $rid = Session::get('rid');
         $rolename = Session::get('rolename');
         //var_dump($rid);die;
         //
         // 查询用户权限
         $result = Db::name('access')->alias('r')->where('role_id',"$rid")->join('node n','r.node_id=n.id')->select();
         $data['result'] = $result;
         echo json_encode($data);

    }

        //退出登录
    public function clear()
    {
        Session::clear();
        $this->success('退出成功', 'admin/login/login');
    }
}