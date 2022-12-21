<?php
namespace App\Domain\User\UseCase\PasswordChanged;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class PasswordChangedEmail extends Mailable
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
        $link = request()->getScheme().'://'.request()->getHost().'/#/password-update?token='.$this->token;
        return $this->view('email.user.password-reset')
                ->with([
                    'resetPasswordLink' => $link,
                    'user' => $this->user,
                    'subject' => 'Password reset'
                ])
                ->from('admin@admin.com', 'Ссылка для восстановления пароля')
                ->subject('Восстановить пароль');
    }
}
