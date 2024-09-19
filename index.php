<?php
require __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 'On');

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

define('CHEMIN', realpath(__DIR__));

$options = [
    'size' => '300',
    'color' => '000000',
    'bgcolor' => 'FFFFFF',
    'logo' => CHEMIN . '/images/poulailler-128.png'
];

$url = $_GET['url'] ?? false;
if (!$url) die('No url given');
foreach ($options as $key => $val) {
    if (isset($_GET[$key])) {
        $options[$key] = $_GET[$key];
    }
}

generateQrCode($url, $options);

/**
 * Generates a QR code as a PNG image with a transparent logo.
 * 
 * @param string $text The text to encode in the QR code.
 * @param array $options {
 *     @type string 'size' Size of the QR code in pixels.
 *     @type string 'color' Foreground color in hex format.
 *     @type string 'bgcolor' Background color in hex format, can be 'transparent'.
 *     @type string 'logo' Path to the logo image (local).
 * }
 * @return string Base64 encoded PNG image.
 */
function generateQrCode($text, $options = [])
{
    $size = $options['size'] ?? 400; // Default size is 400px if not specified
    $color = new Color(hexdec(substr($options['color'], 0, 2)), hexdec(substr($options['color'], 2, 2)), hexdec(substr($options['color'], 4, 2)));
    $bgColor = $options['bgcolor'] === 'transparent' ? new Color(255, 255, 255, 0) : new Color(hexdec(substr($options['bgcolor'], 0, 2)), hexdec(substr($options['bgcolor'], 2, 2)), hexdec(substr($options['bgcolor'], 4, 2)));

    $builder = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data($text)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(ErrorCorrectionLevel::High)
        ->size($size)
        ->margin(0) // Set margin to 0 to remove any border
        ->foregroundColor($color) // Set the QR code color
        ->backgroundColor($bgColor) // Set the background color
        ->roundBlockSizeMode(RoundBlockSizeMode::Enlarge);

    $result = $builder->build();

    // Process the logo if present
    if (isset($options['logo']) && file_exists($options['logo'])) {
        $qrCode = imagecreatefromstring(file_get_contents($result->getDataUri()));
        $logo = imagecreatefrompng($options['logo']);
        imagesavealpha($qrCode, true);
        imagealphablending($qrCode, true);

        $QR_width = imagesx($qrCode);
        $QR_height = imagesy($qrCode);
        $logo_width = imagesx($logo);
        $logo_height = imagesy($logo);
        $logo_qr_width = intval($QR_width / 5);
        $scale = intval($logo_width / $logo_height);
        $logo_qr_height = intval($logo_qr_width / $scale);
        $from_width = intval(($QR_width - $logo_qr_width) / 2);
        $from_height = intval(($QR_height - $logo_qr_height) / 2);

        imagecopyresampled($qrCode, $logo, $from_width, $from_height, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

        // Final output
        ob_start();
        imagepng($qrCode);
        $imageString = ob_get_contents();
        ob_end_clean();

        imagedestroy($qrCode);
        imagedestroy($logo);

        header('Content-Type: image/png');
        echo $imageString;
    } else {
        header('Content-Type: image/png');
        echo $result->getString();
    }
}
