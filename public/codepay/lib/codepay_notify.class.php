<?php
/* *
 * 类名：CodePayNotify
 * 功能：码支付通知处理类
 * 详细：处理码支付各接口通知返回
 * 版本：1.0
 * 日期：2016-12-11
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究码支付接口使用，只是提供一个参考

 *************************注意*************************
 * 调试通知返回时，可查看或改写log日志的写入TXT里的数据，来检查通知返回是否正常
 */

require_once("codepay_core.function.php");
require_once("codepay_md5.function.php");

class CodePayNotify
{

    var $codepay_config;

    function __construct($codepay_config)
    {
        $this->codepay_config = $codepay_config;
    }

    function CodePayNotify($codepay_config)
    {
        $this->__construct($codepay_config);
    }

    /**
     * 针对GET及POST验证消息是否是码支付发出的合法消息
     * @return 验证结果
     */
    function verifyAll()
    {
        if (!empty($_POST)) {//判断POST来的数组是否为空
            foreach ($_POST as $key => $data) {
                $_GET[$key] = $data;
            }
        }
        if (!empty($_GET)&&$this->getSignVeryfy($_GET, $_GET["sign"])) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 针对notify_url验证消息是否是码支付发出的合法消息
     * @return 验证结果
     */
    function verifyNotify()
    {
        if (empty($_POST)) {//判断POST来的数组是否为空
            logResult('POST为空');
            return false;
        } else {
            if ($this->getSignVeryfy($_POST, $_POST["sign"])) {
                return true;

            } else {
                return false;
            }
        }
    }

    /**
     * 针对return_url验证消息是否是码支付发出的合法消息
     * @return 验证结果
     */
    function verifyReturn()
    {
        if (empty($_GET)) {//判断POST来的数组是否为空
            return false;
        } else {
            //生成签名结果
            $isSign = $this->getSignVeryfy($_GET, $_GET["sign"]);
            if ($isSign) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    function getSignVeryfy($para_temp, $sign)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = paraFilter($para_temp);
        //对待签名参数数组排序
        $para_sort = argSort($para_filter);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = createLinkstring($para_sort);

        $isSgin = md5Verify($prestr, $sign, $this->codepay_config['key']);
        return $isSgin;
    }

}

?>
