<?php

namespace App\Models\Stamm;

class GfuStamm extends BaseStamm
{
    protected static $table = 'GFU_STAMM';
    protected static $barcodeColumn = 'GFU_BARCODE_NR';
    protected static $friendlyName = 'Gerät (Funk)';
    protected static $module = 'GFU';
}
