<?php

namespace App\Models\Stamm;

class KldStamm extends BaseStamm
{
    protected static $table = 'KLD_STAMM';
    protected static $barcodeColumn = 'KLD_BARCODE_NR';
    protected static $friendlyName = 'Kleiderkammer';
    protected static $module = 'KLD';
}