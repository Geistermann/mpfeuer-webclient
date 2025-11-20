<?php

namespace App\Models\Stamm;

class GasStamm extends BaseStamm
{
    protected static $table = 'GAS_STAMM';
    protected static $barcodeColumn = 'GAS_BARCODE_NR';
    protected static $friendlyName = 'Atemschutz';
    protected static $module = 'GAS';
}
