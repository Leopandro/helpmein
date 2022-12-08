<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject ?? 'Email'  }}</title>
</head>
<body style="margin: 0; padding: 0;">

<table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;background-color: #e5e5e5 " width="100%">
    <tr>
        <td align="center">
            <center style="width: 100%;">

                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0; background: #ffffff; " width="100%">
                    <tr>
                        <td align="center">
                            <center style="width: 100%;">
                                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding: 76px 16px 30px; max-width: 572px;" width="100%">
                                    <tr>
                                        <td>
                                            <a href="{{ \App\Infrastructure\Http\Routing\FrontendRouterFacade::route('frontend.main-page') }}" style="-webkit-text-size-adjust:none; display: block; text-decoration: none" target="_blank" title="logo">
                                                <img src="{{ env('AWS_STATIC_ENDPOINT') }}/mail/logo.png" alt="logo" border="0" width="148" height="24" style="display:block;"/>
                                            </a>
                                        </td>
                                        <td width="50"></td>
                                        <td align="right">
                                            <a href="{{ \App\Infrastructure\Http\Routing\FrontendRouterFacade::route('frontend.lk-main') }}" style="-webkit-text-size-adjust:none; display: block; text-decoration: none" target="_blank" title="button">
                                                <img src="{{ env('AWS_STATIC_ENDPOINT') }}/mail/lk.png" alt="button" border="0" width="138" height="36" style="display:block;"/>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>

                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;background-color: #e5e5e5" width="100%">
                    <tr>
                        <td align="center">
                            <center style="width: 100%;">
                                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding: 30px 16px 30px 16px; max-width: 572px;" width="100%">
                                    <tr>
                                        <td>
                                            <font style='font-family: Arial,serif;font-size:30px;line-height:38px;font-weight:bold;color:#161616;margin-bottom:8px;-webkit-text-size-adjust:none;display:block'><b>{{ $userName }}, добро пожаловать в HelpMeIn!</b></font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <font style='font-family: Arial,serif;font-size:22px;line-height:30px;color:#161616;-webkit-text-size-adjust:none;display:block'>Для дальнейшей работы с платформой следуйте простой инструкции:</font>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>

                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0; background-color: #e5e5e5" width="100%">
                    <tr>
                        <td align="center">
                            <center style="width: 100%;">
                                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding: 40px 30px 30px 30px; max-width: 540px;background: #ffffff;border-radius: 8px;" width="100%">
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="88">
                                                        <img src="{{ env('AWS_STATIC_ENDPOINT') }}/mail/icon1.png" alt="icon" border="0" width="88" height="88" style="display:block;"/>
                                                    </td>
                                                    <td width="380">
                                                        @section('text1')

                                                        @show
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="88">
                                                        <img src="{{ env('AWS_STATIC_ENDPOINT') }}/mail/icon2.png" alt="icon" border="0" width="88" height="88" style="display:block;"/>
                                                    </td>
                                                    <td width="380">
                                                        @section('text2')

                                                        @show
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="88">
                                                        <img src="{{ env('AWS_STATIC_ENDPOINT') }}/mail/icon3.png" alt="icon" border="0" width="88" height="88" style="display:block;"/>
                                                    </td>
                                                    <td width="380">
                                                        @section('text3')

                                                        @show
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="88">
                                                        <img src="{{ env('AWS_STATIC_ENDPOINT') }}/mail/icon4.png" alt="icon" border="0" width="88" height="88" style="display:block;"/>
                                                    </td>
                                                    <td width="380">
                                                        @section('text4')

                                                        @show
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="{{ \App\Infrastructure\Http\Routing\FrontendRouterFacade::route('frontend.main-page') }}" style="-webkit-text-size-adjust:none; display: block; text-decoration: none" target="_blank" title="button">
                                                <img src="{{ env('AWS_STATIC_ENDPOINT') }}/mail/btn-web.png" alt="icon" border="0" width="100%" height="auto" style="display:block;"/>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>

                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;background-color: #e5e5e5" width="100%">
                    <tr>
                        <td align="center">
                            <center style="width: 100%;">
                                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:30px 16px 20px 16px;max-width: 572px;" width="100%">
                                    <tr>
                                        <td>
                                            <font style='font-family: Arial,serif;font-size:14px;line-height:20px;color:#8D8D8D;margin-bottom:8px;-webkit-text-size-adjust:none;display:block'>
                                                Вы получили это письмо, так как зарегистрировались на сайте
                                                <a href="{{ \App\Infrastructure\Http\Routing\FrontendRouterFacade::route('frontend.main-page') }}" style="color:#6977D2;font-size: 14px;font-family:Arial,sans-serif;line-height: 20px;-webkit-text-size-adjust:none;" target="_blank" title="helpmein">
                                                    helpmein.ru
                                                </a>
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <font style='font-family: Arial,serif;font-size:14px;line-height:20px;color:#8D8D8D;-webkit-text-size-adjust:none;display:block'>
                                                Не отвечайте на него, мы не сможем получить ваше сообщение. Если вам нужна помощь, то напишите нам в
                                                <a href="{{ \App\Infrastructure\Http\Routing\FrontendRouterFacade::route('frontend.support', []) }}" style="color:#6977D2;font-size: 14px;font-family:Arial,sans-serif;line-height: 20px;-webkit-text-size-adjust:none;" target="_blank" title="helpmein">
                                                    техподдержку
                                                </a>
                                            </font>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>

                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;background-color: #e5e5e5" width="100%">
                    <tr>
                        <td align="center">
                            <center style="width: 100%;">
                                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding: 0 16px 110px 16px; max-width: 572px;" width="100%">
                                    <tr>
                                        <td>
                                            <a  href="tel:88006005767" title="phone" style='font-family: Arial,serif;font-size:16px;line-height:20px;color:#161616;text-decoration:none;-webkit-text-size-adjust:none;display:block'>
                                                8 800 600 57 67
                                            </a>
                                            <a  href="mailto:help@helpmein.ru" title="email" style='font-family: Arial,serif;font-size:16px;line-height:20px;color:#6977D2;-webkit-text-size-adjust:none;display:block'>
                                                help@helpmein.ru
                                            </a>
                                        </td>
                                        <td align="right">
                                            <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;">

                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>

            </center>
        </td>
    </tr>
</table>

</body>
</html>
