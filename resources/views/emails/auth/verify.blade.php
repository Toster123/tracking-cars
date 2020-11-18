<!DOCTYPE html>
<html lang="en" style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;font-size:10px;-webkit-tap-highlight-color:rgba(0, 0, 0, 0);">
<body class="menubar-hoverable header-fixed " style='-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;margin:0;font-family:"Roboto", sans-serif, Helvetica, Arial, sans-serif;font-size:13px;line-height:1.846153846;color:#313534;background-color:#ffffff;height:100%;background:#e5e6e6;background-size:100% 100%;-ms-overflow-style:scrollbar;'>
<div class="col-sm-12" style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:relative;min-height:1px;padding-left:12px;padding-right:12px;float:left;width:100%;">
                    <br style="-webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;"><span class="text-lg text-bold text-primary" style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;color:#0aa89e;font-weight:700;font-size:125%;">Здравствуйте, {{$user->name}}!</span>
                    <br style="-webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;"><br style="-webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;"><div class="form-group" style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;margin-bottom:19px;position:relative;">
                        <label style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:inline-block;max-width:100%;margin-bottom:0;font-weight:400;font-size:12px;opacity:0.5;">Ваша почта была указанна при регистрации, если вы этого не делали, то просто проигнорируете это письмо. Если вы хотите завершить регстрацию, подтвердив свою почту, то нажмите на кнопку "Подтвердить"</label>
                    </div>
                    <br style="-webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;"><div class="row" style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;margin-left:-12px;margin-right:-12px;">
                        <div class="col-xs-6 text-left" style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;text-align:left;position:relative;min-height:1px;padding-left:12px;padding-right:12px;float:left;width:50%;">
                            <div class="checkbox checkbox-inline checkbox-styled" style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:relative;display:inline-block;margin-top:10px;margin-bottom:0;padding-left:20px;vertical-align:middle;font-weight:normal;cursor:pointer;">
                                <label style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:inline-block;max-width:100%;margin-bottom:0;font-weight:normal;min-height:24px;padding-left:20px;cursor:pointer;">
                                </label>
                            </div>
                        </div>
                        <!--end .col -->
                        <div class="col-xs-6 text-right" style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;text-align:right;position:relative;min-height:1px;padding-left:12px;padding-right:12px;float:left;width:50%;">
                            <a href="{{route('verify.token', $user->verify_token)}}" class="btn btn-primary btn-raised" style="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;background-color:#0aa89e;color:#ffffff;text-decoration:none;display:inline-block;margin-bottom:0;font-weight:normal;text-align:center;vertical-align:middle;touch-action:manipulation;cursor:pointer;background-image:none;border:1px solid transparent;white-space:nowrap;padding:4.5px 14px;font-size:14px;line-height:1.846153846;border-radius:2px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;text-transform:uppercase;border-color:#0aa89e;">Подтвердить</a>
                        </div>
                        <!--end .col -->
                    </div>
                    <!--end .row -->
                </div>
                <!--end .col -->
</body>
</html>
