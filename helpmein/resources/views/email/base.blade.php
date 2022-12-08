<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject ?? 'Email'  }}</title>
    <style>
        ul {
            margin-block-start: 0px;
        }
        li {
            font-family: Arial,serif;
            font-size:16px;
        }
        body {
            font-family: 'OpenSans', sans-serif;
            font-weight: 400;
            font-size: 13px;
            line-height: 20px;
            background-color: #fff;
        }
        .btn {
            display: inline-block;
            width: 100%;
            height: 40px;
            line-height: 40px;
            font-size: 14px;
            border-radius: 4px;
            background-color: #6977d2;
            color: #fff;
            text-align: center;
        }
        .btn--big {
            height: 56px;
            line-height: 56px;
        }
        a {
            text-decoration: none;
            color: #6977d2;
        }
    </style>
    @stack('style')
</head>
<body style="margin: 0; padding: 0;">

<table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;background-color: #E5E5E5 " width="100%">
    <tr>
        <td align="center">
            <center style="max-width: 600px;background: #ffffff;">

                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0; border-bottom: 1px solid #F3F3F3" width="100%">
                    <tr>
                        <td align="center">
                            <center style="width: 100%">
                                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding: 30px 20px 30px 20px; max-width: 580px;" width="100%">
                                    <tr>
                                        <td>
                                            <a href="{{ \App\Infrastructure\Http\Routing\FrontendRouterFacade::route('frontend.main-page') }}" style="-webkit-text-size-adjust:none; display: block; text-decoration: none" target="_blank" title="logo">
                                                <div class="" style="background-color:#3699FF;text-align: center;color:white;width:80px;height:30px;display: table-cell;vertical-align: middle;">Helpmein</div>
                                            </a>
                                        </td>
                                        <td align="right">
                                            <font style='font-family: Arial,serif;font-size:12px;line-height:16px;color:#8D8D8D;-webkit-text-size-adjust:none;display:block'>
                                                Пн-Пт 8:00 - 18:00
                                            </font>
                                            <a  href="tel:88006005767" title="phone" style='font-family: Arial,serif;font-size:16px;line-height:20px;color:#161616;text-decoration:none;-webkit-text-size-adjust:none;display:block'>
                                                8 999 999 99 99
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>

                @section('content')
                    <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;" width="100%">
                        <tr>
                            <td align="center">
                                <center style="width: 100%;">
                                    <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding: 30px 20px 30px 20px; max-width: 580px;" width="100%">
                                        @yield('content-inner')
                                    </table>
                                </center>
                            </td>
                        </tr>
                    </table>
                @show

                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;" width="100%">
                    <tr>
                        <td align="center">
                            <center style="width: 100%;">
                                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:30px 16px 20px 16px;max-width: 572px;" width="100%">
                                    <tr>
                                        <td>
                                            <font style='font-family: Arial,serif;font-size:14px;line-height:20px;color:#8D8D8D;margin-bottom:8px;-webkit-text-size-adjust:none;display:block'>
                                                Вы получили это письмо, так как зарегистрировались на сайте
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

                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;" width="100%">
                    <tr>
                        <td align="center">
                            <center style="width: 100%;">
                                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding: 0 16px 110px 16px; max-width: 572px;" width="100%">
                                    <tr>
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
