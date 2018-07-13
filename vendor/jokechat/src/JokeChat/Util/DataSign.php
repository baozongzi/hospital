<?php
namespace  JokeChat\Util;
/**
 * 数据签名 加密/解密类库
 * @author jokechat
 * @date 2017年4月1日
 */
class DataSign
{
    /**
     * 数据签名认证
     * @param array $data 被认证的数据
     * @return string  签名
     */
    public function data_auth_sign($data)
    {
        if(!is_array($data))
        {
            $data = (array)$data;
        }
        ksort($data);
        $code = http_build_query($data); 
        $sign = sha1($code); 
        return $sign;
    }
}
?>