<?php

$allowed_origins = [
    "https://koonolmex.com.mx",
    "https://www.koonolmex.com.mx"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    header("Access-Control-Allow-Origin: https://koonolmex.com.mx");
}

header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $nombre = filter_var($input['nombre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $empresa = filter_var($input['empresa'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $telefono = filter_var($input['telefono'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $correo = filter_var($input['correo'] ?? '', FILTER_VALIDATE_EMAIL);
    $mensaje = filter_var($input['mensaje'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$nombre || !$correo || !$mensaje) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Por favor llena los campos obligatorios."]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'mail.koonolmex.com.mx';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contacto@koonolmex.com.mx'; 
        $mail->Password   = 'coknofGcKi5Ir';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('contacto@koonolmex.com.mx', 'Página Web Contacto');
        $mail->addAddress('contacto@koonolmex.com.mx'); 
        $mail->addReplyTo($correo, $nombre);         

        
        $empresaHTML = !empty($empresa) 
            ? $empresa 
            : '<em style="color:#94a3b8;">No especificada</em>';

        $telefonoHTML = !empty($telefono) 
            ? $telefono 
            : '<em style="color:#94a3b8;">No proporcionado</em>';

        // Convertir saltos de línea en <br> para HTML
        $mensajeHTML = nl2br($mensaje);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "Lead KoonolMX - {$nombre}";

        $mail->Body = "
            <div style=\"background-color: #f6f9fc; padding: 40px 10px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;\">
                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;\">
                    <tr>
                        <td style=\"background: linear-gradient(135deg, #005671 0%, #1e1b4b 100%); padding: 30px; text-align: center;\">
                            <h1 style=\"color: #ffffff; margin: 0; font-size: 22px; font-weight: 600; letter-spacing: 0.5px;\">
                                Nuevo Mensaje de Contacto
                            </h1>
                            <p style=\"color: #93c5fd; margin: 5px 0 0 0; font-size: 14px;\">
                                Página Web KoonolMX • Lead Recibido
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style=\"padding: 40px 30px;\">
                            <p style=\"margin: 0 0 25px 0; font-size: 16px; line-height: 1.5; color: #334155;\">
                                Se ha registrado una nueva consulta a través del formulario de contacto de la página web. A continuación se detallan los datos proporcionados:
                            </p>

                            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"margin-bottom: 30px;\">
                                <tr>
                                    <td style=\"padding: 10px 0; border-bottom: 1px solid #f1f5f9; width: 35%; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;\">Nombre:</td>
                                    <td style=\"padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #0f172a;\">{$nombre}</td>
                                </tr>
                                <tr>
                                    <td style=\"padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;\">Empresa:</td>
                                    <td style=\"padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #0f172a;\">{$empresaHTML}</td>
                                </tr>
                                <tr>
                                    <td style=\"padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;\">Teléfono:</td>
                                    <td style=\"padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #0f172a;\">{$telefonoHTML}</td>
                                </tr>
                                <tr>
                                    <td style=\"padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;\">Correo:</td>
                                    <td style=\"padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #005671; font-weight: 500;\">{$correo}</td>
                                </tr>
                            </table>

                            <div style=\"background-color: #f8fafc; border-left: 4px solid #005671; padding: 20px; border-radius: 0 4px 4px 0;\">
                                <h4 style=\"margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;\">Mensaje o Proyecto:</h4>
                                <p style=\"margin: 0; font-size: 15px; line-height: 1.6; color: #1e293b;\">{$mensajeHTML}</p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style=\"background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #f1f5f9;\">
                            <p style=\"margin: 0; font-size: 12px; color: #94a3b8;\">
                                Este es un correo automático generado por el sistema de contacto. Puedes responder directamente a este correo para comunicarte con el prospecto.
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        ";

        $mail->send();
        echo json_encode(["status" => "success", "message" => "Tu información ha sido enviada con éxito."]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Error al enviar el correo."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}