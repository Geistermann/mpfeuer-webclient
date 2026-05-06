<?php

namespace App\Models\Stamm;

class GelStamm extends BaseStamm
{
    protected static $table = 'GEL_STAMM';
    protected static $barcodeColumns = ['GEL_BARCODE_NR', 'GEL_BARCODE_NR2'];
    protected static $friendlyName = 'Gerät (Elektro)';
    protected static $module = 'GEL';
}
