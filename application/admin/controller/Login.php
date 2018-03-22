<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\admin\model\User as UserModel;
use app\index\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;


class Login extends Controller
{
    public function login()
    {
        return $this->fetch();
    }


    public function panduan()
    {
        $user = new UserModel();
        //var_dump($_POST);
        if(!empty($_POST['username'] && !empty($_POST['password']))){
            $username = $_POST['username'];
            $password = $this->jia($_POST['password']);
           /* $result = $user->panduan($username);*/
            $result=Db::name('user')->alias('a')->where('quanxian',1)->where(['username'=>$username,'password'=>$password])->join('role_user b','b.user_id= a.uid')->join('role c','c.rid=b.role_id')->select();
          //dump($result);
        /* if(!$res){
            return json(['status'=>0,'msg'=>"您输入错误，请重新登录",'url'=>'/admin/user/login']);
         }*/
            //var_dump($result);

                if(!empty($result)){
                    $rid = $result[0]['rid'];
                    $username = $result[0]['username'];
                    $rolename = $result[0]['rolename'];
                    $quanxian = $result[0]['quanxian'];
                    $uid = $result[0]['uid'];
                    Session::set('rid',$rid);
                    Session::set('username',$username);
                    Session::set('rolename',$rolename);
                    Session::set('quanxian',$quanxian);
                    Session::set('uid',$uid);
                    //var_dump($rid);
                    //var_dump(Session::get('rid'));die;
                    $this->success('登录成功','/admin/index/index');
                }else{
                    $this->error('您不是管理员无法登陆');
                }


        }else{
            $this->error('用户名或密码不能为空');
        }
    }


    public function jia($str)
    {
        if(is_string($str)){
            return base64_encode(strrev($str));
        }else{
            return false;
        }


    }
}