<?php

/** 加载助手函数 */
include_once __DIR__.'/functions.php';

/** 加载配置文件 */
list($docProjectPath, $targetProject, $docName,  $scheme, $h5_dev_file, $my_7_map_md_file,$UI_url,$MnsPrefixs,$clientType) = require_once __DIR__ . '/config.php';

$docName_array = explode('_',$docName);
/** 应用市场 */
$appMarket = $docName_array[1].'-'.$docName_array[2];
/** 应用名称小写 */
$smallAppName = $docName_array[1].'_'.$docName_array[2].'_'.$docName_array[2];
/** 生成7.map.md的内容 */
$fieldsData = require_once $docProjectPath . '/docs/' . $docName . '/fields_map.php';
/** 获取混淆数组的值 */
$array = array_values($fieldsData);

/** 生成h5的原始文件 */

$h5_dev_content = <<<eof
## 1119.菲律宾- Peso Pocket IOS

### 基本信息：

> - 文档地址：https://doc.alta-dg.mx/{$docName}/
> - ui 地址: {$UI_url}
> - 原生 scheme： {$scheme}
> - 原生产品 id： 'product_id' => '{$fieldsData['product_id']}',

### 登陆态：

- http://8.212.182.12:8856/water/pso/poisson?steadily=50031ee754a409c0b1b1aa3683907893
- 9000000001

### 原生页映射

```
  'setting' => '{$array[12]}',   //设置页
  'main' => '{$array[13]}',     //首页
  'login' => '{$array[14]}',     //登录
  'order' => '{$array[15]}',          //订单
  'productDetail' => '{$array[16]}',    //产品详情
```

### h5 路由混淆

```
'errorUrl' => '/{$array[17]}', //'/jointLogonFail',     //准入失败页面
'loanDetailUrl' => '/{$array[18]}', // '/loanDetailsV3',     //借款详情页
'orderUrl' =>  '/{$array[19]}', //'/loanDetailsV3',     //订单详情页面
'confirmUrl' => '/{$array[20]}', //'/confirmOfLoanV3',     //借款详情页
'confirmNewUrl' => '/{$array[21]}', //'/confirmUrl',     //后置确认详情页
'confirmOfPeriod' => '/{$array[22]}', //'/confirmOfLoanV3',     //借款详情页
'repayUrl' =>  '/{$array[23]}', //'/repaymentOfPeriod',     //还款页
'customerService' =>  '/{$array[24]}', //'/loanDetailsV3',     //订单详情页面
'loanAgreement' =>  '/{$array[25]}', //'/loanDetailsV3',     //借款协议
'aboutUs' =>  '/{$array[26]}', //'/loanDetailsV3',     //关于我们
'privacyPolicy' =>  '/{$array[27]}', //'/privacyPolicy',      //隐私协议
'repayDelayUrl' => '/{$array[28]}',  //展期
'complaint' => '/{$array[29]}',  //客服案件投诉入口
'bindBank' => '/{$array[30]}',  //绑卡页面
```

### 参数混淆

```
 'productId' => '{$fieldsData['productId']}',   联调值=1
 'orderId' => '{$fieldsData['orderId']}',     联调值=2
```

### 原生 h5 交互函数

```
{$array[31]}(String url)页面跳转
{$array[32]}()关闭当前webview
{$array[33]}(String url, String params)带参数页面跳转
{$array[34]}()回到首页，并关闭当前页
{$array[35]}()回到个人中心，并关闭当前页
{$array[36]}()跳转到登录页，并清空页面栈
{$array[37]}(String phone)拨打电话号码（客户端补充"tel:"）
{$array[38]}() app store评分功能
{$array[39]}() 确认申请埋点调用方法
{$array[40]}() 开始绑卡时间
{$array[41]}() 结束绑卡时间
```

eof;
$h5_dev_file = $docProjectPath . '/docs/h5-dev/' . $h5_dev_file;
if (file_put_contents($h5_dev_file, $h5_dev_content)) {
    echo "创建文件{$h5_dev_file}成功\r\n";
} else {
    echo "创建文件{$h5_dev_file}失败\r\n";
}


/** 生成 7.map.md 的内容 */
$map_7_md = <<<eof
## 7. 值映射

### 首页元素
- 原版
```json
'"type":"BANNER"',
'"type":"LARGE_CARD"',
'"type":"SMALL_CARD"',
'"type":"PRODUCT_LIST"',
```
- 混淆后
```json
'"type":"{$array[0]}"',
'"type":"{$array[1]}"',
'"type":"{$array[2]}"',
'"type":"{$array[3]}"',
```

