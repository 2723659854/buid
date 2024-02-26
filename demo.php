<?php

class GelunPayService
{

    /** 返回码  */
    public static $code = [
        '000' => '成功',
        '999' => '失败',
        '100' => '参数错误',
        '101' => '参数格式不符',
        '102' => '金额超限',
        '400' => '请求异常',
        '401' => 'Token错误或失效',
        '402' => '签名错误',
        '403' => '该交易已存在',
        '404' => '该交易不存在',
        '405' => '服务不可用',
        '406' => 'IP 白名单拒绝',
        '407' => '渠道未配置',
        '408' => '渠道不可用',
        '409' => '商户手续费未配置',
        '500' => '系统错误',
        '201' => '商户余额不足',
        '202' => '账户黑名单拒绝',
        '203' => '账户信息有误',
        '204' => '银行转账失败',
    ];

    /** 证件类型 */
    public static $cardType = [
        'NIT' => '税号',
        'CC' => '当地人身份证',
        'CE' => '外国人身份证',
        'PP' => '护照',
        'TI' => '学生证',
        'RC' => '居民签证',
    ];

    /** 进账单状态 */
    public static $incomeOrderStatus = [
        'INIT_ORDER' => '创建订单',
        'NO_PAY' => '未支付',
        'SUCCESS' => '支付成功',
        'PAY_ERROR' => '支付失败',
        'REFUND' => '已退款',
    ];
    /** 进账支付方式 */
    public static $incomePayWay = [
        'Pse' => '线上付款',
        'Nequi' => '使用钱包进行付款',
        'Efecty' => '使用线下便利店进行付款',
    ];

    /** 银行编码 */
    public static $bankCode = [
        '1059' => 'BANCAMIA S.A.',
        '1040' => 'BANCO AGRARIO',
        '1052' => 'BANCO AV VILLAS',
        '1013' => 'BANCO BBVA COLOMBIA S.A.',
        '1032' => 'BANCO CAJA SOCIAL',
        '1066' => 'BANCO COOPERATIVO COOPCENTRAL',
        '1558' => 'BANCO CREDIFINANCIERA',
        '1051' => 'BANCO DAVIVIENDA',
        '1001' => 'BANCO DE BOGOTA',
        '1023' => 'BANCO DE OCCIDENTE',
        '1062' => 'BANCO FALABELLA',
        '1012' => 'BANCO GNB SUDAMERIS',
        '1006' => 'BANCO ITAU',
        '1060' => 'BANCO PICHINCHA S.A.',
        '1002' => 'BANCO POPULAR',
        '1065' => 'BANCO SANTANDER COLOMBIA',
        '1069' => 'BANCO SERFINANZA',
        '1303' => 'BANCO UNION antes GIROS',
        '1007' => 'BANCOLOMBIA',
        '1061' => 'BANCOOMEVA S.A.',
        '1283' => 'CFA COOPERATIVA FINANCIERA',
        '1009' => 'CITIBANK',
        '1370' => 'COLTEFINANCIERA',
        '1292' => 'CONFIAR COOPERATIVA FINANCIERA',
        '1291' => 'COOFINEP COOPERATIVA FINANCIERA',
        '1289' => 'COTRAFA',
        '1097' => 'DALE',
        '1551' => 'DAVIPLATA',
        '1637' => 'IRIS',
        '1070' => 'LULO BANK',
        '1801' => 'MOVII S.A.',
        '1151' => 'RAPPIPAY DAVIPLATA',
        '1019' => 'SCOTIABANK COLPATRIA',
        '2209' => 'NEQUI',
    ];

    /** 出款订单状态  */
    public static $outcomeOrderStatus = [
        '0' => '待处理',
        '1' => '处理中',
        '2' => '成功',
        '4' => '失败',
    ];
    /** 当前环境 dev prod */
    public static $env = 'dev';
    /** 测试环境请求地址 */
    public $devRequestUrl = 'https://sanbox-openapi-co.eastpay.top';

    /** 正式环境请求地址 */
    public $proRequestUrl = 'https://openapi-co.eastpay.top';

    /** 商户id */
    public $merchantId = "MSF20240225222121003";

    /** 加密公钥 */
    private $publicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzP6Gp6B/cAmeNpiNcAFnlzIiBcxNM9FpgZiVT7aTF4gmBYvP9+QlNE0591Bim169LwYYDWd2xofSmaFkOtQuaRRk3FmlVz2arodvahQ2XZQXk88oaSO2VdcigWctRxTw+x2hzZj+IdqmlUC1BHIPVlJ/ED96Zu7I2NWPgHQ/WaTGvk/aqdw4AcxbOJAg7efSXyru2NhY/F81vCuYTpRIHQHaRxmn4Pp+5eHjypGBLXkHNIOZ8gjBFtzfJ2NAnuCgYSHlRHi7RuIY+x4rQGKN/8ZhvrN9KXSnDH1/U8cQ9yOrzO0JaUJyc1QUOf7z3gOe18Avo/6qVTZMAYqVQD57ywIDAQAB";

