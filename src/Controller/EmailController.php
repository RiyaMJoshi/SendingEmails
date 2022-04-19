<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class EmailController extends AbstractController
{
    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer, $appEmail, $projectDir, $publicDir, NotifierInterface $notifier)
    {
        // $email = (new Email())
        //     ->from('riyajoshi312@gmail.com')
        //     ->to('sakshigoraniya13@gmail.com')
        //     ->subject('Your order has been placed')
        //     ->text('Thanks for trusting us!')
        //     ->html('<p>Thank you, your order has been placed</p>');

        $to = 'riyajoshi.190673107028@gmail.com';
        $email = (new TemplatedEmail())
            ->from($appEmail)
            ->to(new Address($to, 'Riya'))
            ->subject('Your order has been placed')
            ->textTemplate('emails/order-confirmation.txt.twig')
            //->htmlTemplate('emails/order-confirmation.html.twig')
            ->htmlTemplate('emails/welcome.html.twig')
            ->attachFromPath($publicDir . '/pdf/sfcasts_api_part-2-security.pdf', 'Security Certificate')
            //->embed(fopen('build/images/email/logo.png', 'r'), 'logo')
            ->context([
                'delivery_date' => date_create('+3 days'),
                'order_number' => rand(5, 50000)
            ]);
            //->html('<img src="cid:logo">');

        $mailer->send($email);

        // Create a Notification that has to be sent
        // using the "email" channel
        $notification = (new Notification('New Invoice', ['email']))
            ->content('You got a new invoice for 15 EUR.');

        // The receiver of the Notification
        $recipient = new Recipient($to);

        // Send the notification to the recipient
        $notifier->send($notification, $recipient);

        return new Response('Email sent');
        //return $this->redirectToRoute('notify_user');
    }

    /**
     * @Route("/email/notification", name="notify_user")
     */
    public function notifyUser(NotifierInterface $notifier)
    {
        // // Create a Notification that has to be sent
        // // using the "email" channel
        // $notification = (new Notification('New Invoice', ['email']))
        //     ->content('You got a new invoice for 15 EUR.');

        // // The receiver of the Notification
        // $recipient = new Recipient(
        //     $user->getEmail()
        // );

        // // Send the notification to the recipient
        // $notifier->send($notification, $recipient);
    }


    /**
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('welcome/index.html.twig', [
            'controller_name' => 'WelcomeController',
        ]);
    }
}

?>