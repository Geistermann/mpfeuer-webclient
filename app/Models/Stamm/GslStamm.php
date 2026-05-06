<?php

namespace App\Models\Stamm;

class GslStamm extends BaseStamm
{
    protected static $table = 'GSL_STAMM';
    protected static $barcodeColumns = ['GSl_BARCODE_NR',  'GSl_BARCODE_NR2'];
    protected static $friendlyName = 'Schläuche';
    protected static $module = 'GSL';
}
