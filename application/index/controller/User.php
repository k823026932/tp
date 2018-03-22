<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Ucpaas;
use think\Session;
use think\Phoneyz;
use app\index\model\User as UserModel;
use app\index\model\Category as CategoryModel;
use app\index\controller\Open51094;
class User extends Controller
{
    //登录
	public function login()
    {
    	$category = new CategoryModel();
        $lala = $category->where('parentid','=',0)->select();

        $this->assign('lala',$lala);
        return $this->fetch();
    }
    //验证登录
    public function loginIn()
    {
        //var_dump($_POST);
        if(empty($_POST['username'])&&empty($_POST['password'])){
            exit('请填写用户名或密码');
        }
        $user = new UserModel;
        $username = $_POST['username'];
        $pwd = $this->jia($_POST['password']);
        $data = $user->where('username',$username)->find();
        //var_dump($data['username']);
        if($pwd == $data['password']){
            Session::set('username',$username);
            Session::set('uid',$data['uid']);
            Session::set('touxiang',$data['touxiang']);
            Session::set('quanxian',$data['quanxian']);
            //var_dump(Session::get('touxiang'));die;
            $this->success('登录成功', 'index/index/index');

        }else{
            $this->error('请输入正确的用户名或密码');
        }
    }
    public function reg()
    {
        $category = new CategoryModel();
        $lala = $category->where('parentid','=',0)->select();

        $this->assign('lala',$lala);
        return $this->fetch();
    }

