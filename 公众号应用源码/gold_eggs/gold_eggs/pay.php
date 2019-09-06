<?php
class Pay
{
    /*微信企业付款给个人*/  
// <!--具体细节参考微信支付开发文档-->  
// <!--参数 $openid个人openid,$re_user_name个人真实姓名，$amount付款金额，$partner_trade_no商户订单号，$desc描述-->  
// <!--返回 如果付款成功，返回true,否则返回false-->  
 function weixin_transfer_money($openid,$re_user_name,$amount,$partner_trade_no,$desc){  
    //请求参数  
    //公众号appid（字段名）--mch_appid(变量名)--1(必填)  
    //输入你的公众号appid  
    $data['mch_appid']="wx078364682b09cc50";    
      
    //商户号--mchid--1  
    //输入你的商户号  
    $data['mchid']="1490439232";  
      
    //设备号--device_info--0  
    //随机字符串--nonce_str--1  
    $data['nonce_str']=$this->get_unique_value();  
  
    //签名--sign--1  
    $data['sign']="";  
  
    //商户订单号--partner_trade_no--1  
    $data['partner_trade_no']=$partner_trade_no;  
  
    //用户openid--openid--1  
    $data['openid']=$openid;  
      
    //校验用户姓名选项--check_name--1  
    $data['check_name']="NO_CHECK";  
      
    //收款用户姓名--re_user_name--0  
    $data['re_user_name']=$re_user_name;  
  
    //金额--amount--1  
    $data['amount']=$amount;  
  
    //企业付款描述信息--desc--1  
    $data['desc']=$desc;  
  
    //IP地址--spbill_create_ip--1  
    $data['spbill_create_ip']='106.15.203.129';  
  
    //生成签名  
    //对数据数组进行处理  
    //API密钥，输入你的appsecret  
    $appsecret="4qF5xvedhv4oxq43xlynxvfdhqojxqwd";  
    $data=array_filter($data);  
    ksort($data);  
    $str="";  
    foreach($data as $k=>$v){  
        $str.=$k."=".$v."&";  
    }  
    $str.="key=".$appsecret;  
    $data['sign']=strtoupper(MD5($str));  
  
    /* 
        付款操作： 
            1.将请求数据转换成xml 
            2.发送请求 
            3.将请求结果转换为数组 
            4.将请求信息和请求结果录入到数据库中 
            5.判断是否通信成功 
            6.判断是否付款成功 
     */  
  
  
  
    //企业付款接口地址  
    $url="https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";  
  header("Content-type:text/html;charset=utf-8");
    //1.将请求数据由数组转换成xml  
    $xml=$this->arraytoxml($data);  
    //2.进行请求操作  
    $res=$this->curl($xml,$url);  
    //3.将请求结果由xml转换成数组  
    // $arr=$this->xmltoarray($res);  
  
    // //4.将请求信息和请求结果录入到数据库中，可以根据自己的需要进行处理，$arr是返回的结果数组  
    // $transfer['partner_trade_no']=$data['partner_trade_no'];  
    // $transfer['request_data']=serialize($data);      
    // $transfer['response_data']=serialize($arr);  
    // if($arr['return_code']=="SUCCESS" && $arr['result_code']=="SUCCESS"){  
    //     //5. 判断是否通讯成功 6.判断是否付款成功  
    //     $transfer['success']=1;  
    //     $transfer_res['success']=1;  
    // }else{  
    //     $transfer['success']=0;  
    //     $transfer_res['success']=0;  
    //     $transfer_res['desc']=$arr['return_msg'];  
    // }  
    // $transfer['add_time']=time();  
  
    // D("weixin_transfer")->add($transfer);  
      
    return $res;  
}  
  
// 生成32位唯一随机字符串  
private function get_unique_value(){  
    $str=uniqid(mt_rand(),1);  
    $str=sha1($str);  
    return md5($str);  
}  
// 将数组转换成xml  
private function arraytoxml($arr){  
    $xml="<xml>";  
    foreach($arr as $k=>$v){  
        $xml.="<".$k.">".$v."</".$k.">";  
    }  
    $xml.="</xml>";  
    return $xml;  
}  
// 将xml转换成数组  
private function xmltoarray($xml){  
    //禁止引用外部xml实体  
    libxml_disable_entity_loader(true);  
    $xmlstring=simplexml_load_string($xml,"SimpleXMLElement",LIBXML_NOCDATA);  
    $arr=json_decode(json_encode($xmlstring),true);  
    return $arr;  
}  
  
//进行curl操作  
private function curl($xml,$url) {  
    
    //初始化curl  
    $ch = curl_init();                                       
    //抓取指定网页  
     curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch,CURLOPT_HEADER,FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,dirname(__FILE__).'/cert/apiclient_cert.pem');
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, dirname(__FILE__).'/cert/apiclient_key.pem');
        //post提交方式
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
        $data = curl_exec($ch);
         //返回结果
         //返回结果
        if($data){
            curl_close($ch);
            return $this->xmltoarray($data);
        }
        else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>"; 
            curl_close($ch);
            return false;
        }

}  
}