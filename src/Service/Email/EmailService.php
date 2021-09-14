<?php

declare(strict_types=1);

namespace App\Service\Email;

use App\Service\DotEnv\DotEnv;

class EmailService
{

    private string $smtp;
    private string $smtpPort;
    private string $username;
    private string $password;
    private string $email;

    public function __construct(string $smtp, string $smtpPort, string $username, string $password, string $email)
    {
        $this->smtp = $smtp;
        $this->smtpPort = $smtpPort;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function prepareEmail(): \Swift_Mailer
    {
        $transport = (new \Swift_SmtpTransport($this->smtp, $this->smtpPort))
            ->setUsername($this->username)
            ->setPassword($this->password)
            ->setEncryption('TLS')
            ;

        return new \Swift_Mailer($transport);
    }

    public function createMessage(): \Swift_Message
    {
        return (new \Swift_Message('Subject'))
            ->setFrom($this->email)
            ->setTo('test@test.com')
            ->setBcc('toto@toto.com')
            ->setBody('message')
            ;
    }
}