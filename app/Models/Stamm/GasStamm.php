<?php

namespace App\Models\Stamm;

class GasStamm extends BaseStamm
{
    protected static $table = 'GAS_STAMM';
    protected static $barcodeColumns = ['GAS_BARCODE_NR', 'GAS_BARCODE_NR2'];
    protected static $friendlyName = 'Atemschutz';
    protected static $module = 'GAS';
}
