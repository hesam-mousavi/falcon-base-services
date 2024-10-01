<?php

namespace FalconBaseServices\Services\Sender\Implements\Email;

use PHPMailer\PHPMailer\Exception;

use FalconBaseServices\Services\Sender\Contracts\Email;

class PHPMailer implements Email
{
    private $mail;

    public function __construct()
    {
        try {
            $this->mail = new \PHPMailer\PHPMailer\PHPMailer(true);

            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['EMAIL_HOST'];
            $this->mail->SMTPAuth = true;
            $this->mail->SMTPSecure = 'ssl';
            $this->mail->Port = 465;
            $this->mail->SMTPKeepAlive = true;
            $this->mail->isHTML(true);
            $this->mail->CharSet = "UTF-8";
        } catch (Exception $e) {
            LOGGER->error("phpmailer can't initial.", ['exception message' => $this->mail->ErrorInfo]);
        }
    }

    public function send($to, $subject, $content, $from = null, $bcc = null): bool
    {
        $from = is_null($from) ? 'no-reply' : $from;
        $this->mail->Username = $from.'@abc.com';
        $this->mail->Password = self::getPass($from);
        $this->mail->setFrom($this->mail->Username);

        if (!is_null($bcc) && is_array($bcc)) {
            foreach ($bcc as $other_mail) {
                $this->mail->addBCC($other_mail);
            }
        }
        $this->mail->addAddress($to);
        $this->mail->Subject = $subject;
        $this->mail->Body = $content;

        try {
            $this->mail->send();
        } catch (Exception $e) {
            LOGGER->error(
                "phpmailer can't sent email to {$to} from {$this->mail->Username}",
                ['exception message' => $this->mail->ErrorInfo],
            );

            return false;
        }

        return true;
    }

    protected static function getPass($from)
    {
        switch ($from) {
            case 'info':
                return $_ENV['INFO_EMAIL_PASS'];
            case 'newsletter':
                return $_ENV['NEWSLETTER_EMAIL_PASS'];
            case 'support':
                return $_ENV['SUPPORT_EMAIL_PASS'];
            default:
                return $_ENV['NO_REPLY_EMAIL_PASS'];
        }
    }
}
