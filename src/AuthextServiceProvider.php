<?php

namespace Iehong\AuthExt;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;

class AuthextServiceProvider extends ServiceProvider
{
  protected $js = [
    'js/index.js',
  ];
  protected $css = [
    'css/index.css',
  ];

  public function register()
  {
    config([
      'admin.auth.except'    => array_merge(config('admin.auth.except'), [
        'auth/login',
        'auth/logout',
        'auth/sms'
      ])
    ]);
  }

  public function init()
  {
    parent::init();
  }

  public function settingForm()
  {
    return new Setting($this);
  }
}
