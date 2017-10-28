<?php
namespace CodeExperts\App\Service;

use \PHPMailer;

class Sender
{
    private $dataSend;

    private $mailer;

    public function __construct(PHPMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function setDataSend(array $dataSend)
    {
        $this->dataSend = $dataSend;
    }

    public function send(string $view, string $subject, array $receiver) :float
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->dataSend['SMTP_HOST'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->dataSend['SMTP_USER'];
        $this->mailer->Password = $this->dataSend['SMTP_PASSWORD'];
        $this->mailer->SMTPSecure = $this->dataSend['SMTP_ENCRYPTION'];
        $this->mailer->Port = $this->dataSend['SMTP_PORT'];

        $this->mailer->CharSet = 'UTF-8';


        $this->mailer->setFrom($this->dataSend['SMTP_FROM'], $this->dataSend['SMTP_USER_FROM']);
        $this->mailer->addAddress($receiver['email'], $receiver['name']);

        $this->mailer->isHTML(true);

        $this->mailer->Subject = $subject;

        $this->mailer->Body = $view;

        return $this->mailer->send();
    }
}