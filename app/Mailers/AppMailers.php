<?php
namespace App\Mailers;

use App\User;
use Illuminate\Contracts\Mail\Mailer;
/**
 *
 */
class AppMailers {
  protected $mailer;
  protected $from='store@admin.com';
  protected $to;
  protected $view;
  protected $data=[];

  public function __construct(Mailer $mailer){
    $this->mailer=$mailer;
  }
  public function sendEmailConfirmationTo(User $user){
    $this->to=$user->email;
    $this->view='auth.confirm';
    $this->data=compact('user');
    $this->deliver();
  }
  public function deliver(){
    $this->mailer->send($this->view,$this->data,
      function($message){
        $message->from($this->from,'Administrator')
        ->to($this->to);
    });
  }
}
