<?php

namespace App\Models\Stamm;

class GmpStamm extends BaseStamm
{
    protected static $table = 'GMP_STAMM';
    protected static $barcodeColumns = ['GMP_BARCODE_NR', 'GMP_BARCODE_NR2'];
    protected static $friendlyName = 'Medizin Produkte';
    protected static $module = 'GMP';
}
