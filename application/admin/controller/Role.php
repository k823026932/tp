<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Page;
use think\Session;
use app\index\model\User as UserModel;
use app\index\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;
class Role extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    //查询所有角色权限
    public function index1()
    {
        $video = new UserModel();
        $result=Db::name('user')->alias('a')->join('role_user b','b.user_id= a.uid')->join('role c','c.rid=b.role_id')->select();
        //var_dump($result);
        $quanxian = Db::name('access')->alias('r')->join('node n','r.node_id=n.id')->select();
        $count = count($result);
        $page = new Page(3,$count);
        $limit = $page->limit();
        $data = Db::name('user')->limit($limit)->alias('a')->join('role_user b','b.user_id= a.uid')->join('role c','c.rid=b.role_id')->select();
        //var_dump($data);
        $value['quanxian'] = $quanxian;
        $value['data'] = $data;
        $value['allPage'] = $page->allPage();
        echo json_encode($value);
    }
    //删除用户管理员功能
    public function del()
    {
        $video = new UserModel();
        $uid = $_GET['uid'];
        $result = db('role_user')->where('user_id',$uid)->delete();
        $result1 = $video->where('uid',$uid)->update(['quanxian'=>0]);
        if($result && $result1){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    //多删
    public function duoshan()
    {
        if(!empty($_POST['uid'])){
            
            $video = new UserModel();
            $uid = $_POST['uid'];
            //$uid = '3,4';
            $u = explode(',',$uid);
            //var_dump($u);
            foreach ($u as $key => $value) {
            //var_dump($value);
                $result = db('role_user')->where('user_id',$value)->delete();
                $result1 = $video->where('uid',$value)->update(['quanxian'=>0]);
            }
            
            //var_dump($result1);
            echo json_encode(['sta'=> 1]);
            
        }else{
            echo json_encode(['sta'=> 2]);
        }

    }
    //是否开启权限
    public function quanxian()
    {
        $video = new UserModel();
        $uid = $_GET['uid'];
        $quanxian = $_GET['quanxian'];
        $result = $video->where('uid',$uid)->update(['quanxian'=>$quanxian]);
        if($result){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }
    public function add()
    {
        $result = db('role')->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    //增加管理员
    public function addUser()
    {
        $user = new UserModel();
        if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['phone'])){
            $username = $_POST['username'];
            $password = $this->jia($_POST['password']);
            $phone = $_POST['phone'];
            $jiaose = $_POST['jiaose'];
            
            $result = $user->where('username',$username)->select();
            //var_dump($result);
            if($result){
                $this->error('该用户已被注册');
            }else{
                $user->username = $username;
                $user->password = $password;
                $user->phone = $phone;
                $data = $user->save();
                $uid = $user->uid;
                $data1 = ['role_id' => $jiaose, 'user_id' => $uid];
                $re = Db::table('think_role_user')->insert($data1);
                if($data && $re){
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
        }else{
            $this->error('请填写内容');
        }
    }

    public function edit()
    {
        $result = db('role')->select();
        $this->assign('result',$result);
        $uid = $_GET['uid'];
        $this->assign('uid',$uid);
        return $this->fetch();
    }
    //管理员等级修改
    public function updateg()
    {
        $uid = $_POST['uid'];
        $roleid = $_POST['jiaose'];
        //$roleid = 1;
        $result = Db::table('think_role_user')->where('user_id', $uid)->update(['role_id' => $roleid]);
        //var_dump($result);
        if ($result) {
            $this->success('修改成功','/admin/role/index');
        } else {
            $this->error('修改失败','/admin/role/index');
        }
       
    }
    //密码加密
    public function jia($str)
    {
        if(is_string($str)){
            return base64_encode(strrev($str));
        }else{
            return false;
        }

    }
}