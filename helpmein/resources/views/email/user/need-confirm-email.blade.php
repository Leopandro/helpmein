@extends('email.base')

@section('content-inner')
    @include('email.blocks.subject')

    <tr>
        <td>
            @include('email.blocks.text', ['text' => 'Здравствуйте! Для работы с сервисом, пожалуйста, подтвердите ваш e-mail, нажав на кнопку ниже.'])
        </td>
    </tr>
    <tr>
        <td>
            @include('email.blocks.img-button', [
                'href' => $verificationLink,
                'src' => env('AWS_STATIC_ENDPOINT') . '/mail/btn-confirm-email.png',
            ])
        </td>
    </tr>
    <tr>
        <td style="
        padding-top: 20px;
        ">
            @include('email.blocks.text', ['text' => 'Воспользуйтесь этой ссылкой, если у вас не работает кнопка:'])
            @include('email.blocks.link', [
                'href' => $verificationLink,
                'text' => $verificationLink,
            ])
        </td>
    </tr>
@endsection
