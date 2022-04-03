<?php

namespace Iehong\AuthExt;

use Dcat\Admin\Extend\Setting as Form;

class Setting extends Form
{
  public function form()
  {
    //1400538650
    //823a92144ebc7f8cd2e546af0ec3f468
    //紫叶技术分享
    //1013714
    //验证码[{1}]，若非本人操作，请勿泄露。
    $this->text('APPID')->required();
    $this->text('APPKEY')->required();
    $this->text('SIGN')->required();
    $this->text('TEMPL')->required();
    $this->text('CONTENT')->required();
  }
}
