<?php

    require "./libs/phpMailer/Exception.php";
    require "./libs/phpMailer/OAuth.php";
    require "./libs/phpMailer/PHPMailer.php";
    require "./libs/phpMailer/POP3.php";
    require "./libs/phpMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // print_r($_POST);

    class Mensagem {
        private $para = NULL;
        private $assunto = null;
        private $mensagem = null;

        public function __get($atributo){
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        public function mensagemValida(){
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }

            return true;
        }

        
    }

    $mensagem = new Mensagem();

    $mensagem->__set('para',$_POST['para']);
    $mensagem->__set('assunto',$_POST['assunto']);
    $mensagem->__set('mensagem',$_POST['mensagem']);

    // print_r($mensagem);

    if(!$mensagem->mensagemValida()){
        echo 'A mensagem não é válida';
        die();
    }


    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.mailgun.org';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'postmaster@sandboxee48add78ce748cfb9ab6f6f584df931.mailgun.org';                     //SMTP username
        $mail->Password   = 'fe97253c65268e03ef0efa731a37f9e5-413e373c-83390455';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('postmaster@sandboxee48add78ce748cfb9ab6f6f584df931.mailgun.org');
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        // $mail->AltBody = $mensagem->__get('mensagem');

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Não foi possível enviar o email, tente novamente mais tarde. Erro: {$mail->ErrorInfo}";
    }