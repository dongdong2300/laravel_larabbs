<?php

return [
    /*
     * 接口频率限制
     */
    'rate_limits' => [
        // 登录相关，默认 10 次数/分钟
        'sign'   => env('SIGN_RATE_LIMITS', '10,1'),
        // 访问频率限制，默认 60 次数/分钟
        'access' => env('ACCESS_RATE_LIMITS', '60,1'),
    ],
];