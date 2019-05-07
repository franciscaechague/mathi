<?php

$recipients = 'ventas@celugadgets.com.ar; clica.leads@gmail.com';

try {
    require './phpmailer/PHPMailerAutoload.php';

    preg_match_all("/([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)/", $recipients, $addresses, PREG_OFFSET_CAPTURE);

    if (!count($addresses[0])) {
        die('MF001');
    }

    if (preg_match('/^(127\.|192\.168\.)/', $_SERVER['REMOTE_ADDR'])) {
        die('MF002');
    }

    $template = file_get_contents('rd-mailform.tpl');	
	
    $subject = $_POST['firstname'] . " - Solicitud de presupuesto";
    

    if (isset($_POST['email'])) {
        $template = str_replace(
            array("<!-- #{FromState} -->", "<!-- #{FromEmail} -->"),
            array("Email:", $_POST['email']),
            $template);
    }else{
        die('MF003');
    }

    if (isset($_POST['problema'])) {
        $template = str_replace(
            array("<!-- #{MessageState} -->", "<!-- #{MessageDescription} -->"),
            array("Problema y modelo:", $_POST['problema'] . " | " . $_POST['modelo'] ), 
            $template);
    }


    preg_match("/(<!-- #{BeginInfo} -->)(.|\n)+(<!-- #{EndInfo} -->)/", $template, $tmp, PREG_OFFSET_CAPTURE);
    foreach ($_POST as $key => $value) {
        if ($key != "email" && $key != "message" && $key != "form-type" && !empty($value)){
            $info = str_replace(
                array("<!-- #{BeginInfo} -->", "<!-- #{InfoState} -->", "<!-- #{InfoDescription} -->"),
                array("", ucfirst($key) . ':', $value),
                $tmp[0][0]);

            $template = str_replace("<!-- #{EndInfo} -->", $info, $template);
        }
    }

    $template = str_replace(
        array("<!-- #{Subject} -->", "<!-- #{SiteName} -->"),
        array($subject, $_SERVER['SERVER_NAME']),
        $template);

    $mail = new PHPMailer();

    $mail->From = "mathias@clica.com.ar";
	//;

    if (isset($_POST['firstname'])){
        $mail->FromName = $_POST['firstname'];
    }else{
        $mail->FromName = "Posible cliente";
    }
	$mail->AddReplyTo($_POST['email'], $_POST['firstname']);

    foreach ($addresses[0] as $key => $value) {
        $mail->addAddress($value[0]);
    }

	
	$mail->Mailer = 'smtp';
	$mail->Host = 'ssl://smtp.gmail.com';
	$mail->Port = 465;
	$mail->SMTPAuth = true;
	$mail->Username = 'clica.leads@gmail.com'; // SMTP username
	$mail->Password = 'studiochu123'; // SMTP password*/
    $mail->CharSet = 'utf-8';
    $mail->Subject = $subject;
    $mail->MsgHTML($template);
    $mail->send();

    header("Location: https://soporteiphone.com.ar/success.html");
    die();
} catch (phpmailerException $e) {
    die('MF254');
} catch (Exception $e) {
    die('MF255');
}