<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Page;
use think\Session;
use app\index\model\User as UserModel;
use app\admin\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;
class Node extends Controller
{

    public function index()
    {
        $cate = new CategoryModel();

        $arr = $cate->select();
        //var_dump($arr);

        $arr = $cate->select();
        //$arr = Db::table('think_category')->select();
        //dump($arr);


        $arr = $cate->select();
        //var_dump($arr);
        $arr = $cate->select();
        //$arr = Db::table('think_category')->select();
        //dump($arr);
        $cat = $this->tree($arr);
        //var_dump($cat);
        $this->assign('cat',$cat);
/*      $cate = new CategoryModel();
        $where = 'parentid=0';
        $result = $cate->big($where);
        $this->assign('result',$result);*/
        return $this->fetch();
    }

    public function index1()
    {
        $cate = new CategoryModel();
        $result = $cate->select();
        $count = count($result);
        $page = new Page(5,$count);
        $limit = $page->limit();
        $data = $cate->catLimit($limit);
        //var_dump($data);
        $value['data'] = $data;
        $value['allPage'] = $page->allPage();
        echo json_encode($value);
    }

    public function add()
    {
        //$cate = new CategoryModel();
        //$where = 'parentid=0';
        //$result = $cate->big($where);
        //var_dump($result);
        //$this->assign('result',$result);
        $arr = Db::table('think_category')->where(null)->select();
        //var_dump($arr);
        $cat = $this->tree($arr);
        //var_dump($cat);
        $this->assign('cat',$cat);
        return $this->fetch();
    }

    public function edit()
    {
        //var_dump($_GET['id']);
        $id = $_GET['id'];
        $this->assign('id',$id);
        return $this->fetch();
    }

    public function bankuai()
    {

        if (!empty($_FILES['file']['name']) && !empty($_POST['grouptitle'])) {
            $touxiang = request()->file('file');
            $vid = $touxiang->move(ROOT_PATH . 'public' . DS . 'uploads');
            $vimg = '/uploads/' . $vid->getSaveName();
            $name = $_POST['grouptitle'];
            if(!empty($_POST['selc'])){
                $selc = $_POST['selc'];
            }else{
                $selc = 0;
            }

           $result = Db::name('category')->insert(['bkname'=>$name, 'parentid'=>$selc,'bkimg'=>$vimg]);
           //var_dump($result);die;
           if($result){
            $this->success('增加成功');
           }else{
            $this->error('增加失败');
           }
        }else{
            $this->error('资料不能为空');
        }

    }

    //修改板块
    public function update()
    {
        $id = $_POST['id'];
        $where = "cid=$id";
        if (!empty($_FILES['img']['name']) && !empty($_POST['grouptitle'])) {
            $touxiang = request()->file('img');
            $vid = $touxiang->move(ROOT_PATH . 'public' . DS . 'uploads');
            $vimg = '/uploads/' . $vid->getSaveName();
            $name = $_POST['grouptitle'];
            //$selc = $_POST['selc'];
            $cate = new CategoryModel;
            $result = $cate->where('cid', $id)->update(['bkname' =>$name,'bkimg'=>$vimg]);
           if($result){
            $this->success('修改成功','admin/index/index');
           }else{
            $this->error('修改失败');
           }
        }else{
            $this->error('资料不能为空');
        }        //var_dump($_POST);

    }

    //板块删除
    public function del()
    {
        $cate = new CategoryModel();
        $id = $_GET['id'];
        $result = CategoryModel::destroy($id);
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
        //var_dump($id);
    }


    //多删
    public function duoshan()
    {
        $cate = new CategoryModel();
        if(!empty($_POST['id'])){
            $id = $_POST['id'];
            //var_dump($id);
            $cid = join(',',$id);
            $result = CategoryModel::destroy($cid);
            if($result){
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('请选择要删除的板块');
        }

    }
    //无限级分类
    static function tree($data,$pid=0,$level=0)
    {
        //var_dump($data);die;
        static $treeList = array();
        foreach($data as $v){
          if($v['parentid'] == $pid){
            $v['level'] = $level;
            $treeList[] = $v;
            self::tree($data,$v['cid'],$level+1);
          }
        }
        //var_dump($treeList);
        return $treeList;
    }
}