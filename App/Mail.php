<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Mail
{

  public static function send($to, $subject, $text, $html)
  {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("m.kapela91@gmail.com", "Milusie Pączusie");
        $email->setSubject($subject);
        $email->addTo($to);
        $email->addContent("text/plain", $text);
        $email->addContent("text/html", "<strong>$html</strong>");

        $sendgrid = new \SendGrid(Config::SECRET_MAIL_GRID);
        $sendgrid->send($email);
  }
}