    /** 加密私钥 */
    private $privateKey = "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDM/oanoH9wCZ42mI1wAWeXMiIFzE0z0WmBmJVPtpMXiCYFi8/35CU0TTn3UGKbXr0vBhgNZ3bGh9KZoWQ61C5pFGTcWaVXPZquh29qFDZdlBeTzyhpI7ZV1yKBZy1HFPD7HaHNmP4h2qaVQLUEcg9WUn8QP3pm7sjY1Y+AdD9ZpMa+T9qp3DgBzFs4kCDt59JfKu7Y2Fj8XzW8K5hOlEgdAdpHGafg+n7l4ePKkYEteQc0g5nyCMEW3N8nY0Ce4KBhIeVEeLtG4hj7HitAYo3/xmG+s30pdKcMfX9TxxD3I6vM7QlpQnJzVBQ5/vPeA57XwC+j/qpVNkwBipVAPnvLAgMBAAECggEAJVNO92Ss75yAt1YtorBOyWPyd/XMNNwzhspenYYT4FwE+EuN0Yg0EgLhxBtZ6QpmDhLg/EfTg7Clcx3E30VVMgLpnomgJnNsH80/RyRpBb76UAqPRB2hjJ3AhalgwCma+05Y8NOjqkxiuphunWGbU09wHWhBhE6EOZ8hIvWHt22Kvf5HVEks0Z5Ze8fhv6PtLj1dt8ciIPoqm/+Kw164ZGtadZgJ7FfiVZwl0lDSDoVpagKp7HZaKNbTCnTfISUojlltrc8VKlpLiVSlJk4+c3o8HNal7kv0ZyST0iP7on+SyJJ0NxIS6DX8zsf6RywKBxw/r4BfvaMixUG0ArJ0WQKBgQD0RXdz/D0bxvs8qGAaEOYEPyVv/EW2D/MPYWkuucWxbxYU7DMotui++gZF9t5DOEo6p2g1R78UOxHtdHxWdJQ0tV81m6vUvlOdg9+7xTU8N226oWVD4QxLbRVPgklA2DItFcC6N/CpEoT9YQNQsASh4NEuEGmNgedpLOW7t0uKBQKBgQDW1kXvCyVyikEPPi+bm6V5jagW8IyAuZJ+0tnHrTBVhfWT3Scqsfqb05sDDeOA59Q0NB4pfqruKGF2NWy0iDbNYQSnZRP5qQbWLXZ18Infl/nstPD41sBi76RdEal0E6IRy4FrHQTFO0oqVPyPArJlcCNFM7qLB6KLYRvYLMFHjwKBgFLuSrHcDzshAScE2yu+VPlYHvO4KEq5e4HbRoSQmae12T9dObk28Cn2ZK7YM3mK4NitAVolc6AAtgNyKsyHY9HJSfu/UEU4INc3cHwlZf99qglUxjaXbz8kV7nYt9oGDHL2EaqnV1NvcCs+BowcVlZHJ32GIO/kDJbOoWhrwH+pAoGARpIsdtGag32W0YMD8IG4Ya1+wcpS3RYYYUCx+T2S2wUeHmxPKV89i2J/UQmG6hk9Q8i6/7Z0P7dUDJiQFN2J4v0zoik53pD/het5NlTFdYVeu7rUTWl92QIPY+MhXtf0LRREdwMZRhCr1CwGqpOgNmIXs+vCI1lms5I6q1BITVECgYASgsfbJfYnmFJlfp0AgUlDEFgpO4cgUkdMYbelFfL1iaCAIvs9LkW+PzLSKo1+0LhuJM1FB00IjUGjN6oBsAHY456WwFCxUtNrO/0X7I5gKy7neAU2fYWLFl2jdf2l1k0TJMGhW6eWAO8y/MZb6yB/k3tH9O4mIGv1yZBnQewvCA==";

    /** api秘钥 */
    public $apiSecret = "1e7bf5c0-479d-4936-a720-c6b32fe4243a";

    /** 缓存token */
    private $accessToken = '';

    /** token 类型 */
    private $tokenType = '';

    /** 缓存有效期 */
    private $expiresIn = 0;

    /** 进账支付通知地址 */
    public $incomeNotifyUrl = 'https://378d0a5c.r23.cpolar.top/income-notify';

    /** 汇款支付通知地址 */
    public $outcomeNotifyUrl = 'https://378d0a5c.r23.cpolar.top/outcome-notify';

