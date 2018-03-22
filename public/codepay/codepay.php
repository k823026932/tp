<?php
/**
 * 功能：接收网站参数 并创建订单
 * 版本：1.8
 * 修改日期：2017-8-2
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究接口使用，只是提供一个参考。
 *
 *
 *************************注意*****************
 * 如果您是软件版您必须制作并先上传收款码或者是金额的二维码。 否则提示无二维码
 * 如果还没上传或不想上传先测试 请使用免挂机模式进行测试(钱到平台账户 只能创建1元以下的订单)
 * 修改文件codepay_config.php中 $codepay_config['act']='1'
 * 1、支付宝二维码制作教程：http://codepay.fateqq.com:52888/help/rknXG3lFx.html
 * 2、微信二维码制作教程：http://codepay.fateqq.com:52888/help/ByLyU3bFl.html
 * 其他操作教程：http://codepay.fateqq.com:52888/help/web/
 *
 *如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 *1、开发文档中心：http://codepay.fateqq.com:52888/apiword/
 *2、商户帮助中心：http://codepay.fateqq.com:52888/help/
 *3、联系客服：http://codepay.fateqq.com:52888/msg.html
 *如果想使用扩展功能,请按文档要求,自行添加到parameter数组即可。
 **********************************************
 */

//session_start(); //开启session
require_once("codepay_config.php"); //导入配置文件
require_once("lib/codepay_submit.class.php"); //导入自动提交类


/**
 * 接收表单的数据 无需改动
 * 需要注意：pay_id 云端限制字符长度为100；太长会返回too long错误
 */


if (empty($_POST)) $_POST = $_GET; //如果为GET方式访问


$user = $_POST['user'];//提交的用户名

$pay_id = $user; //网站唯一标识 需要充值的用户名，用户ID或者自行创建订单 建议传递用户的ID

$price = (float)$_POST["price"]; //提交的价格

$param = ''; //自定义参数  可以留空 传递什么返回什么 用于区分游戏分区或用户身份

$type = (int)$_POST["type"]; //支付方式

if ($type < 1) $type = 1;


if ($price <= 0) $price = (float)$_POST["money"]; //如果没提供自定义输入金额则使用money参数






/**
 *
 * 增加付款记录到数据库的方法演示代码
 * require_once("includes/MysqliDb.class.php");//导入mysqli连接
 * require_once("includes/M.class.php");//导入mysqli操作类
 * $m=new M(); //创建连接数据库类
 *
 * //以下4个参数是为了演示向数据库插入记录
 * $pay_no=time();
 * $pay_time=time();
 * $creat_time=time();
 * $status=0;
 *
 * $sql="INSERT INTO `codepay_order` (`pay_id`, `money`, `price`, `type`, `pay_no`, `param`, `pay_time`, `pay_tag`, `status`, `creat_time`)values(?,?,?,?,?,?,?,?,?,?)";
 * $rs = $m->execSQL( $sql, array('sddissisii', $pay_id, $price, $price, $type, $pay_no, $param, $pay_time, $pay_tag, $status, $creat_time), true); //$rs返回是否执行成功 true表示返回是否成功
 *
 *
 * 查询1条信息演示代码
 * $order_id=$_GET['order_id'];
 * $sql="select * from `codepay_order` where pay_no=?";
 * $rs = $m->getOne( $sql, array('s', $order_id), false); //$rs返回查询到的结果 没有结果则返回false
 *
 *
 */



if ($price < $codepay_config['min']) exit('最低限制' . $codepay_config['min'] . '元'); //检查最低限制

$price = number_format($price, 2, '.', '');// 四舍五入只保留2位小数。

if (empty($codepay_config) || (int)$codepay_config['id'] <= 1) {
    exit('请修改配置文件codepay_config.php  或进入<a href="install.php">install.php</a> 安装码支付接口测试数据');
}




/**
 * 一些防攻击的方法 需要先session_start();
 * 3秒内禁止刷新页面
 * 验证表单是否合法
 */

