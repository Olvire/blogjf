<?php
/**
 * Mail
 *
 * PHP Version 5.6
 *
 * @category Core
 * @package  Core
 * @author   Pierre-Sylvain Augereau <ps.augereau@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     https://blogjs.lignedemire.eu
 */
namespace Core;

/**
 * Envoi de mails
 *
 * @category Core
 * @package  Core
 * @author   Pierre-Sylvain Augereau <ps.augereau@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     https://blogjs.lignedemire.eu
 */
class Mail
{
    /**
     * Notification d'un commentaire abusif
     *
     * @param Array $comment Le Commentaire signalé
     *
     * @return boolean true si le mail a été envoyé
     */
    public static function sendAbuseNotification($comment)
    {
        $SMTPServeur = Config::getInstance()->config('SMTPServer');

        // Si le serveur SMTP n'est pas renseigné on ne considère
        // pas cela comme une erreur, simplement que l'on ne veux
        // pas envoyer de mail.
        if (empty($SMTPServeur)) {
            return true;
        }

        $mail = new \PHPMailer;
        $mail->isSMTP();
        $mail->Host = $SMTPServeur;
        $mail->SMTPAuth = true;
        $mail->Username = Config::getInstance()->config('SMTPUsername');
        $mail->Password = Config::getInstance()->config('SMTPPassword');
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;


        $mail->setFrom('ps.augereau@gmail.com.com', 'Commentaire abusif sur JF Blog');
        $mail->addAddress('ps.augereau@gmail.com', 'Commentaire abusif');
        $mail->isHTML(true);
        $mail->Subject = 'Un commentaire abusif a été signalé';

        $message = 'Le commentaire de '.$comment->author.' ('.$comment->email.') a été signalé comme abusif<br>';
        $message .= $comment->content;
        $message .= '<br><p>Pour le valider rendez-vous dans votre <a href="https://blogjf.lignedemire.eu/Admin">interface d\'administration</a></p><br>';
        $mail->Body = $message;
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }

}

