<?php

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Output\QROutputInterface;

//is the event id set?
if (isset($event_id)) {
    //is the max-width set?
    if (!isset($qrcode_max_width)) {
        $qrcode_max_width = '200px';
    }
?>

    <div class="qr-code-render">
        <!-- QRCode -->
        <?php
        $qrCodeData = APP_URL . '/index.php?event=' . $event->getEventSlug($event_id);
        $qrCodeOptions = new QROptions;
        $qrCodeOptions->version = 7;
        $qrCodeOptions->outputType = QROutputInterface::GDIMAGE_PNG;
        $qrCodeOptions->scale = 20;
        $qrCodeOptions->outputBase64 = true;
        $qrCode = (new QRCode($qrCodeOptions))->render($qrCodeData); // per the documentation, https://php-qrcode.readthedocs.io/en/main/Usage/Quickstart.html
        //output the QRCode JPG

        echo '<img src="' . $qrCode . '" alt="QRCode" style="max-width: ' . $qrcode_max_width . '; max-height: auto;">';
        ?>
    </div>

<?php } ?>
