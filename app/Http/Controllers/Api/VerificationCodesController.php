<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone     = $request->phone;
        $code      = '123456';// 默认验证码
        $key       = 'verificationCode_' . Str::random(15);// 短信验证码，缓存 key
        $expiredAt = now()->addMinutes(5);// 短信验证码，缓存超时时间

        if (app()->environment('production')) {
            // 生产环境，验证码随机生成 6 位随机数
            $code = random_int(100000, 999999);
            try {
                $result = $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data'     => [
                        'code' => $code
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }

        // 缓存验证码 5 分钟过期。
        Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        $result = [
            'key'        => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ];
        return response()->json($result)->setStatusCode(201);
    }
}