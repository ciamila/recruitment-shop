<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ShopException;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSenderService
{
    /** @var MailerInterface */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $message
     * 
     * @return void
     */
    public function sendEmail(string $from, string $to, string $subject, string $message): void
    {
        $email = new Email();
        $email
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($message);

        try {
            $this->mailer->send($email);
        } catch (TransportException $exception) {
            throw new ShopException('The email couldn\'t be sent');
        }
    }
}