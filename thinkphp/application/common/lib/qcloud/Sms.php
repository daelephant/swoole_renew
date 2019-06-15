<?php
/**
 * Created by PhpStorm.
 * User: yin
 * Date: 2019/6/14
 * Time: 23:16
 */
namespace app\common\lib\qcloud;
require_once APP_PATH . '/../extend/qcloudsms/src/index.php';
use Qcloud\Sms\SmsSingleSender;

//发送短信
class Sms
{
    private $appid;
    private $appkey;
    private $smsSign;

    function __construct()
    {
        // 短信应用SDK AppID
        $this->appid = 1400168158; // 1400开头
        // 短信应用SDK AppKey
        $this->appkey = "1b5b4c04a9ad8649e59e5b95a2390a22";
        $this->smsSign = "奢无忧"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`
    }

    public function sendSms($phoneNum, $smsCode, $expire)
    {
        // 短信模板ID，需要在短信应用中申请
        $templateId = 309086;
        // 需要发送短信的手机号码
        // $phoneNumbers = ["21212313123", "12345678902", "12345678903"];
        $phoneNumbers = ["$phoneNum"];

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);

            $params = ["$smsCode", "$expire"];
            $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId,
                $params, $this->smsSign, "", "");
            $rsp = json_decode($result);
            echo $result;
        } catch (\Exception $e) {
            echo var_dump($e);
        }

    }

}
