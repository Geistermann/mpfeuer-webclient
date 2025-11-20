<?php

namespace App\Models\Stamm;

class GsoStamm extends BaseStamm
{
    protected static $table = 'GSO_STAMM';
    protected static $barcodeColumn = 'GSO_BARCODE_NR';
    protected static $friendlyName = 'Geräte (Sonstige)';
    protected static $module = 'GSO';
}
