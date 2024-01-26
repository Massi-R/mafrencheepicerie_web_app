<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendRegistrationConfirmation(string $recipient, string $subject, array $context = []): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('mafrenchepicerie@gmail.com', 'Ma French Epicerie'))
            ->to($recipient)
            ->subject('Confirmation d\'inscription')
            ->htmlTemplate('register/mail.html.twig')
            ->context(['user' => $username]);
        $this->mailer->send($email);
    }
}
