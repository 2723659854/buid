<?php


/**
 * @purpose 百度定位解析类
 * @author nobody
 * @time 2024年2月22日15:19:09
 */
class Location {

    public  $url = 'https://api.map.baidu.com/geocoding/v3';
    public  $ak = 'mF0vxmxnPJA7aYulrYfQyCEtuke4k2hR';


    /**
     * 根据地名获取经纬度
     * @param string $name 中文地名
     * @return bool|string
     */
    public function getLocationByName (string $name = '北京市海淀区上地十街10号'){
        // 构造请求参数
        $param['address']   = $name;
        $param['output']   = 'json';
        $param['ak']   = $this->ak;
        $param['callback']   = 'showLocation';
        return $this->request_get($this->url, $param) ;
    }

    /**
     * 请求公共方法
     * @param string $url 请求地址
     * @param array $param 请求参数
     * @return bool|string
     * @throws Exception
     */
    private function request_get(string $url = '', array $param = array()) {
        if (empty($url) || empty($param)) {
            return false;
        }

        $getUrl = $url . "?" . http_build_query($param);
        $curl = curl_init(); // 初始化curl
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_URL, $getUrl); // 抓取指定网页
        curl_setopt($curl, CURLOPT_TIMEOUT, 1000); // 设置超时时间1秒
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // curl不直接输出到屏幕
        curl_setopt($curl, CURLOPT_HEADER, 0); // 设置header
        $data = curl_exec($curl); // 运行curl

        curl_close($curl);

        if (!$data) {
            throw new Exception("an error occured in function request_get(): " . curl_error($curl) );
        }
        return $data;
    }
}

/** 调用 */
$class = new Location();
var_dump($res = $class->getLocationByName('北京市海淀区上地十街10号'));
?>