    /**
     * 初始化，直接获取token
     * GelunPayService constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->getToken();
    }

    /**
     * 公共请求方法
     * @param string $url 请求地址
     * @param array $postFields body参数
     * @param array $headers header参数
     * @return array|mixed 返回值
     */
    public function request(string $url, array $postFields, array $headers = [])
    {

        /** 不同环境使用不同的baseUrl */
        if (self::$env=='dev'){
            $url = $this->devRequestUrl . $url;
        }else{
            $url = $this->proRequestUrl . $url;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//设置头部
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); //若果报错 name lookup timed out 报错时添加这一行代码
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $res = curl_exec($ch);
        curl_close($ch);
        if (!$res) {
            $errorMsg = curl_errno($ch) . '-' . curl_error($ch);
            //Yii::info('支付失败信息'.$errorMsg,'appInfo');
            return ['code' => "-1", 'message' => $errorMsg];
        } else {
            return json_decode($res, true);
        }
    }

    /**
     * 生成获取token的签名
     *
     */
    public function makeTokenSignature()
    {
        $timestamp = $this->getTimestamp();
        $strToSign = $this->merchantId . '|' . $timestamp;
        return ['timestamp' => $timestamp, 'sign' => $this->getSHA256Sign($strToSign)];
    }

    /**
     * sha256加密
     * @param string $content
     * @return string
     */
    public function getSHA256Sign(string $content)
    {
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($this->privateKey, 64, "\n", true) .  "\n-----END RSA PRIVATE KEY-----";
        $key = openssl_get_privatekey($privateKey);
        openssl_sign($content, $signature, $key, "SHA256");
        openssl_free_key($key);
        return base64_encode($signature);
    }

    /**
     * 普通接口生成签名
     * @param string $path
     * @param array $params
     * @return array
     */
    public function generateSign(string $path, array $params):array
    {
        $jsonString = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
        $timestamp = $this->getTimestamp();
        $requestBodyStr = hash('sha256', $jsonString);
        $requestBodyStr = strtolower($requestBodyStr);
        $strToSign = "POST:" . $path . ":" . $this->accessToken . ":" . $requestBodyStr . ":" . $timestamp;
        $sign = base64_encode(hash_hmac('sha512', $strToSign, $this->apiSecret, true));
        return ['sign' => $sign, 'timestamp' => $timestamp];
    }

