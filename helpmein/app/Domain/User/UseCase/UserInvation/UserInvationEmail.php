<?php
namespace App\Domain\User\UseCase\UserInvation;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected $user, protected $token)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = request()->getScheme().'://'.request()->getHost().'/#/password-update?token='.$this->token;
        return $this->view('email.user.password-create')
            ->with([
                'resetPasswordLink' => $url,
                'user' => $this->user,
                'subject' => 'Задайте пароль для вашего личного кабинета'
            ])
            ->from('admin@admin.com', 'HelpMeIn')
            ->subject('Приглашение на сайт');
    }
}