//$_SESSION["count"] += 1;
//if ($_SESSION["count"] > 20 || ($_SESSION["endTime"] + 3) > time()) {
//    $_SESSION["count"] = 0;
//    exit("您的操作太频繁请重试 <a href='../'>返回重试</a><script>setTimeout(function() {
//  history.back(-1)
//},3000);</script>");
//}
//$_SESSION["endTime"] = time();
//
//$salt = $_POST["salt"]; //验证表单合法性的参数
//
//if ($salt <> md5($_SESSION["uuid"])) exit('表单验证失败 请重新提交'); //防止跨站攻击


if (empty($pay_id)) exit('需要充值的用户不能为空'); //唯一用户标识 不能为空 如果是登录状态可以直接取session中的ID或用户名

if ($codepay_config['pay_type'] == 1 && $type == 1) $codepay_config["qrcode_url"] = ''; //支付宝默认不走本地化二维码

/**
 * 这里可以自行创建站内订单将用户提交的数据保存到数据库生成订单号
 *
 * 嫌麻烦pay_id直接传送用户ID或用户名(中文用户名请确认编码一致)
 * 我们支持GBK,gb2312,utf-8 如发送中文遇到编码困扰无法解决 可以尽量使用UTF-8
 * 万能解决方法：base64或者urlencode加密后发送我们. 处理业务的时候转回来
 */
//构造要请求的参数数组，无需改动
$parameter = array(
    "id" => (int)$codepay_config['id'],//平台ID号
    "type" => $type,//支付方式
    "price" => (float)$price,//原价
    "pay_id" => $pay_id, //可以是用户ID,站内商户订单号,用户名
    "param" => $param,//自定义参数
    "act" => (int)$codepay_config['act'],//是否开启认证版的免挂机功能
    "outTime" => (int)$codepay_config['outTime'],//二维码超时设置
    "page" => (int)$codepay_config['page'],//付款页面展示方式
    "return_url" => $codepay_config["return_url"],//付款后附带加密参数跳转到该页面
    "notify_url" => $codepay_config["notify_url"],//付款后通知该页面处理业务
    "style" => (int)$codepay_config['style'],//付款页面风格
    "pay_type" => $codepay_config['pay_type'],//支付宝使用官方接口
    "qrcode_url" => $codepay_config['qrcode_url'],//本地化二维码
    "chart" => trim(strtolower($codepay_config['chart']))//字符编码方式
    //其他业务参数根据在线开发文档，添加参数.文档地址:https://codepay.fateqq.com/apiword/
    //如"参数名"=>"参数值"
);

//简单的创建订单方式
//ksort($parameter); //重新排序$data数组
//reset($parameter); //内部指针指向数组中的第一个元素
//
//$sign = ''; //初始化需要签名的字符为空
//$urls = ''; //初始化URL参数为空
//
//foreach ($parameter AS $key => $val) { //遍历需要传递的参数
//    if ($val == ''||$key == 'sign') continue; //跳过这些不参数签名
//    if ($sign != '') { //后面追加&拼接URL
//        $sign .= "&";
//        $urls .= "&";
//    }
//    $sign .= "$key=$val"; //拼接为url参数形式
//    $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值
//
//}
//$query = $urls . '&sign=' . md5($sign .$codepay_config['key']); //创建订单所需的参数
//$url = "http://api2.fateqq.com:52888/creat_order/?{$query}"; //支付页面
//
//header("Location:{$url}"); //跳转到支付页面

switch ((int)$codepay_config['page']) {
    case 1:
        //框架显示(简单 全集成好 自动升级)
        require_once("./html/codepay_frame_order.php");
        break;
    case 2:
        //POST请求云端(简单 全集成好 自动升级)
        echo '正在创建订单...';
        $codepaySubmit = new CodepaySubmit($codepay_config);
        echo $codepaySubmit->buildRequestForm($parameter, "post", "确认");
        break;
    case 3:
        //开发模式(难 可自行实现复杂功能 需要自行开发部分)
        require_once("./html/codepay_diy_order.php");
        break;
    case 4:
        //超级模式(复杂 可自行实现复杂功能 需要自行开发部分)
        require_once("./html/codepay_supper_order.php");
        break;
    default:
        require_once("./html/codepay_frame_order.php");
}

?>