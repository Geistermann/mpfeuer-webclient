<?php

namespace App\Models\Stamm;

use App\Models\Firebird\FirebirdModel;

abstract class BaseStamm extends FirebirdModel
{
    protected static $table = null;
    protected static $barcodeColumn = null;
    protected static $friendlyName = null;
    protected static $module = null;

    public static function getFriendlyName()
    {
        return static::$friendlyName ?? static::$module;
    }

    public static function getModule()
    {
        return static::$module;
    }

    public static function getBarcodeColumn()
    {
        return static::$barcodeColumn;
    }

    public static function getTableName()
    {
        return static::$table;
    }

    public static function findByBarcode($barcode)
    {
        $pdo = static::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM ".static::$table." WHERE ".static::$barcodeColumn." = :barcode");
        $stmt->execute(['barcode' => $barcode]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
