<?php

namespace App\Models\Stamm;

class GslStamm extends BaseStamm
{
    protected static $table = 'GSL_STAMM';
    protected static $barcodeColumn = 'GSl_BARCODE_NR';
    protected static $friendlyName = 'Schläuche';
    protected static $module = 'GSL';
}
