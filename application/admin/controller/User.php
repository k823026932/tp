<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Page;
use think\Session;
use app\admin\model\User as UserModel;
use app\index\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;
class User extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function index1()
    {
        $user = new UserModel();
        $result = $user->select();
        $count = count($result);
        //var_dump($count);
        $page = new Page(5,$count);
        $limit = $page->limit();
        $data = $user->limit($limit)->select();
        //var_dump($data);
        $value['data'] = $data;
        $value['allPage'] = $page->allPage();
        echo json_encode($value);
    }
    public function add()
    {
        return $this->fetch();
    }

    public function edit()
    {
        return $this->fetch();
    }

    //删除用户
    public function del()
    {
        //var_dump($_GET);
        $id = $_GET['id'];
        $result = UserModel::destroy($id);
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    //增加用户
    public function addUser()
    {
        $user = new UserModel();
        if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['phone'])){
            $username = $_POST['username'];
            $password = $this->jia($_POST['password']);
            $phone = $_POST['phone'];
            
            $result = $user->where('username',$username)->select();
            //var_dump($result);
            if($result){
                $this->error('该用户已被注册');
            }else{
                $user->username = $username;
                $user->password = $password;
                $user->phone = $phone;
                $data = $user->save();
                if($data){
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
        }else{
            $this->error('请填写内容');
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

    //黑名单
    public function heimingdan()
    {
        return $this->fetch();
    }

    public function heimingdan1()
    {
        $user = new UserModel();
        $result = UserModel::onlyTrashed()->select();
        $count = count($result);
        //var_dump($count);
        $page = new Page(5,$count);
        $limit = $page->limit();
        $data = UserModel::onlyTrashed()->limit($limit)->select();
        //var_dump($data);
        //var_dump($data);
        $value['data'] = $data;
        $value['allPage'] = $page->allPage();
        echo json_encode($value);
    }

    public function delete()
    {
        $id = $_GET['id'];
        $result = Db::table('think_user')->delete($id);
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    public function quxiaola()
    {
        $id = $_GET['id'];
        $result = Db::table('think_user')->where("uid=$id")->update(['delete_time'=>null]);
        if($result){
            $this->success('已取消');
        }else{
            $this->error('失败');
         }
    }
}