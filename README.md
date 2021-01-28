### 微信公众号、小程序、APP授权

##### 微信公众号授权 示例
```
// +-----------------------------------------------------------
// | 日志记录
// | 自行封装需要实现 LoggerInterface 接口类
// +-----------------------------------------------------------
$logger = new Logger(LOG_PATH, true);

// +-----------------------------------------------------------
// | 第1步
// | 获得CODE
// +-----------------------------------------------------------

// 参数配置方式1
 /*
 $option = new CodeOption([
 'appid'=> 'wx1111111111111111',
 'scope'=> 'snsapi_userinfo',
 'state'=> 'xxx',
 'redirect_uri'=> '',
 ]);
 */

// 参数配置方式2
$option = new CodeOption();
$option->setAppId('wx1111111111111111');
$option->setRedirectUri('http://cli.life/auth/wechat');
$option->setScope($option::SCOPE_SNSAPI_USERINFO);
$option->setState('xxx');

// 实例化 用于获取code也可自行组装URL
$code = new Code($option, $logger);

// 生成跳转到微信平台授权URL
// 从微信平台跳转回来会携带 code和state
$url = $code->makeUrl();

// 打印接口返回信息
$logger->print($url, true);

// 前台展示授权按钮
// $logger->print("<a href=\"{$url}\">授权</a>", true);

// +-----------------------------------------------------------
// | 第2步
// | 获得 ACCESS-TOKEN和OPENID
// +-----------------------------------------------------------

// 配置参数
/*
 $option = new AccessTokenOption([
 'appid'=> '',
 'secret'=> '',
 'code'=> '',
 ]);
 */

$option = new AccessTokenOption();
$option->setAppId('wxf584e0d5640d7cc1');
$option->setSecret('eba4effd57f3781d30d1a0f7e7a5f5db');
$option->setCode('code');

$token = new AccessToken($option, $logger);

$result = $token->request();

$logger->print($result->original, true);

$logger->print($result->openid);

// +-----------------------------------------------------------
// | 第3步
// | 获得 授权信息
// +-----------------------------------------------------------
/*
 $option = new UserInfoOption([
 'openid'=> '',
 'access_token'=> '',
 'lang'=> 'zh_CN'
 ]);
 */

$option = new UserInfoOption();
$option->setOpenId('openid');
$option->setAccessToken('token');
$option->setLang();

$userinfo = new UserInfo($option, $logger);

$result = $userinfo->request();

$logger->print($result->original);
$logger->print($result->nickname);
```

##### 小程序code2session 示例
```
// +-----------------------------------------------------------
// | 日志记录
// | 自行封装需要实现 LoggerInterface 接口类
// +-----------------------------------------------------------
$logger = new Logger(LOG_PATH, true);

// +-----------------------------------------------------------
// | 获得登录信息
// +-----------------------------------------------------------
// 配置参数
/*
$option = new AccessTokenOption([
    'appid'=> '',
    'secret'=> '',
    'code'=> '',
]);
*/
$option = new AccessTokenOption();
$option->setAppId('wxf584exxxxcc1');
$option->setSecret('eba4exxxxxxf7e7a5f5db');
$option->setCode("023c550w3LL7KV2HmX2w3Z4PMj1c550v");

$token = new AccessToken($option, $logger);

//
// $with_miniprogram 为 true 
// 程序自动差异处理
//
$result = $token->request(true);

$logger->print($result->original, true);

$logger->print($result->openid);
```

##### 刷新 ACCESS TOKEN
```
// +-----------------------------------------------------------
// | 刷新 token 仅APP和公众号可用
// +-----------------------------------------------------------
/*
$option = new AccessTokenOption();
$option->setAppId('adfasdfadasdfadf');
$option->setRefreshToken('ea0d1a0f7e7a5f5db');

$token = new AccessToken($option, $logger);

$result = $token->refresh();

$logger->print($result->original, true);

$logger->print($result->access_token);
*/
```

##### 校验 ACCESS TOKEN
```
// +-----------------------------------------------------------
// | 校验 token 仅APP和公众号可用
// +-----------------------------------------------------------
$option = new AccessTokenOption();
$option->setOpenId('adfasdfadasdfadf');
// 将第2步获得的 refresh_token 自行存储
// 在需要的时刷新
$option->setAccessToken('ea0d1a0f7e7a5f5db');

$token = new AccessToken($option, $logger);

$result = $token->check();

$logger->print($result->original, true);

$logger->print($result->errmsg);
```

##### 解密小程序敏感数据
```
// 实例化传入参数
$decrypt = new Decrypt('iv', 'data', 'session_key');

// 解密成功
if($decrypt->handle()) {
    // 解密的所有数据
    print_r($decrypt->decrypt);
    // 获得解密数据中的某个字段
    echo $decrypt->nickName;
} else {
    echo $decrypt->error;
}
```