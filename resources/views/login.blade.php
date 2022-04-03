<style>
  .login-box {
    margin-top: -10rem;
    padding: 5px;
  }

  .login-card-body {
    padding: 1.5rem 1.8rem 1.6rem;
  }

  .card,
  .card-body {
    border-radius: .25rem
  }

  .login-btn {
    display: block;
    margin: auto;
    height: 36px;
    line-height: 36px;
    padding: 0 20px !important;
    letter-spacing: 0.2em;
    text-indent: 0.2em;
  }

  .content {
    overflow-x: hidden;
  }

  .sendcode {
    line-height: 34px;
    height: 34px;
    padding: 0 !important;
    letter-spacing: 0.2em;
    text-indent: 0.2em;
    border-radius: 0.2em !important;
  }
</style>

<div class="login-page bg-40">
  <div class="login-box">
    <div class="login-logo mb-2">
      {{ config('admin.name') }}
    </div>
    <div class="card">
      <div class="card-body login-card-body shadow-100">
        <p class="login-box-msg mt-1 mb-1">{{ __('admin.welcome_back') }}</p>
        <form id="login-form" method="POST" action="{{ admin_url('auth/login') }}">
          @csrf
          <input type="hidden" name="smsurl" value="{{ admin_url('auth/sms') }}">
          <fieldset class="form-label-group form-group position-relative has-icon-left">
            <input type="text" class="form-control" name="name" placeholder="{{ __('admin.username') }}" value="{{ old('username') }}" required>
            <div class="form-control-position">
              <i class="feather icon-user"></i>
            </div>
          </fieldset>
          <fieldset class="form-label-group form-group position-relative has-icon-left">
            <input type="text" class="form-control" name="phone" placeholder="{{ $phone }}" required>
            <div class="form-control-position">
              <i class="feather icon-smartphone"></i>
            </div>
          </fieldset>

          <div class="form-label-group form-group position-relative has-icon-left row">
            <div class="col-8" style="padding-right: 0">
              <input type="text" class="form-control" placeholder="{{ $code }}" name="code" value="{{ old('code') }}" required>
              <div class="form-control-position">
                <i class="fa fa-copyright"></i>
              </div>
            </div>
            <div class="col-4">
              <button type="button" class="btn btn-success btn-block btn-flat sendcode">{{ $send }}</button>
            </div>
          </div>
          <button type="submit" class="btn btn-primary login-btn">
            {{ __('admin.login') }}
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  Dcat.ready(function() {
    // ajax表单提交
    $('#login-form').form({
      validate: true,
    });
    $('button.sendcode').click(function() {
      $.ajax({
        type: 'POST',
        url: $('input[name=smsurl]').val(),
        data: {
          _token: $('input[name=_token]').val(),
          name: $('input[name=name]').val(),
          phone: $('input[name=phone]').val()
        },
        success: function(mes) {
          if (mes.suc) {
            toastr.success(mes.val);
          } else {
            toastr.error(mes.val);
          }
        },
      });
    });

  });
</script>
