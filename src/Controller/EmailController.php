<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class EmailController extends AbstractController
{
    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer, $appEmail, $projectDir, $publicDir)
    {
        // $email = (new Email())
        //     ->from('riyajoshi312@gmail.com')
        //     ->to('sakshigoraniya13@gmail.com')
        //     ->subject('Your order has been placed')
        //     ->text('Thanks for trusting us!')
        //     ->html('<p>Thank you, your order has been placed</p>');

        $email = (new TemplatedEmail())
            ->from($appEmail)
            ->to(new Address('riyajoshi.190673107028@gmail.com', 'Riya'))
            ->subject('Your order has been placed')
            ->textTemplate('emails/order-confirmation.txt.twig')
            ->htmlTemplate('emails/order-confirmation.html.twig')
            ->attachFromPath($publicDir . '/pdf/sfcasts_api_part-2-security.pdf')
            ->context([
                'delivery_date' => date_create('+3 days'),
                'order_number' => rand(5, 50000)
            ]);

        $mailer->send($email);

        return new Response('Email sent');
    }
}

?>