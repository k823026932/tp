<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Page;
use think\Session;
use app\admin\model\Pinglun as PinglunModel;
/*use app\admin\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;*/
class Pinglun extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function index1()
    {
        $pinglun = new PinglunModel();
        $result = $pinglun->select();
        $count = count($result);
        $page = new Page(5,$count);
        $limit = $page->limit();
        $data = $pinglun->limit($limit)->select();
        //var_dump($data);
        $value['data'] = $data;
        $value['allPage'] = $page->allPage();
        echo json_encode($value);
    }

    public function duoshan()
    {
        $pinglun = new PinglunModel();
        if(!empty($_POST['id'])){
            $id = $_POST['id'];
            $result = PinglunModel::destroy($id,true);
            if($result){
                echo json_encode(['sta'=> 1]);
            }else{
                echo json_encode(['sta'=> 0]);
            }
        }else{
            echo json_encode(['sta'=> 2]);
        }

    }


    public function del()
    {
        //var_dump($_GET);
        $id = $_GET['id'];
        $result = PinglunModel::destroy($id,true);
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }

    }
}