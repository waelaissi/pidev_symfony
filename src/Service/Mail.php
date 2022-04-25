<?php

namespace App\Service;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mail
{
    public function send_email(string $destination, string $subject, string $content, MailerInterface $mailer): void
    {
        $email = (new TemplatedEmail())
            ->from('alaazarrouk7@gmail.com')
            ->to($destination)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->subject($subject)
            ->htmlTemplate('Email/Email.html.twig')
            ->context([
                'content' => $content,
                'subject'=>$subject
            ]);
      /*  $email = (new Email())
            ->from('alaazarrouk7@gmail.com')
            ->to($destination)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);*/
        $mailer->send($email);
    }

    public function reservation_mail_subject(string $type)
    {
        $subject = "";
        switch ($type) {
            case 'hotel':
                $subject = " Tfarhida : Hotel reservation payment ";
                break;
            case 'house':
                $subject = " Tfarhida : House reservation payment ";
                break;
            case 'car':
                $subject = " Tfarhida : Car reservation payment ";
                break;
            case 'event':
                $subject = " Tfarhida : Event reservation payment ";
                break;
        }
        return $subject;
    }

    public function reservation_mail_content(string $type, string $data): string
    {
        $content = "";
        switch ($type) {
            case 'hotel':
                $content = 'Payment successfuly done
                 reservation at hotel' . $data . ' created ';
                break;
            case 'house':
                $content = 'Payment successfuly done 
                 reservation of the house  ' . $data . ' created';
                break;

            case 'car':
                $content = 'Payment successfuly done 
                 reservation of the car ' . $data . ' created ';
                break;

            case 'event':
                $content = 'Payment successfuly done
                 reservation of the event ' . $data . ' created';
                break;
        }
        return $content;
    }

    public function refund_mail_subject(string $type)
    {
        $subject = "";
        switch ($type) {
            case 'hotel':
                $subject = " Tfarhida : Canceled hotel reservation ";
                break;
            case 'house':
                $subject = " Tfarhida : Canceled house reservation";
                break;
            case 'car':
                $subject = " Tfarhida : Canceled car reservation ";
                break;
        }
        return $subject;
    }

    public function refund_mail_content(string $type, string $data): string
    {
        $content = "";
        switch ($type) {
            case 'hotel':
                $content = 'Reservation money refunded  
                 Reservation at hotel' . $data . ' was cancled   ';
                break;
            case 'house':
                $content = 'Reservation money refunded  
                 reservation of the house  ' . $data . '  was cancled ';
                break;

            case 'car':
                $content = 'Reservation money refunded 
                 reservation of the car ' . $data . '  was cancled ';
                break;

            case 'event':
                $content = 'Reservation money refunded 
                  reservation of the event ' . $data . ' created';
                break;
        }
        return $content;
    }
}

?>