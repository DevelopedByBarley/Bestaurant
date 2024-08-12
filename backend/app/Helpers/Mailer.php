<?php

namespace App\Helpers;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;


/* Example:

    $this->Mailer->renderAndSend(
        'test',  >>> test.mt.php
        [
            'name' => 'name',
            'age' => 10
        ],
        'example@mail.hu',
        'subject'
    )

*/

class Mailer
{
    function renderAndSend($file, $data, $address, $subject)
    {
        ob_start();

        $path = "app/Views/templates/mails/$file.mt.php";

        // Ellenőrizzük, hogy a fájl létezik-e
        if (file_exists($path)) {
            // Változók beállítása
            foreach ($data as $key => $value) {
                $$key = $value; // Dinamikusan létrehozunk változókat a $data tömb kulcsainak megfelelően
            }

            // Sablonfájl beolvasása
            include($path);

            // Sablonfájl tartalmának mentése a változóba
            $body = ob_get_clean();

            // E-mail elküldése
            self::send($address, $body, $subject); // Feltehetően itt meghívnánk egy másik függvényt, ami elküldi az e-mailt
        } else {
            // Hibás fájlelérés esetén hibaüzenet kiírása
            echo "Error: File $path not found";
            exit;
        }
    }


    public function send($address, $body, $subject)
    {

        try {
            $mail = new PHPMailer();
            $mail->isSMTP();
           //$mail->SMTPDebug = 3;
            $mail->setFrom($_SERVER['MAILER_SET_FROM'], $_SERVER['MAILER_SET_TO']);
            $mail->addAddress($address);
            $mail->Username = $_SERVER['MAILER_USERNAME'];
            $mail->Password = $_SERVER['MAILER_PASSWORD'];
            $mail->Host = $_SERVER['MAILER_HOST'];
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
            $mail->isHTML(true);
            return $mail->send();
        } catch (Exception $e) {
            var_dump($e);
            return false;
        }
    }
}
