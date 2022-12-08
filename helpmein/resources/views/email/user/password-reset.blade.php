@extends('email.base')

@section('content')
    <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;" width="100%">
        <tr>
            <td align="center">
                <br>
                @include('email.blocks.text', [
                    'text' => 'Для восстановления доступа к личному кабинету задайте новый пароль.'
                ])
            </td>
        </tr>
        <tr>
            <td align="center">
                <br>
                @include('email.blocks.img-button', [
                    'href' => $resetPasswordLink,
                    'text' => 'Сменить пароль',
                    'src' => '',
                ])
                <br>
            </td>
        </tr>
    </table>
@endsection
