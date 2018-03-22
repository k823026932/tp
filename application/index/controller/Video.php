<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Upload;
use think\Session;
use think\Page;
use app\index\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;
use app\index\model\User as UserModel;
use app\index\model\Danmu as DanmuModel;
use app\index\model\Pinglun as PinglunModel;
class Video extends Controller
{
  //文件上传表单
  public function upload()
  {
    $user1 = new UserModel();
    if (!empty(Session::get('uid'))) {
        $uid = Session::get('uid');

          $ziliao = $user1->where('uid',$uid)->find();
          $this->assign('ziliao',$ziliao);
      }
    $user = new VideoModel();
    $category = new CategoryModel();
     $lala = $category->where('parentid','=',0)->select();

    $this->assign('lala',$lala);
    $result = $category->where('parentid','<>',0)->select();
    $this->assign('result',$result);
    return $this->fetch();
  }
  //文件上传提交
  public function upload1(){
    //var_dump($_FILES);die;
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
      $this->success('上传成功', 'index/video/upload');
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

  //视频列表
  //加载板块页面
  public function list()
  {
    $user = new VideoModel();
    $user1 = new UserModel();
    $user2 = new CategoryModel();
    $result3 = $user2->where('parentid != 0')->select();
    $result = $user->limit(5)->order('bofangliang desc')->select();
    $result2 = $user1->select();
    $lala = $user2->where('parentid','=',0)->select();
    $this->assign('lala',$lala);
      $lala1 = $user2->select();
      $this->assign('lala1',$lala1);
      $qbzl = $user1->select();
      $this->assign('qbzl',$qbzl);
      if (!empty(Session::get('uid'))) {
        $uid = Session::get('uid');

          $ziliao = $user1->where('uid',$uid)->find();
          $this->assign('ziliao',$ziliao);
      }
     //var_dump($_GET);
     $this->assign('result3',$result3);
     $this->assign('result2',$result2);
     //var_dump($result);
     $this->assign('result',$result);
    return $this->fetch();
  }
  //板块页面切换查询方法
  public function list1()
  {
    $a = $_GET['test'];
    if(!empty($_GET['cid'])){
      $cid = $_GET['cid'];
      $where = "fenqu = $cid";
    }else if(!empty($_GET['parentid'])){
      $parentid = $_GET['parentid'];
      $where = "dabankuai = $parentid";
    }else{
      $where = null;
    }

    $user = new VideoModel();
    $user1 = new UserModel();

    $result2 = $user->where($where)->select();
    $result1 = $user1->userList();
    //var_dump($result1);
    $count = count($result2);
    //var_dump($count);
    $page = new Page(8,$count);
    $limit = $page->limit();
    //var_dump($where);
    if($a == 0){
      $result = $user->where($where)->limit($limit)->select();
      //var_dump($result);
    }else if($a == 1){
      $result = $user->where($where)->limit($limit)->order("bofangliang desc")->select();
      //var_dump($result);
    }else if($a == 2){
      $result = $user->where($where)->limit($limit)->order("pinglun desc")->select();
    }else if($a == 3){
      $result = $user->where($where)->limit($limit)->order("danmu desc")->select();
    }

    //var_dump($result);
    $value['data'] = $result;
    $value['data1'] = $result1;
    $value['allPage'] = $page->allPage();
    return json_encode($value);
  }

  //视频页面
  public function v()
  {



      $video = new VideoModel();
      $user = new UserModel;
      $category = new CategoryModel();
      //查询一级分类
      $lala = $category->where('parentid','=',0)->select();
      $this->assign('lala',$lala);
      //查询所有分类
      $lala1 = $category->select();
      $this->assign('lala1',$lala1);
      //查询全部用户资料
      $qbzl = $user->userList();
      $this->assign('qbzl',$qbzl);
      //播放量增加1
      $vid = $_GET['vid'];
      Db::table('think_video')
      ->where('vid', $vid)
      ->setInc('bofangliang');

      if (!empty(Session::get('uid'))) {
        $uid = Session::get('uid');
          $ziliao = $user->selu($uid);
          //var_dump($ziliao);
          $this->assign('ziliao',$ziliao);
          $ziliao = $user->selu($uid);
          $sc = explode(',',$ziliao['shoucang']);
          foreach ($sc as $key => $value) {
            if ($value == $vid) {
              //var_dump($value);
              $this->assign('sc',$sc);
            }
          }
          
      }
      if (!empty($_GET['vid'])) {
        $vid = $_GET['vid'];
        $shipin = $video->shipin($vid);
        $this->assign('shipin',$shipin);
      }
      $arr = Db::table('think_pinglun')->where('pvideo',$vid)->select();
      $cat = $this->tree($arr);
      $this->assign('cat',$cat);
    return $this->fetch();
  }


  //搜索页面
  function souSuo()
  {
    //var_dump($_POST);
    $video = new VideoModel();
    $user = new UserModel();
    if(!empty($_POST['content'])){
      $content = $_POST['content'];
      $where = "title like '%$content%'";
      $data = $video->where($where)->select();
      $count = count($data);
      //var_dump($data);
      $userList = $user->userList();
     // var_dump($userList);
      $this->assign('content',$content);
      $this->assign('count',$count);
      $this->assign('data',$data);
      $this->assign('userList',$userList);
    }else{
      $this->error('请输入要搜索的内容','/index/index/index');
    }

          //查询全部用户资料
      $qbzl = $user->userList();
      $this->assign('qbzl',$qbzl);
      if (!empty(Session::get('uid'))) {
        $uid = Session::get('uid');

          $ziliao = $user->selu($uid);
          $this->assign('ziliao',$ziliao);
      }

    return $this->fetch();
  }



  //收藏页面
  function shoucang()
  {
    $video = new VideoModel();
    $user = new UserModel();
    $uid = Session::get('uid');
    $userList1 = $user->selu($uid);
    $userList = $user->userList();
    $data = $video->sou(null);
    $a = [];
    foreach($data as $data)
    {
      $vid = explode(',',$userList1['shoucang']);
      if(in_array($data['vid'],$vid)){
         array_push($a,$data);
      }

    }
          //查询全部用户资料
      $qbzl = $user->userList();
      $this->assign('qbzl',$qbzl);
      if (!empty(Session::get('uid'))) {
        $uid = Session::get('uid');

          $ziliao = $user->selu($uid);
          $this->assign('ziliao',$ziliao);
      }
    //var_dump(count($a));
    $count = count($a);


      //$this->assign('content',$content);
      $this->assign('count',$count);
      $this->assign('a',$a);
      $this->assign('userList',$userList);

    return $this->fetch();
  }
  //弹幕查询
  public function danmu()
  {
    $vid = $_POST['vid'];
    //$vid = 6;
    $time = time()-2;
    //var_dump($time);
    $danmu = db('danmu')->where('create_time','>=',$time)->where('dvideo',$vid)->select();
    $data['danmu'] = $danmu;
    echo json_encode($data);
  }
  //添加弹幕
  public function idanmu()
  {
    $dname = Session::get('uid');
    //var_dump($dname);
    $vid = $_POST['vid'];
    $cdanmu = $_POST['danmu'];
    Db::table('think_video')
      ->where('vid', $vid)
      ->setInc('danmu');
    //$vid = '6';
    //$cdanmu = 'GV会比较难卡梅伦';
    $danmu = new DanmuModel();
    $danmu->danmu = $cdanmu;
    $danmu->dvideo = $vid;
    $danmu->dname = $dname;
    $danmu->save();
  }
  //收藏视频
  public function shoucang1()
  {
    $vid = $_POST['vid'];
    //$vid = 6;
    $uid = Session::get('uid');
    $sc = db('user')->where('uid',$uid)->select();
    //var_dump($sc);
    if (!empty($sc[0]['shoucang'])) {
      $shoucang = $sc[0]['shoucang'] . ',' . $vid;
    } else {
      $shoucang = $vid;
    }
    
    $data = Db::table('think_user')->where('uid',$uid)->setField('shoucang', $shoucang);
    Db::table('think_video')->where('vid', $vid)->setInc('shoucangliang');
    if($data){
            echo json_encode(['state'=> 1]);
    }else{
        echo json_encode(['state'=> 0]);
    }
  }
  //取消收藏视频
  public function qxsc()
  {
    $vid = $_POST['vid'];
    //$vid = 6;
    $uid = Session::get('uid');
    $sc = db('user')->where('uid',$uid)->select();
    //var_dump($sc);
    Db::table('think_video')->where('vid', $vid)->setDec('shoucangliang');
    if (!empty($sc[0]['shoucang'])) {
      $shou = explode(',',$sc[0]['shoucang']);
      $shoucang = null;
      foreach ($shou as $key => $value) {
        if ($value != $vid) {
          if (empty($shoucang)) {
            $shoucang .= $value;
          } else {
            $shoucang .= ',' . $value;
          }
          
        }
        
      }
      //var_dump($shoucang);
      //$shoucang = $sc[0]['shoucang'] . ',' . $vid;
    }
    
    $data = Db::table('think_user')->where('uid',$uid)->setField('shoucang', $shoucang);
    if($data){
            echo json_encode(['state'=> 1]);
    }else{
        echo json_encode(['state'=> 0]);
    }
  }


    public function video()
    {
      $pinglun = new PinglunModel();
      $arr = $pinglun->where('pvideo','6')->select();
      $cat = $this->tree($arr);
      $this->assign('cat',$cat);
      //var_dump($cat);
      return $this->fetch(); 

    }


    //无限级分类
    static function tree($data,$pid=0,$level=0)
  {
    // var_dump($data);die;
    static $treeList = array();
    foreach($data as $v){
      if($v['parent_id'] == $pid){
        $v['level'] = $level;
        
        $treeList[] = $v;
        self::tree($data,$v['pid'],$level+1);
      } 
    }
    //var_dump($treeList);
    return $treeList;
  }
  //增加评论
  public function ping()
  {
    $vid = $_POST['vid'];
    $pid = $_POST['pid'];
    $content = $_POST['content'];
    $username = Session::get('username');
    $touxiang = Session::get('touxiang');
    $pinglun = new PinglunModel();
    $pinglun->parent_id = $pid;
    $pinglun->pvideo = $vid;
    $pinglun->content = $content;
    $pinglun->head_pic = $touxiang;
    $pinglun->nickname = $username;
    $data = $pinglun->save();
    if($data){
            echo json_encode(['state'=> 1]);
    }else{
        echo json_encode(['state'=> 0]);
    }  }

    



    


}
