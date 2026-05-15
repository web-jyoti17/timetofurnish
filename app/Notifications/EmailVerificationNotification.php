<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationNotification extends Notification
{
    use Queueable;


    public function __construct()
    {
        
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    // public function toMail($notifiable)
    // {
    //     $notifiable->verification_code = encrypt($notifiable->id);
    //     $notifiable->save();

    //     $array['view'] = 'emails.verification';
    //     $array['subject'] = translate('Email Verification');
    //     $array['content'] = translate('Please click the button below to verify your email address.');
    //     $array['link'] = route('email.verification.confirmation', $notifiable->verification_code); 
        
    //     // Recipient email
    //     $to = $notifiable->email;
        
    //     // Email headers
    //     $headers  = "MIME-Version: 1.0" . "\r\n";
    //     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    //     $headers .= "From: Your Company <no-reply@timetofurnish.com>" . "\r\n";
        
    //     // Email message (HTML)
    //     $message = "
    //     <html>
    //     <head>
    //       <title>{$array['subject']}</title>
    //     </head>
    //     <body style='font-family: Arial, sans-serif; background-color:#f8f8f8; padding:20px;'>
    //       <div style='max-width:600px; margin:0 auto; background:#fff; padding:30px; border-radius:8px;'>
    //         <h2 style='color:#333;'>{$array['subject']}</h2>
    //         <p style='color:#555;'>{$array['content']}</p>
    //         <p style='text-align:center; margin-top:30px;'>
    //           <a href='{$array['link']}' style='background-color:#28a745; color:#fff; padding:12px 24px; text-decoration:none; border-radius:5px;'>
    //             Verify Email
    //           </a>
    //         </p>
    //         <p style='margin-top:40px; color:#999; font-size:12px; text-align:center;'>
    //           If you did not request this, please ignore this email.
    //         </p>
    //       </div>
    //     </body>
    //     </html>
    //     ";
        
    //     // Send email
    //     if (mail($to, $array['subject'], $message, $headers)) {
    //         return true;
    //     } else {
    //         return true;
    //     }
    //     //return (new MailMessage)->view('emails.verification', ['array' => $array])->subject(translate('Email Verification - ') . env('APP_NAME'));
    // }
    
    
    
    public function toMail($notifiable)
    {
      
        $notifiable->verification_code = encrypt($notifiable->id);
        $notifiable->save();  
        $array['subject'] = translate('Email Verification');
        $array['content'] = translate('Please click the button below to verify your email address.');
        $array['link'] = route('email.verification.confirmation', $notifiable->verification_code);
        return (new MailMessage)->subject($array['subject'])->view('emails.verification', ['array' => $array]);
    }

    

    public function toArray($notifiable)
    {
    }
}
