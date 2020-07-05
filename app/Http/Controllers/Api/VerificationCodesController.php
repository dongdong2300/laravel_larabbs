<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captchaData = Cache::get($request->captcha_key);

        if (!$captchaData) {
            abort(403, '图片验证码已失效');
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 清理图片验证码缓存
            Cache::forget($request->captcha_key);
            throw new AuthenticationException('验证码错误');
        }

        $phone     = $captchaData['phone'];
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
        // 清理图片验证码缓存
        Cache::forget($request->captcha_key);

        $result = [
            'key'        => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ];
        if (!app()->environment('production')) {
            $result['sms_code'] = $code;
        }
        return response()->json($result)->setStatusCode(201);
    }
}