### 产品详情认证项目列表
- 原版
```json
'"taskType":"public"', // ocr认证
'"taskType":"personal"', // 个人信息
'"taskType":"job"', // 个人信息
'"taskType":"ext"', // 紧急联系人
'"taskType":"bank"', // 绑卡
```
- 混淆后
```json
'"taskType":"{$array[4]}"',
'"taskType":"{$array[5]}"',
'"taskType":"{$array[6]}"',
'"taskType":"{$array[7]}"',
'"taskType":"{$array[8]}"',
```

### 认证项组件
- 原版
```json
'"cate":"enum"',
'"cate":"txt"',
'"cate":"citySelect"',
```
- 混淆后
```json
'"cate":"{$array[9]}"',
'"cate":"{$array[10]}"',
'"cate":"{$array[11]}"',
```

### 原生页
- 原版
```
xx://xxxx/setting     设置
xx://xxxx/main     首页
xx://xxxx/login     登录
xx://xxxx/order?tab=1    订单
xx://xxxx/productDetail?product_id=2    产品详情
```
- 混淆后
```
{$scheme}{$array[12]}     设置
{$scheme}{$array[13]}     首页
{$scheme}{$array[14]}     登录
{$scheme}{$array[15]}?{$fieldsData['tab']}=1    订单 
{$scheme}{$array[16]}?{$fieldsData['product_id']}=2   产品详情  

```

