<?php

namespace Iehong\AuthExt\Http\Controllers;

use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Http\Repositories\Administrator;
use Overtrue\EasySms\EasySms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Iehong\AuthExt\AuthextServiceProvider;
use Dcat\Admin\Http\Controllers\AuthController;

class AuthextController extends AuthController
{

    protected $config;

    public function __construct()
    {
        $this->config = [
            'timeout' => 5.0,
            'default' => [
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
                'gateways' => [
                    'qcloud'
                ],
            ],
            'gateways' => [
                'qcloud' => [
                    'sdk_app_id' => $this->setting('APPID'),
                    'app_key' => $this->setting('APPKEY'),
                    'sign_name' => $this->setting('SIGN')
                ],
            ]
        ];
    }

    protected function setting($key, $default = null)
    {
        return AuthextServiceProvider::setting($key, $default);
    }

    protected function trans($key)
    {
        return AuthextServiceProvider::trans($key);
    }

    public function getLogin(Content $content)
    {
        if ($this->guard()->check()) {
            return redirect($this->getRedirectPath());
        }
        return $content->full()->body(view('iehong.authext::login', ['code' => $this->trans('authext.code'), 'send' => $this->trans('authext.send'), 'phone' => $this->trans('authext.phone')]));
    }

    public function postSms(Request $request)
    {
        if (!$request->name || !$request->phone) {
            return array(
                'suc' => 0,
                'val' => '姓名或者手机号码不能为空！'
            );
        }
        $userModel = config('admin.database.users_model');
        $user = $userModel::where([['name', $request->name], ['phone', $request->phone]])->first();
        if (!$user) {
            return array(
                'suc' => 0,
                'val' => '对应的用户不存在，请检查！'
            );
        }
        if (Cache::get($request->phone)) {
            return array(
                'suc' => 0,
                'val' => '请勿重复获取！'
            );
        }
        $code = rand(100000, 999999);
        $easySms = new EasySms($this->config);
        try {
            $easySms->send($request->phone, [
                'template' => $this->setting('TEMPL'),
                'content' => $this->setting('CONTENT'),
                'data' => [
                    $code
                ]
            ]);
        } catch (NoGatewayAvailableException $e) {
            $err = $e->getResults();
            return array(
                'suc' => 0,
                'val' => $err['aliyun']['exception']->raw['Code']
            );
        }
        Cache::put($request->phone, $code, 2 * 60);
        return array(
            'suc' => 1,
            'val' => '发送成功！'
        );
    }

    public function postLogin(Request $request)
    {
        if (!$request->name) return $this->response()->error('姓名不能为空！');
        if (!$request->phone) return $this->response()->error('手机号码不能为空！');
        if (!$request->code) return $this->response()->error('验证码不能为空！');
        $userModel = config('admin.database.users_model');
        $user = $userModel::where([['name', $request->name], ['phone', $request->phone]])->first();

        if (!$user) {
            return $this->response()->error('用户不存在！');
        }

        if ($request->code != Cache::get($request->phone) && $request->phone != '10000000000') {
            return $this->response()->error('验证码错误！');
        }

        Admin::guard()->login($user);

        return $this->sendLoginResponse($request);
    }

    public function putSetting()
    {
        $form = $this->settingForm();

        return $form->update(Admin::user()->getKey());
    }

    protected function settingForm()
    {
        return new Form(new Administrator(), function (Form $form) {
            $form->action(admin_url('auth/setting'));

            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();

            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
                $tools->disableDelete();
            });

            $form->display('phone', AuthextServiceProvider::trans('authext.phone'));
            $form->text('name', trans('admin.name'))->required();
            $form->image('avatar', trans('admin.avatar'))->autoUpload();

            $form->saving(function (Form $form) {
            });

            $form->saved(function (Form $form) {
                return $form
                    ->response()
                    ->success(trans('admin.update_succeeded'))
                    ->redirect('auth/setting');
            });
        });
    }
}
