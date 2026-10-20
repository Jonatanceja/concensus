<?php

/*
 * Recibe el formulario de contacto y el Termómetro y los manda por correo.
 * Responde JSON: {"ok": true} o {"ok": false, "error": "..."}.
 *
 * Para cambiar el destinatario, edita DESTINO. El remitente es no-reply@<dominio>:
 * ese buzón no necesita existir, pero el dominio debe ser el del sitio para que el
 * correo no caiga en spam. Las respuestas de la persona van en Reply-To.
 */

const DESTINO = 'ktapia@consensusfamily.com';
const LIMITE_ENVIOS = 5;      // máximo de envíos por IP...
const VENTANA_SEGUNDOS = 600; // ...en esta ventana (10 minutos)

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function responder(int $codigo, bool $ok, string $error = ''): never
{
    http_response_code($codigo);
    echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $error], JSON_UNESCAPED_UNICODE);
    exit;
}

function limpiar(string $valor, int $max = 200): string
{
    // Sin saltos de línea (evita inyección de cabeceras) ni espacios sobrantes
    $valor = trim(preg_replace('/[\r\n\t]+/', ' ', $valor));

    return mb_substr($valor, 0, $max);
}

function texto(string $valor, int $max = 4000): string
{
    return mb_substr(trim(str_replace("\r", '', $valor)), 0, $max);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    responder(405, false, 'Método no permitido.');
}

// Solo acepta envíos hechos desde este mismo sitio
$host = preg_replace(['/:\d+$/', '/^www\./'], '', strtolower($_SERVER['HTTP_HOST'] ?? ''));
$origen = $_SERVER['HTTP_ORIGIN'] ?? ($_SERVER['HTTP_REFERER'] ?? '');
if ($origen !== '') {
    $hostOrigen = preg_replace('/^www\./', '', strtolower((string) parse_url($origen, PHP_URL_HOST)));
    if ($hostOrigen !== $host) {
        responder(403, false, 'Solicitud no permitida.');
    }
}

// Trampa para bots: el campo está oculto y una persona nunca lo llena
if (($_POST['bot-field'] ?? '') !== '') {
    responder(200, true);
}

// Límite de envíos por IP
$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
$archivo = sys_get_temp_dir() . '/consensus_form_' . md5($ip);
$ahora = time();
$marcas = is_file($archivo) ? array_filter(array_map('intval', file($archivo, FILE_IGNORE_NEW_LINES) ?: []), fn ($t) => $t > $ahora - VENTANA_SEGUNDOS) : [];
if (count($marcas) >= LIMITE_ENVIOS) {
    responder(429, false, 'Has enviado varias solicitudes seguidas. Inténtalo más tarde.');
}

$formulario = ($_POST['form-name'] ?? 'contacto') === 'termometro' ? 'termometro' : 'contacto';

$nombre = limpiar($_POST['nombre'] ?? '', 120);
$email = limpiar($_POST['email'] ?? '', 160);

if ($nombre === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
    responder(422, false, 'Revisa tu nombre y tu correo electrónico.');
}

if ($formulario === 'termometro') {
    $asunto = 'Termómetro de empresa familiar: ' . $nombre;
    $lineas = [
        'Nombre: ' . $nombre,
        'Correo: ' . $email,
        'Puntaje: ' . limpiar($_POST['puntaje'] ?? '', 40),
        'Resultado: ' . limpiar($_POST['resultado'] ?? '', 160),
        '',
        'Respuestas:',
        texto($_POST['respuestas'] ?? '', 2000),
    ];
} else {
    $mensaje = texto($_POST['mensaje'] ?? '');
    if ($mensaje === '') {
        responder(422, false, 'Cuéntanos brevemente en qué podemos ayudarte.');
    }

    $asunto = 'Solicitud de cita: ' . $nombre;
    $lineas = [
        'Nombre: ' . $nombre,
        'Empresa: ' . limpiar($_POST['empresa'] ?? '', 160),
        'Correo: ' . $email,
        'Teléfono: ' . limpiar($_POST['telefono'] ?? '', 40),
        'Tema de interés: ' . limpiar($_POST['interes'] ?? '', 80),
        '',
        'Mensaje:',
        $mensaje,
    ];
}

$lineas[] = '';
$lineas[] = '— Enviado desde ' . ($_SERVER['HTTP_HOST'] ?? 'el sitio') . ' el ' . date('d/m/Y H:i');

$remitente = 'no-reply@' . ($host !== '' ? $host : 'consensusfamily.com');
$cabeceras = implode("\r\n", [
    'From: Consensus Family <' . $remitente . '>',
    'Reply-To: ' . str_replace(['"', '<', '>'], '', $nombre) . ' <' . $email . '>',
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
]);

$enviado = mail(
    DESTINO,
    mb_encode_mimeheader($asunto, 'UTF-8', 'B', "\r\n"),
    implode("\n", $lineas),
    $cabeceras,
    '-f' . $remitente
);

if (! $enviado) {
    responder(500, false, 'No pudimos enviar tu mensaje. Inténtalo de nuevo en un momento.');
}

file_put_contents($archivo, implode("\n", [...$marcas, $ahora]), LOCK_EX);
responder(200, true);
