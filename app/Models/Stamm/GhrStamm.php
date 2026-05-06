<?php

namespace App\Models\Stamm;

class GhrStamm extends BaseStamm
{
    protected static $table = 'GHR_STAMM';
    protected static $barcodeColumns = ['GHR_BARCODE_NR', 'GHR_BARCODE_NR2'];
    protected static $friendlyName = 'Gerät (SRHT)';
    protected static $module = 'GHR';
}
