<?php

namespace App\Models\Stamm;

class GmpStamm extends BaseStamm
{
    protected static $table = 'GMP_STAMM';
    protected static $barcodeColumn = 'GMP_BARCODE_NR';
    protected static $friendlyName = 'Medizin Produkte';
    protected static $module = 'GMP';
}
