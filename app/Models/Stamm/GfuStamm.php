<?php

namespace App\Models\Stamm;

class GfuStamm extends BaseStamm
{
    protected static $table = 'GFU_STAMM';
    protected static $barcodeColumns = ['GFU_BARCODE_NR', 'GFU_BARCODE_NR2'];
    protected static $friendlyName = 'Gerät (Funk)';
    protected static $module = 'GFU';
}
