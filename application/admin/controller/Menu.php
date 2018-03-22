<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Page;
use think\Session;
use app\admin\model\User as UserModel;
use app\admin\model\Danmu as DanmuModel;
use app\index\model\Video as VideoModel;
class Menu extends Controller
{
    public function add()
    {
        return $this->fetch();
    }

    public function edit()
    {
        return $this->fetch();
    }

    public function index()
    {
        return $this->fetch();
    }

    public function index1()
    {
        $danmu = new DanmuModel();
        $user = new UserModel();
        $result1 = $user->userLimit(null);
        //var_dump($result1);
        $result = $danmu->select();
        $count = count($result);
        $page = new Page(10,$count);
        $limit = $page->limit();
        $data = $danmu->limit($limit)->select();
        //var_dump($data);
        $value['data'] = $data;
        $value['data1'] = $result1;
        $value['allPage'] = $page->allPage();
        echo json_encode($value);
    }


    public function del()
    {
        $id = $_GET['id'];
        //echo $id;
        $result = DanmuModel::destroy($id,true);
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    public function duoshan()
    {
        if(!empty($_POST['id'])){
            $id = $_POST['id'];
            $result = DanmuModel::destroy($id,true);
            if($result){
                echo json_encode(['sta'=> 1]);
            }else{
                echo json_encode(['sta'=> 0]);
            }
        }else{
            echo json_encode(['sta'=> 2]);
        }
    }
}