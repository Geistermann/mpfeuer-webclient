<?php

namespace App\Models\Stamm;

class KldStamm extends BaseStamm
{
    protected static $table = 'KLD_STAMM';
    protected static $barcodeColumns = ['KLD_BARCODE_NR', 'KLD_BARCODE_NR2'];
    protected static $friendlyName = 'Kleiderkammer';
    protected static $module = 'KLD';
}