    /**
     * 获取毫秒时间戳
     * @return int
     */
    public function getTimestamp():int
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return substr($msectime, 0, 13);
    }


    /**
     * 使用私钥加密
     * @param $data
     * @return string
     */
    public function privateRSAEncrypt($data)
    {
        $platforPrivateKey = '-----BEGIN PRIVATE KEY-----' . "\n" . $this->privateKey . "\n" . '-----END PRIVATE KEY-----';
        $piKey = openssl_pkey_get_private($platforPrivateKey);
        openssl_private_encrypt($data, $encryptData, $piKey);
        return base64_encode($encryptData);
    }

    /**
     * 使用公钥解密
     * @param $data
     * @return mixed
     */
    public function publicRSADecrypt($data)
    {
        $publicKey = '-----BEGIN PUBLIC KEY-----' . "\n" . $this->publicKey . "\n" . '-----END PUBLIC KEY-----';
        $data = base64_decode($data);
        $puKey = openssl_pkey_get_public($publicKey);
        openssl_public_decrypt($data, $decryptData, $puKey);
        return $decryptData;
    }

    /**
     * 获取token
     * @throws Exception
     */
    public function getToken()
    {
        $url = '/gateway/v1.0/access-token';
        $sign = $this->makeTokenSignature();
        $headers = [
            'X-TIMESTAMP:'.$sign['timestamp'],
            'X-MERCHANT-ID:' .$this->merchantId,
            'X-SIGNATURE:' . $sign['sign'],
            'Content-Type:'.'application/json',
        ];
        $res = $this->request($url, ['grant_type' => 'client_credentials'], $headers);

        if ($res['code'] == '000') {
            $this->accessToken = $res['access_token'];
            $this->tokenType = $res['token_type'];
            $this->expiresIn = $res['expires_in'];
        } else {
            throw new Exception($res['message']);
        }
    }

    /**
     * 生成订单号
     * @return string
     */
    public static function makeOrder():string{
        return md5(time().uniqid());
    }

    /**
     * 创建进账单
     * @return array|mixed|string[]
     */
    public function createIncomePay()
    {
        $url = '/gateway/v1.0/payIn/create-bill';
        $params = [
            'method' => 'Pse',
            'partner_trx_id' => $orderSn=self::makeOrder(),
            'amount' => 199.88,
            'customer_name' => 'Jack Test',
            'email' => 'test@gmail.com',
            'phone' => '3211234567',
            'identity_type' => 'CC',
            'identity_number' => '3215545610',
            'expiry_time' => '2024-12-21 18:00:00',
            'notify_url' => $this->incomeNotifyUrl,
            'product_desc' => 'Test pay'
        ];

        $sign = $this->generateSign($url, $params);

        $headers = [
            'Content-Type: application/json',
            'X-TIMESTAMP: ' . $sign['timestamp'],
            'Authorization: ' . $this->tokenType . ' ' . $this->accessToken,
            'X-SIGNATURE: ' . $sign['sign'],
        ];
        $data = $this->request($url, $params, $headers);

        if (self::$env=='dev'){
            $this->modeNotify($orderSn);
        }
        return $data;
    }

    /**
     * 模拟支付成功和失败
     * @param string $orderSn
     * @param string $type BILL/CASH
     * @return array|mixed|string[]
     */
    public function modeNotify(string $orderSn,string $type = 'BILL')
    {

        $url = "/gateway/test/simulation";
        $params = [
            'merchant_id' => $this->merchantId,
            'type' => $type,
            'partner_trx_id' => $orderSn
        ];

        return $this->request($url, $params, ['Content-Type:application/json']);
    }

    /**
     * 校验签名
     * @return bool
     */
    public function checkSign(array $params):bool
    {
        if (empty($params)) return false;
        $signature = $params['signature'] ?? '';
        ksort($params);
        $string = '';
        foreach ($params as $key => $value) {
            if ($key != 'signature'){
                if (is_float($value)){
                    $value = sprintf('%.2f', $value);
                }
                $string .=$key.'='.$value.'&';
            }
        }
        $string = trim($string,'&');
        $sign =  base64_encode(hash_hmac('sha512', $string, $this->apiSecret,true));
        if ($sign == $signature) {
            return true;
        }
        return false;
    }

    /**
     * 查询进账单状态
     * @param string $orderSn
     * @return array|mixed|string[]
     */
    public function queryIncomeOrder(string $orderSn = 'def4bb5e12c0f474e98dd7e959e46926')
    {
        $url = '/gateway/v1.0/payIn/inquiry';
        $params = [
            'type' => 'BILL',
            'partner_trx_id' => $orderSn
        ];

        $sign = $this->generateSign($url, $params);
        $headers = [
            'Content-Type: application/json',
            'X-TIMESTAMP: ' . $sign['timestamp'],
            'Authorization: ' . $this->tokenType . ' ' . $this->accessToken,
            'X-SIGNATURE: ' . $sign['sign'],
        ];

        return $this->request($url, $params, $headers);
    }

    /**
     * 发起汇款
     * @return array|mixed|string[]
     */
    public function makeOutcomePay()
    {
        $url = '/gateway/v1.0/cashOut/submit';
        $params = [
            "partner_trx_id" => $orderSn=self::makeOrder(),
            "amount" => 10000,
            "charging_fees_method" => 1,
            "recipient_bank_code" => "1007",
            "recipient_account_type" => 0,
            "recipient_account" => "5414515151",
            "recipient_account_name" => "Jack Test",
            "email" => "test@gmail.com",
            "phone" => "3211234567",
            "identity_type" => "CC",
            "identity_number" => "3215545610",
            "notify_url" => "https://sanbox-openapi-co.eastpay.top/gateway/test/notify",
            "transfer_desc" => "Test Transfer"
        ];

        $sign = $this->generateSign($url, $params);
        $headers = [
            'Content-Type: application/json',
            'X-TIMESTAMP: ' . $sign['timestamp'],
            'Authorization: ' . $this->tokenType . ' ' . $this->accessToken,
            'X-SIGNATURE: ' . $sign['sign'],
        ];
        $data = $this->request($url, $params, $headers);
        if (self::$env=='dev'){
            $this->modeNotify($orderSn,'CASH');
        }
        return $data;
    }

    /**
     * 查询汇款单状态
     * @param string $orderSn
     * @return array|mixed|string[]
     */
    public function queryOutcomeOrder(string $orderSn = '562b640985bbf4fb7363952a62e54125')
    {
        $url = '/gateway/v1.0/cashOut/inquiry';
        $params = [
            "partner_trx_id" => $orderSn,
        ];
        $sign = $this->generateSign($url, $params);
        $headers = [
            'Content-Type: application/json',
            'X-TIMESTAMP: ' . $sign['timestamp'],
            'Authorization: ' . $this->tokenType . ' ' . $this->accessToken,
            'X-SIGNATURE: ' . $sign['sign'],
        ];
        return $this->request($url, $params, $headers);
    }
}

/** 测试 */
$class = new GelunPayService();

print_r($incomeOrder = $class->createIncomePay());

//print_r($class->queryIncomeOrder());
//print_r($class->makeOutcomePay());
//print_r($class->queryOutcomeOrder());




