<?php

namespace App\Models\Stamm;

class GsoStamm extends BaseStamm
{
    protected static $table = 'GSO_STAMM';
    protected static $barcodeColumns = ['GSO_BARCODE_NR', 'GSO_BARCODE_NR2'];
    protected static $friendlyName = 'Geräte (Sonstige)';
    protected static $module = 'GSO';
}
