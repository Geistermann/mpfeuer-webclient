<?php

namespace App\Models\Stamm;

class GhrStamm extends BaseStamm
{
    protected static $table = 'GHR_STAMM';
    protected static $barcodeColumn = 'GHR_BARCODE_NR';
    protected static $friendlyName = 'Gerät (SRHT)';
    protected static $module = 'GHR';
}
