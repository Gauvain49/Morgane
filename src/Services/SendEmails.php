<?php
namespace App\Services;

class SendEmails
{
	protected $transport;

	public function __construct()
	{
		$this->transport = (new \Swift_SmtpTransport('ssl0.ovh.net', 465, 'SSL'))
          ->setUsername('contact@percevalcreation.fr')
          ->setPassword('kaErlion091013#')
        ;
	}

	public function confirmOrder($template, $templateTxt, $numOrder, $fromEMail, $fromName, $toEmail, $toName)
	{
        $message = (new \Swift_Message("Votre commande N°$numOrder"))
            ->setFrom([$fromEMail => $fromName])
            ->setTo([$toEmail => "Service commande $toName"])
            ->setBody(
                $template,
                'text/html'
            )
            ->addPart(
                $templateTxt,
                'text/plain'
            )
            ;
        $mailer = new \Swift_Mailer($this->transport);
        $mailer->send($message);
    }

    public function confirmSendManuscript($template, $templateTxt, $fromEMail, $fromName, $toEmail, $toName, $recipient)
    {
        if ($recipient == 'Author') {
            $title = 'Envoi de votre manuscrit';
        } else {
            $title = "Un nouveau manuscrit vient d'être envoyé.";
        }
        $message = (new \Swift_Message($title))
            ->setFrom([$fromEMail => "Service manuscrit $fromName"])
            ->setTo([$toEmail => "$toName"])
            ->setBody(
                $template,
                'text/html'
            )
            ->addPart(
                $templateTxt,
                'text/plain'
            )
            ;
        $mailer = new \Swift_Mailer($this->transport);
        $mailer->send($message);
    }

    public function sendMailContact($template, $templateTxt,
        $fromEMail, $fromName, $toEmail, $toName, $subject)
    {
        $message = (new \Swift_Message($subject))
            ->setFrom([$fromEMail => "Message de $fromName"])
            ->setTo([$toEmail => "$toName"])
            ->setBody(
                $template,
                'text/html'
            )
            ->addPart(
                $templateTxt,
                'text/plain'
            )
            ;
        $mailer = new \Swift_Mailer($this->transport);
        $mailer->send($message);
    }
}