<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $phone     = $request->phone;
        $captcha   = $captchaBuilder->build();
        $code      = $captcha->getPhrase();
        $key       = 'captcha_' . Str::random(15);
        $expiredAt = now()->addMinutes(2);

        Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        $result = [
            'captcha_key'           => $key,
            'expired_at'            => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];
        if (!app()->environment('production')) {
            $result['captcha_code'] = $code;
        }
        return response()->json($result)->setStatusCode(201);
    }
}