    public function zhuce()
    {
        $user = new UserModel;
        $name = $_POST['name'];

        $data = $user->panduan($name);
        if($data){
            echo json_encode(['state'=> 1]);
        }else{
            echo json_encode(['state'=> 0]);
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

    public function doZhuce()
    {
        $user = new UserModel;
        $username = $_POST['name'];
        $email = $_POST['mailNum'];
        $code = Session::get('content');
        if($email == $code){
            $password = $this->jia(($_POST['password']));
            $phone = $_POST['phone'];
            //$list = [['username'=>$username,'password'=>$password,'phone'=>$phone]];
            $user->username = $username;
            $user->password = $password;
            $user->phone = $phone;
            $data = $user->save();
            //echo json_encode(['sta'=> 1]);
            if($data){
                echo json_encode(['state'=> 1]);
                //echo 11;
            }else{
                echo json_encode(['state'=> 0]);
                //echo 222;
            }
        }else{
                echo json_encode(['sta'=> 0]);
            }

    }

    //邮箱验证
    function email()
    {
        include 'mail/mail.php';
        $to = trim($_POST['to']);
        //$to = '571156432@qq.com';
        $title = '邮箱验证码';
        $content = substr(str_shuffle('0123456789'),0,4);
        Session::set('content',$content);
        $data = sendMails($to,$title,$content);
        if($data){
            echo json_encode(['state'=> 1]);
            //echo 111;
        }else{
            echo json_encode(['state'=> 0]);
            //echo 222;
        }
        //var_dump($content);
    }
    function forgot()
    {
        $category = new CategoryModel();
        $lala = $category->where('parentid','=',0)->select();

        $this->assign('lala',$lala);
        return $this->fetch();
    }
    //修改资料页面
    public function member()
    {
        $category = new CategoryModel();
        $lala = $category->where('parentid','=',0)->select();
        $uid = Session::get('uid');
        $user = new UserModel;
        $ziliao = $user->where('uid',$uid)->find();
        $this->assign('ziliao',$ziliao);
        $this->assign('lala',$lala);
        return $this->fetch();
    }
    public function uinsert()
    {
        $uid = Session::get('uid');
        if (!empty($_POST)) {
            //var_dump($_POST);
            //var_dump($_FILES);
            $user = UserModel::get($uid);
            $user->uid = $uid;
            //若头像不为孔就更新
            if (!empty($_FILES['touxiang']['name'])) {
                $touxiang = request()->file('touxiang');
                $vid = $touxiang->move(ROOT_PATH . 'public' . DS . 'uploads');
                $vimg = '/uploads/' . $vid->getSaveName();
                Session::set('touxiang',$vimg);
                //var_dump($vimg);
                $user->touxiang = $vimg;

                $user->save();
            }
            //若性别不为不公开
            if ($_POST['sex'] != '-1') {
                $sex = $_POST['sex'];
                $user->sex = $sex;
                $user->save();
            }
            //若密码不为空
            if ($_POST['password'] != '') {
                $password = $_POST['password'];
                $user->password = $password;
                $user->save();
            }
            //若真实姓名不为空
            if ($_POST['xingming'] != '') {
                $xingming = $_POST['xingming'];
                $user->xingming = $xingming;
                $user->save();
            }
            //若邮箱不为空
            if ($_POST['email'] != '') {
                $email = $_POST['email'];
                $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
                if(preg_match($pattern,$email)){
                    $user->email = $email;
                    $user->save();
                } else{
                    $this->error('邮箱格式错误！');
                }


            }
            //若手机不为空
            if ($_POST['phone'] != '') {
                $phone = $_POST['phone'];
                if(preg_match("/1[3458]{1}\d{9}$/",$phone)){
                     $user->phone = $phone;
                    $user->save();
                }else{
                   $this->error('手机号格式错误！');
                }


            }
            //若省份不为不公开
            if ($_POST['shengfen'] != '不公开') {
                $shengfen = $_POST['shengfen'];
                $user->shengfen = $shengfen;
                $user->save();
            }
            //若地市不为不公开
            if ($_POST['dishi'] != '不公开') {
                $dishi = $_POST['dishi'];
                $user->dishi = $dishi;
                $user->save();
            }
        }
        $this->success('修改成功');
    }
    //短信验证码
    function code()
    {
        /*$phone = '17777787662';
        $name = 'admin';*/
        $name = trim($_POST['name']);
        $phone = trim($_POST['number']);
        $user = new UserModel;
        $data = $user->panduan($name);
        if ($data && $phone == $data['phone'] ){
            Session::set('uid',$data['uid']);
            $phoneyz = new Phoneyz($phone);
            $phoneyz->getYzm();
            $pcode = $phoneyz->randNum;
            Session::set('code',$pcode);
            echo json_encode(['state'=> 1]);
            //echo 111;
            //echo $pcode;
        }else{
            //$this->success('','/index/user/forgot');
            echo json_encode(['state'=> 0]);
            //echo 222;
        }

    }

    //判断验证码是否相等
    function codeYz()
    {
        $yzm = trim($_POST['yzm']);
        $pyzm = Session::get('code');
        if($yzm == $pyzm){
            echo json_encode(['state'=> 1]);
            //$this->success('','/index/user/login');
        }else{
            echo json_encode(['state'=> 0]);
        }
    }

    //修改页面
    function retrieve()
    {
        $category = new CategoryModel();
        $lala = $category->where('parentid','=',0)->select();

        $this->assign('lala',$lala);
        return $this->fetch();
    }

    //修改密码
    function update()
    {
        $uid = Session::get('uid');
        $user = UserModel::get($uid);
       $user->password = $this->jia(trim($_POST['password']));
       $result = $user->save();
       if($result){
            echo json_encode(['state'=> 1]);
       }else{
        echo json_encode(['state'=> 0]);
       }
    }

//第三方登录
    public function other()
    {
        $open = new Open51094();
        // dump($open);
        $code = $_GET['code'];
        // dump($code);
        $me = $open->me($code);
        // dump($me);
        $username = $me['uniq'];
        $touxiang = $me['img'];
        //var_dump($touxiang);
        $sex = $me['sex'];
        $res = Db::table('think_user')->where('username','=',"$username")->select();
        // dump($res);
        if (!$res) {
            //添加到数据库中
            $result = Db::table('think_user')->insert(['username' => "$username",'touxiang' => "$touxiang",'sex' => "$sex"]);
            // dump($result);die;
            if (!$result) {
                $this->error('登录失败，请重新登录', '/index/user/login');
                exit;
            }
        }
        $uid = Db::table('think_user')->where('username','=',"$username")->value('uid');
        // dump($uid);die;
        Session::set('uid', $uid);
        Session::set('username', $username);
        Session::set('touxiang', $touxiang);
        //var_dump(Session::get('touxiang'));die;
        $this->success('登录成功','/index/index/index');
    }
}