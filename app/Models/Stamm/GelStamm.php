<?php

namespace App\Models\Stamm;

class GelStamm extends BaseStamm
{
    protected static $table = 'GEL_STAMM';
    protected static $barcodeColumn = 'GEL_BARCODE_NR';
    protected static $friendlyName = 'Gerät (Elektro)';
    protected static $module = 'GEL';
}
