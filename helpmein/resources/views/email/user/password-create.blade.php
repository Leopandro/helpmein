@extends('email.base')

@section('content')
    <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;" width="100%">
        <tr>
            <td align="center">
                <br>
                @include('email.blocks.text', [
                    'text' => 'Ваш преподаватель приглашает вас присоединиться к Платформе HelpMeIn.'
                ])
                @include('email.blocks.text', [
                    'text' => 'Перейдите по ссылке, чтобы задать пароль для вашего личного кабинета.'
                ])
            </td>
        </tr>
        <tr>
            <td align="center">
                <br>
                @include('email.blocks.img-button', [
                    'href' => $resetPasswordLink,
                    'text' => 'Задать пароль',
                    'src' =>  '',
                ])
                <br>
            </td>
        </tr>
    </table>
@endsection
