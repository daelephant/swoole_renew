<?php
require_once "./Public/qcloudsms/src/index.php";
use Qcloud\Sms\SmsSingleSender;
function filterUrl($param){
    //先取出当前的URL地址
    $url = $_SERVER['PHP_SELF'];
    //正则去掉某个参数
    //http://www.2018.com/index.php/Home/Search/cat_search/cat_id/1/brand_id/1-%E5%B0%8F%E7%B1%B3
    $re = "/\/$param\/[^\/]+/";//匹配/brand_id/1-%E5%B0%8F%E7%B1%B3这个字符串，分析，$param后绝不是斜线/的字符，即非斜线的字符出现1+次
    return preg_replace($re,'',$url);
}

/**
 *为一个订单生成支付宝按钮
 *
 */
function makeAlipayBtn($orderId,$btnName = '去支付宝支付'){
    return require('./alipay/alipayapi.php');
}

/**
 * 使用一个表中的数据制作下拉框
 *
 */
function buildSelect($tableName, $selectName, $valueFieldName, $textFieldName, $selectedValue = '')
{
	$model = D($tableName);
	$data = $model->field("$valueFieldName,$textFieldName")->select();
	$select = "<select name='$selectName'><option value=''>请选择</option>";
	foreach ($data as $k => $v)
	{
		$value = $v[$valueFieldName];
		$text = $v[$textFieldName];
		if($selectedValue && $selectedValue==$value)
			$selected = 'selected="selected"';
		else 
			$selected = '';
		$select .= '<option '.$selected.' value="'.$value.'">'.$text.'</option>';
	}
	$select .= '</select>';
	echo $select;
}
function deleteImage($image = array())
{
	$savePath = C('IMAGE_CONFIG');
	foreach ($image as $v)
	{
		unlink($savePath['rootPath'] . $v);
	}
}
/**
 * 上传图片并生成缩略图
 * 用法：
 * $ret = uploadOne('logo', 'Goods', array(
			array(600, 600),
			array(300, 300),
			array(100, 100),
		));
	返回值：
	if($ret['ok'] == 1)
		{
			$ret['images'][0];   // 原图地址
			$ret['images'][1];   // 第一个缩略图地址
			$ret['images'][2];   // 第二个缩略图地址
			$ret['images'][3];   // 第三个缩略图地址
		}
		else 
		{
			$this->error = $ret['error'];
			return FALSE;
		}
 *
 */
function uploadOne($imgName, $dirName, $thumb = array())
{
	// 上传LOGO
	if(isset($_FILES[$imgName]) && $_FILES[$imgName]['error'] == 0)
	{
		$ic = C('IMAGE_CONFIG');
		$upload = new \Think\Upload(array(
			'rootPath' => $ic['rootPath'],
			'maxSize' => $ic['maxSize'],
			'exts' => $ic['exts'],
		));// 实例化上传类
		$upload->savePath = $dirName . '/'; // 图片二级目录的名称
		// 上传文件 
		// 上传时指定一个要上传的图片的名称，否则会把表单中所有的图片都处理，之后再想其他图片时就再找不到图片了
		$info   =   $upload->upload(array($imgName=>$_FILES[$imgName]));
		if(!$info)
		{
			return array(
				'ok' => 0,
				'error' => $upload->getError(),
			);
		}
		else
		{
			$ret['ok'] = 1;
		    $ret['images'][0] = $logoName = $info[$imgName]['savepath'] . $info[$imgName]['savename'];
		    // 判断是否生成缩略图
		    if($thumb)
		    {
		    	$image = new \Think\Image();
		    	// 循环生成缩略图
		    	foreach ($thumb as $k => $v)
		    	{
		    		$ret['images'][$k+1] = $info[$imgName]['savepath'] . 'thumb_'.$k.'_' .$info[$imgName]['savename'];
		    		// 打开要处理的图片
				    $image->open($ic['rootPath'].$logoName);
				    $image->thumb($v[0], $v[1])->save($ic['rootPath'].$ret['images'][$k+1]);
		    	}
		    }
		    return $ret;
		}
	}
}



