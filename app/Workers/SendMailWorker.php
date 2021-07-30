<?php
/**
 * Класс для отправки почты
 */
namespace app\Workers;

use app\Registry\Registry;
use PHPMailer\PHPMailer\PHPMailer;

class SendMailWorker
{
    private $mailUser = null;
    private $mailPass = null;

    /**
     * Получить из реестра настройки (env.ini), почту и пароль для SMTP
     */
    public function __construct()
    {
        $reg = Registry::getInstance();
        $env = $reg->getEnviroment();

        $this->mailUser = $env->get('mailUser');
        $this->mailPass = $env->get('mailPass');
    }

    /**
     * Для отправки требуется:
     * 
     * Почта, куда отправлять письмо
     * @param string  $to
     * 
     * Заголовок письма
     * @param string  $title
     * 
     * Сообщение в формате html
     * @param string  $message_html
     * 
     * Сообщение в текстовом формате, если получатель не поддерживает html
     * @param string  $message_nohtml
     * 
     * @return string
     */
    public function send(string $to, string $title, string $message_html, string $message_nohtml): string
    {
        $mail = new PHPMailer(true);

        try{
            $mail->CharSet = 'UTF-8';
            //Server settings
            //Подробный лог на страницу
            //$mail->SMTPDebug  = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = "smtp.gmail.com";
            $mail->SMTPAuth   = true;
            //Тут указать реальные данные для входа
            $mail->Username   = $this->mailUser;
            $mail->Password   = $this->mailPass;
            //    $mail->SMTPSecure = "ssl";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            //    $mail->Port       = "465";
            $mail->Port       = 587;
            
            //Recipients
            $mail->setFrom("augcat50@gmail.com", "Keyburner50.com");
            $mail->addAddress($to);
            //$mail->addAddress("wilcher@mail.ru");
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body    = $message_html;
            $mail->AltBody = $message_nohtml;
            
            $mail->send();
            $data = "На почту отправлено письмо, активируйте учётную запись!";
        }catch (\Exception $e){
            $data = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        return $data;
    }
}
