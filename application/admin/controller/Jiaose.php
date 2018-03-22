<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Page;
use think\Session;
use app\index\model\User as UserModel;
use app\index\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;
class Jiaose extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    //查询所有角色权限
    public function index1()
    {
        $video = new UserModel();
        $result=Db::name('role')->select();
        //var_dump($result);
        $quanxian = Db::name('access')->alias('r')->join('node n','r.node_id=n.id')->select();
        $count = count($result);
        $page = new Page(3,$count);
        $limit = $page->limit();
        $data = Db::name('role')->limit($limit)->select();
        //var_dump($data);
        $value['quanxian'] = $quanxian;
        $value['data'] = $data;
        $value['allPage'] = $page->allPage();
        echo json_encode($value);
    }
    //删除角色
    public function del()
    {
        
        $uid = $_GET['rid'];
        $result = db('role')->where('rid',$uid)->delete();
        $result1 = db('access')->where('role_id',$uid)->delete();
        if($result && $result1){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    //多删
    public function duoshan()
    {
        if(!empty($_POST['rid'])){
            
            
            $uid = $_POST['rid'];
            //$uid = '3,4';
            $u = explode(',',$uid);
            //var_dump($u);
            foreach ($u as $key => $value) {
            //var_dump($value);
                $result = db('role')->where('rid',$value)->delete();
                $result1 = db('access')->where('role_id',$value)->delete();
            }
            
            //var_dump($result1);
            echo json_encode(['sta'=> 1]);
            
        }else{
            echo json_encode(['sta'=> 2]);
        }

    }
    public function add()
    {
        $result = db('node')->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    //增加角色
    public function addUser()
    {
        
        if(!empty($_POST['name']) && !empty($_POST['jiaose'])){
            $username = $_POST['name'];
            $jiaose = $_POST['jiaose'];
            
            $result = Db::name('role')->where('rolename',$username)->find();
            //var_dump($result);
            if($result){
                $this->error('已有该角色名');
            }else{
                //var_dump($jiaose);
                $ins = ['rolename'=>$username];
                $rid = Db::name('role')->insertGetId($ins);
                foreach ($jiaose as $key => $value) {
                    $data1 = ['role_id' => $rid, 'node_id' => $value];
                    $re = Db::table('think_access')->insert($data1);
                }
                
                if($rid){
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
        $result = db('node')->select();
        $this->assign('result',$result);
        $uid = $_GET['rid'];
        $this->assign('uid',$uid);
        $id = $_GET['id'];
        $this->assign('id',$id);
        return $this->fetch();
    }
    //管理员等级修改
    public function upda()
    {
        
        $id = $_POST['id'];
        //var_dump($id);
        $jiaose = $_POST['jiaose'];
        //$roleid = 1;
        $result1 = db('access')->where('role_id',$id)->delete();
        
        foreach ($jiaose as $key => $value) {
            $data1 = ['role_id' => $id, 'node_id' => $value];
            $re = Db::table('think_access')->insert($data1);
        }
        //var_dump($result);
        if ($result1) {
            $this->success('修改成功','/admin/role/index');
        } else {
            $this->error('修改失败','/admin/role/index');
        }
       
    }
}