function showWxImage($url, $width = '', $height = ''){
    $ic = C('IMAGE_CONFIG');
    if($width)
        $width = "width='$width'";
    if($height)
        $height = "height='$height'";
    echo "<img $width $height src='{$ic['wxPath']}$url' />";
}
function showImage($url, $width = '', $height = '')
{
	$ic = C('IMAGE_CONFIG');
	if($width)
		$width = "width='$width'";
	if($height)
		$height = "height='$height'";
	echo "<img $width $height src='{$ic['viewPath']}$url' />";
}
// 有选择性的过滤XSS --》 说明：性能非常低-》尽量少用
function removeXSS($data)
{
	require_once './HtmlPurifier/HTMLPurifier.auto.php';
	$_clean_xss_config = HTMLPurifier_Config::createDefault();
	$_clean_xss_config->set('Core.Encoding', 'UTF-8');
	// 设置保留的标签
	$_clean_xss_config->set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]');
	$_clean_xss_config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
	$_clean_xss_config->set('HTML.TargetBlank', TRUE);
	$_clean_xss_obj = new HTMLPurifier($_clean_xss_config);
	// 执行过滤
	return $_clean_xss_obj->purify($data);
}
//发送短信
function sendSms($vipStatus,$phoneNum,$from,$only_num,$evaluate_price)
{

    // 短信应用SDK AppID
      $appid = 1400168158; // 1400开头

    // 短信应用SDK AppKey
        $appkey = "1b5b4c04a9ad8649e59e5b95a2390a22";

    // 需要发送短信的手机号码
    //    $phoneNumbers = ["21212313123", "12345678902", "12345678903"];
        $phoneNumbers = ["$phoneNum"];

    // 短信模板ID，需要在短信应用中申请


        $smsSign = "奢无忧"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`


    try {
        $ssender = new SmsSingleSender($appid, $appkey);
        if ($vipStatus == 'vip'){
            $templateId = 247294;
            $params = ["$from","$only_num","$evaluate_price"];
            $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId,
                $params, $smsSign, "", "");
        }else{
            $templateId = 247293;
            $params = ["$only_num","$evaluate_price"];
            $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId,
                $params, $smsSign, "", "");
        }
        $rsp = json_decode($result);
        echo "发送短信成功";
    } catch(\Exception $e) {
        echo var_dump($e);
    }

}

//发送短信拒绝评估
function refuseSendSms($vipStatus,$phoneNum,$from,$only_num,$evaluate_price)
{

    // 短信应用SDK AppID
    $appid = 1400168158; // 1400开头

    // 短信应用SDK AppKey
    $appkey = "1b5b4c04a9ad8649e59e5b95a2390a22";

    // 需要发送短信的手机号码
    //    $phoneNumbers = ["21212313123", "12345678902", "12345678903"];
    $phoneNumbers = ["$phoneNum"];

    // 短信模板ID，需要在短信应用中申请


    $smsSign = "奢无忧"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`


    try {
        $ssender = new SmsSingleSender($appid, $appkey);
        if ($vipStatus == 'vip'){
            $templateId = 302975;
            $params = ["$from","$only_num","$evaluate_price"];
            $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId,
                $params, $smsSign, "", "");
        }else{
            $templateId = 302980;
            $params = ["$only_num","$evaluate_price"];
            $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId,
                $params, $smsSign, "", "");
        }
        $rsp = json_decode($result);
        echo "发送短信成功";
    } catch(\Exception $e) {
        echo var_dump($e);
    }

}

//发送短信
function setVipsendSms($phoneNum)
{

    // 短信应用SDK AppID
    $appid = 1400168158; // 1400开头

    // 短信应用SDK AppKey
    $appkey = "1b5b4c04a9ad8649e59e5b95a2390a22";

    // 需要发送短信的手机号码
    //    $phoneNumbers = ["21212313123", "12345678902", "12345678903"];
    $phoneNumbers = ["$phoneNum"];

    // 短信模板ID，需要在短信应用中申请


    $smsSign = "奢无忧"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`


    try {
        $ssender = new SmsSingleSender($appid, $appkey);

            $templateId = 264683;
            $params = [];
            $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId,
                $params, $smsSign, "", "");

        $rsp = json_decode($result);
        echo "发送短信成功";
    } catch(\Exception $e) {
        echo var_dump($e);
    }

}
