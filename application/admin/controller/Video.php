<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Page;
use think\Session;
use app\admin\model\User as UserModel;
use app\admin\model\Category as CategoryModel;
use app\admin\model\Video as VideoModel;
class Video extends Controller
{
    public function index()
    {

        return $this->fetch();
    }


    public function index1()
    {
        $video = new VideoModel();
        $result = $video->select();
        //var_dump($result);
        $count = count($result);
        $page = new Page(5,$count);
        $limit = $page->limit();
        $data = $video->limit($limit)->select();
        //var_dump($data);
        $value['data'] = $data;
        $value['allPage'] = $page->allPage();
        echo json_encode($value);
    }
    public function add()
    {
        //var_dump(Session::get('uid'));
        $cate = new CategoryModel();
        $result = $cate->where('parentid != 0')->select();
        //var_dump($result);
        $this->assign('result',$result);
        return $this->fetch();
    }

    public function edit()
    {
        return $this->fetch();
    }

    public function del()
    {
        $id = $_GET['id'];
        $result = VideoModel::destroy($id);
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
            $result = VideoModel::destroy($id);
            if($result){
                echo json_encode(['sta'=> 1]);
            }else{
                echo json_encode(['sta'=> 0]);
            }
        }else{
            echo json_encode(['sta'=> 2]);
        }

    }

    public function tuijian()
    {
        $video = new VideoModel();
        $id = $_GET['id'];
        $result = $video->where('vid',$id)->update(['tuijian'=>1]);
        if($result){
            $this->success('设置成功');
        }else{
            $this->error('设置失败');
        }
    }

    public function quxiao()
    {
        $video = new VideoModel();
        $id = $_GET['id'];
        $result = $video->where('vid',$id)->update(['tuijian'=>0]);
        if($result){
            $this->success('取消成功');
        }else{
            $this->error('取消失败');
        }
    }


    public function lunbo()
    {
        $video = new VideoModel();
        $id = $_GET['id'];
        $result = $video->where('vid',$id)->update(['lunbo'=>1]);
        if($result){
            $this->success('设置成功');
        }else{
            $this->error('设置失败');
        }
    }

    public function quxiaolb()
    {
        $video = new VideoModel();
        $id = $_GET['id'];
        $result = $video->where('vid',$id)->update(['lunbo'=>0]);
        if($result){
            $this->success('取消成功');
        }else{
            $this->error('取消失败');
        }
    }


    public function fufei()
    {
        $video = new VideoModel();
        //var_dump($_GET);
        $id = $_GET['id'];
        $fufei = $_GET['fufei'];
        $result = $video->where('vid',$id)->update(['huiyuan'=>$fufei]);
        if($result){
            $this->success('设置成功');
        }else{
            $this->error('设置失败');
        }
    }
    //上传视频
    public function addVideo()
    {
    // 获取表单上传文件 例如上传了001.jpg
        if (empty($_FILES['vimg']['tmp_name'])) {
          $this->error('上传图片超过规定大小');
        }
        if (empty($_FILES['vpath']['tmp_name'])) {
          $this->error('上传视频超过规定大小');
      }
        $file = request()->file('vimg');
        $video = request()->file('vpath');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>156780000,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        //var_dump($info);
        $vid = $video->validate(['size'=>156780000,'ext'=>'mp4'])->move(ROOT_PATH . 'public' . DS . 'uploads');

        if($info && $vid){
          $vm = new VideoModel();
          $title = $_POST['title'];
          $fenqu = $_POST['fenqu'];

          $category = new CategoryModel();
          $da = $category->selda($fenqu);
          $dabankuai = $da[0]['parentid'];

          $vpath = '/uploads/' . $vid->getSaveName();
          $content = $_POST['content'];
          $vimg = '/uploads/' . $info->getSaveName();
          //$result = $vm->insuser($title,$fenqu,$vimg,$content,$vpath);

          $vm->dabankuai = $dabankuai;
          $vm->title = $title;
          $vm->fenqu = $fenqu;
          $vm->vimg = $vimg;
          $vm->content = $content;
          $vm->vpath = $vpath;
          $vm->uname = Session::get('uid');
          $vm->save();
          $this->success('上传成功', 'admin/video/index');
        // 成功上传后 获取上传信息
        // 输出 jpg
        //echo $info->getExtension();
        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        //echo $info->getSaveName();
        // 输出 42a79759f284b767dfcb2a0197904287.jpg
        //echo $info->getFilename();
        }else{
          $this->error('上传失败');
        // 上传失败获取错误信息
        //echo $file->getError();
        }
    }

    //视频回收站
    public function bycle()
    {
        $result = VideoModel::onlyTrashed()->select();
        //var_dump($result);
        return $this->fetch();
    }
    public function bycle1()
    {
        $result = VideoModel::onlyTrashed()->select();
        //var_dump($result);
        $count = count($result);
        $page = new Page(5,$count);
        $limit = $page->limit();
        $data = VideoModel::onlyTrashed()->limit($limit)->select();
        //var_dump($data);
        $value['data'] = $data;
        $value['allPage'] = $page->allPage();
        echo json_encode($value);
    }

    public function update()
    {
        $video = new VideoModel();
        $id = $_GET['id'];
        //var_dump($id);
        //$result = $video->where('vid', $id)->update(['delete_time' => null]);
        $result = Db::table('think_video')->where("vid=$id")->update(['delete_time'=>null]);
        if($result){
            $this->success('恢复成功');
        }else{
            $this->error('恢复失败');
        }
    }
    public function updateDuo()
    {
        if(!empty($_POST['id'])){
            $id = $_POST['id'];
            $result = Db::table('think_video')->where("vid in ($id)")->update(['delete_time'=>null]);
            if($result){
                echo json_encode(['sta'=> 1]);
            }else{
                echo json_encode(['sta'=> 0]);
            }
        }else{
            echo json_encode(['sta'=> 2]);
        }
    }

    public function shanchu()
    {
        //var_dump($_GET);
        $video = new VideoModel();
        $id = $_GET['id'];
        $result = Db::table('think_video')->delete($id);
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }

    }

    public function duoshan1()
    {
        if(!empty($_POST['id'])){
            $id = $_POST['id'];
            $result = Db::table('think_video')->delete($id);
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