### 认证项code
```json
{
"name":"name",
"mobile":"mobile",
"id_number":"id_number",
"tax_card":"tax_card",
"id_card_back":"id_card_back",
"face":"face",
"emergent":"emergent",
"education":"education",
"marriage":"marriage",
"company_name":"company_name",
"company_address":"company_address",
"family_monthly_salary":"family_monthly_salary",
"purpose":"purpose",
"live":"live",
"home_city":"home_city",
"residentaddress":"residentaddress",
"home_pin_code":"home_pin_code",
"email":"email",
"job_type":"job_type",
"position":"position",
"work_length":"work_length",
"spouse_name":"spouse_name",
"children_num":"children_num",
"pay_method":"pay_method",
"work_industry":"work_industry",
"company_full_address":"company_full_address",
"company_pincode":"company_pincode",
"monthly_income":"monthly_income",
"company_phone":"company_phone",
"salary_day":"salary_day",
"salary_type":"salary_type",
"sex":"sex",
"postalcode":"postalcode"
}
eof;

$map_7_md_file = $docProjectPath . '/docs/' . $docName . '/' . $my_7_map_md_file;
if (file_put_contents($map_7_md_file, $map_7_md)) {
    echo "创建文件{$map_7_md_file}成功\r\n";
} else {
    echo "创建文件{$map_7_md_file}失败\r\n";
}

/** 修改文件 common/services/UrlService.php 修改方法public static function getAppPageUrl（） 里面的pagesMap数组 */

$getAppPageUrlStart = <<<eof
    public static function getAppPageUrl(\$page, \$params = [])
    {
eof;
$getAppPageUrlEnd = <<<eof
        \$paramsMap = require Yii::getAlias('@frontend') . '/config/maps/' . APP_NAME . '-fields_map.php';

        \$dstParams = [];
        foreach (\$params as \$key => \$value) {
            \$key = \$paramsMap[\$key];
            \$dstParams[\$key] = \$value;
        }

        \$url = Yii::\$app->params['scheme'].\$pagesMap[\$page];
        if (\$dstParams) {
            \$url .= '?'.self::buildQuery(\$dstParams);
        }

        return \$url;
    }

eof;

$getAppPageUrlReplace = <<<eof

\$pagesMap = [
            'main' => '{$array[12]}',     //首页
            'setting' => '{$array[13]}',   //设置
            'login' => '{$array[14]}',     //登录
            'order' => '{$array[15]}',          //订单
            'productDetail' => '{$array[16]}',    //产品详情
            'coupon' => 'coupon',    //优惠券
            'certify'=>'equalled'  //认证列表
        ];
        
eof;


replaceFile($targetProject, 'common/services/UrlService.php', $getAppPageUrlStart, $getAppPageUrlEnd, $getAppPageUrlReplace);

/** 修改 common/services/thirdapi/CertifyService.php 修改方法public static function replaceCate（）方法 的 $replaceNow数组*/

$CertifyServiceStart = <<<eof
    public static function replaceCate\(array \$data\)\: array
eof;
$CertifyServiceEnd = <<<eof
        \$str = str_replace(\$replaceOrg, \$replaceNow, \$str);
        return json_decode(\$str, true);
    }
eof;

$CertifyServiceReplace = <<<eof

        //多包差异化
        \$str = json_encode(\$data);

        \$replaceOrg = [
            '"cate":"enum"',
            '"cate":"txt"',
            '"cate":"citySelect"',
        ];

        \$replaceNow = [
            '"cate":"{$array[9]}"',
            '"cate":"{$array[10]}"',
            '"cate":"{$array[11]}"',
        ];
        
eof;

replaceFile($targetProject, 'common/services/thirdapi/CertifyService.php', $CertifyServiceStart, $CertifyServiceEnd, $CertifyServiceReplace);

/** 然后修改文件frontend/controllers/v3/ProductController.php 修改方法public static function replaceCate（） */

$ProductControllerStart = <<<eof
public static function replaceCate(array \$data): array
    {
        \$str = json_encode(\$data);
        \$replaceOrg = [
            '"taskType":"public"',
            '"taskType":"personal"',
            '"taskType":"job"',
            '"taskType":"ext"',
            '"taskType":"bank"',
        ];
eof;

$ProductControllerEnd = <<<eof
       \$str = str_replace(\$replaceOrg, \$replaceNow, \$str);
        return json_decode(\$str, true);
    }
eof;

$ProductControllerReplace = <<<eof

 \$replaceNow = [
            '"taskType":"{$array[4]}"',
            '"taskType":"{$array[5]}"',
            '"taskType":"{$array[6]}"',
            '"taskType":"{$array[7]}"',
            '"taskType":"{$array[8]}"',
        ];
        
eof;

replaceFile($targetProject, 'frontend/controllers/v3/ProductController.php', $ProductControllerStart, $ProductControllerEnd, $ProductControllerReplace);

/** 修改文件frontend/controllers/v4/IndexController.php 修改方法public static function replaceCate（） */

$IndexControllerStart = <<<eof
public static function replaceCate(array \$data): array
    {
        \$str = json_encode(\$data);
        \$replaceOrg = [
            '"type":"BANNER"',
            '"type":"LARGE_CARD"',
            '"type":"SMALL_CARD"',
            '"type":"PRODUCT_LIST"',
        ];
eof;
$IndexControllerEnd = <<<eof
\$str = str_replace(\$replaceOrg, \$replaceNow, \$str);
        return json_decode(\$str, true);
    }
eof;

$IndexControllerReplace = <<<eof

\$replaceNow = [
            '"type":"{$array[0]}"',
            '"type":"{$array[1]}"',
            '"type":"{$array[2]}"',
            '"type":"{$array[3]}"',
        ];
        
eof;

replaceFile($targetProject, 'frontend/controllers/v4/IndexController.php', $IndexControllerStart, $IndexControllerEnd, $IndexControllerReplace);


/** 修改文件 common/services/UrlService.php 修改方法public static function getH5PageUrl(） */

$UrlServiceGetH5PageUrlStart = <<<eof
public static function getH5PageUrl(\$page, \$params = [])
    {
eof;
$UrlServiceGetH5PageUrlEnd = <<<eof
\$paramsMap = require Yii::getAlias('@frontend') . '/config/maps/' . APP_NAME . '-fields_map.php';

        \$dstParams = [];
        foreach (\$params as \$key => \$value) {
            \$key = \$paramsMap[\$key];
            \$dstParams[\$key] = \$value;
        }

        \$url = Yii::\$app->params['H5_URL'] . \$pagesMap[\$page];
        if (\$dstParams) {
            \$url .= '?' . self::buildQuery(\$dstParams);
        }
        return \$url;
    }
eof;

$UrlServiceGetH5PageUrlReplace = <<<eof

\$pagesMap = [
            'errorUrl' => '/{$array[17]}', //'/jointLogonFail',     //准入失败页面
            'loanDetailUrl' => '/{$array[18]}', // '/loanDetailsV3',     //借款详情页
            'orderUrl' =>  '/{$array[19]}', //'/loanDetailsV3',     //订单详情页面
            'confirmUrl' => '/{$array[20]}', //'/confirmOfLoanV3',     //借款详情页
            'confirmNewUrl' => '/{$array[21]}', //'/confirmUrl',     //后置确认详情页
            'confirmOfPeriod' => '/{$array[22]}', //'/confirmOfLoanV3',     //借款详情页
            'repayUrl' =>  '/{$array[23]}', //'/repaymentOfPeriod',     //还款页
            'customerService' =>  '/{$array[24]}', //'/loanDetailsV3',     //订单详情页面
            'loanAgreement' =>  '/{$array[25]}', //'/loanDetailsV3',     //借款协议
            'aboutUs' =>  '/{$array[26]}', //'/loanDetailsV3',     //关于我们
            'privacyPolicy' =>  '/{$array[27]}', //'/privacyPolicy',      //隐私协议
            'repayDelayUrl' => '/{$array[28]}',  //展期
            'complaint' => '/{$array[29]}',  //客服案件投诉入口
            'bindBank' => '/{$array[30]}',  //绑卡页面
        ]; //todo

eof;

replaceFile($targetProject, 'common/services/UrlService.php', $UrlServiceGetH5PageUrlStart, $UrlServiceGetH5PageUrlEnd, $UrlServiceGetH5PageUrlReplace);


/** 接下来迁移 混淆数组fields_map文件 */


$originFieldFileContent = <<<eof
<?php

\$map = require __DIR__ . '/{$smallAppName}-fields_map.php';

foreach (\$_GET as \$key => \$value) {
    if (in_array(\$key, \$map)) {
        \$k = array_search(\$key, \$map);
        \$_GET[\$k] = \$value;
    }
}

\$_GET['appMarket'] = '{$appMarket}';
\$_GET['clientType'] = '{$clientType}';

foreach (\$_POST as \$key => \$value) {
    if (in_array(\$key, \$map)) {
        \$k = array_search(\$key, \$map);
        \$_POST[\$k] = \$value;
    }
}

eof;
$originFieldFile = $targetProject . '/frontend/config/maps/' . $smallAppName . '.php';
if (file_put_contents($originFieldFile, $originFieldFileContent)) {
    echo "创建文件{$originFieldFile}完成\r\n";
} else {
    echo "创建文件{$originFieldFile}失败\r\n";
}
$targetFieldsDataFile = $targetProject . '/frontend/config/maps/' . $smallAppName . '-fields_map.php';
$contentField = file_get_contents($docProjectPath . '/docs/' . $docName . '/fields_map.php');
if (file_put_contents($targetFieldsDataFile, $contentField)) {
    echo "创建文件{$targetFieldsDataFile}成功\r\n";
} else {
    echo "创建文件{$targetFieldsDataFile}失败\r\n";
}
/** 迁移路由文件 */

$targetLoadFile = $targetProject . '/frontend/config/url_rules/load.php';
$loadFileContent = <<<eof
<?php
\$url_rules = require __DIR__ . '/default.php';
if (file_exists(__DIR__ . '/' . APP_NAME . '.php')) {
    \$rules = require __DIR__ . '/' . APP_NAME . '.php';
    \$url_rules = array_merge(\$url_rules, \$rules);
}
return \$url_rules;
eof;
if (file_put_contents($targetLoadFile, $loadFileContent)) {
    echo "创建{$targetLoadFile}成功\r\n";
} else {
    echo "创建{$targetLoadFile}成功\r\n";
}

$targetUrlFile = $targetProject . '/frontend/config/url_rules/' . $smallAppName . '.php';
$targetUrlContent = require_once $docProjectPath . '/docs/' . $docName . '/url_map.php';

$targetUrlContent = <<<eof
<?php

\$map = [
$targetUrlContent
];

return array_flip(\$map);
eof;

if (file_put_contents($targetUrlFile, $targetUrlContent)) {
    echo "创建{$targetUrlFile}成功\r\n";
} else {
    echo "创建{$targetUrlFile}失败\r\n";
}

/** 修改env.example.php文件 */
$envFile = $targetProject . '/env.example.php';
$envFileContent = <<<eof
<?php
defined('ENV') OR define('ENV', 'prod');//本地开发 dev, 测试 test 生产prod
defined('ENV_DEBUG') OR define('ENV_DEBUG', true);//环境调试 true / false
define('APP_NAME', '{$smallAppName}');
define('OLD_APP_NAME', '{$smallAppName}');
define('NEED_CONNECT_OLD_APP', true);
eof;
if (file_put_contents($envFile, $envFileContent)) {
    echo "创建文件{$envFile}成功\r\n";
} else {
    echo "创建文件{$envFile}失败\r\n";
}

/** 修改枚举类型文件common/enums/MnsQueueName.php  */

$MnsQueueNameStart = <<<eof
<?php

namespace common\enums;
eof;

$MnsQueueNameEnd = <<<eof
defined('MNS_QUEUE_PREFIX') or define('MNS_QUEUE_PREFIX', \$prefixs[APP_NAME]);
eof;

$MnsQueueNameReplace = <<<eof

    \$prefixs = $MnsPrefixs;

eof;

replaceFile($targetProject,'common/enums/MnsQueueName.php',$MnsQueueNameStart,$MnsQueueNameEnd,$MnsQueueNameReplace);

echo "请人工验证修改的文件是否正确。\r\n  
真假产品common/models/merchant/MerchantProduct.php ,\r\n
environments/prod的数据库，接口，日志，oss配置，\r\n
global-php的枚举类型 \r\n";

echo "注意修改h5_dev的开发文档{$h5_dev_file}里面的确认用款页的接口地址，用户账号，联调值的orderId\r\n";







