<?php
namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait MailNotification {

    function notfication(array $data){
        
        
      Mail::send('emails.notification', $data, function($message) use($data)
      {
        $message->from('info@ticketeando.com', $data['title']);
        $message->to($data['emails'])->subject($data['subject']);
      });
    }
    
 }