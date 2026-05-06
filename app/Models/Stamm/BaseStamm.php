<?php

namespace App\Models\Stamm;

use App\Models\Firebird\FirebirdModel;

abstract class BaseStamm extends FirebirdModel
{
    protected static $table = null;
    protected static $barcodeColumns = [];
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

    public static function getBarcodeColumns()
    {
        return static::$barcodeColumn;
    }

    public static function getTableName()
    {
        return static::$table;
    }

    /**
     * Sucht in allen definierten Barcode-Spalten nach dem Wert.
     * Optimiert für Firebird PDO (einzigartige Parameter-Bindings).
     */
    public static function findByBarcode($barcode)
    {
        if (empty(static::$barcodeColumns)) {
            return null;
        }

        $pdo = static::getConnection();
        $whereClauses = [];
        $params = [];

        // Wir erstellen für jede Spalte einen eigenen Platzhalter, 
        // da Firebird PDO oft Probleme bei mehrfacher Verwendung desselben Namens hat.
        foreach (static::$barcodeColumns as $index => $column) {
            $placeholder = "barcode_" . $index;
            $whereClauses[] = "{$column} = :{$placeholder}";
            $params[$placeholder] = $barcode;
        }

        $sql = "SELECT * FROM " . static::$table . " WHERE " . implode(' OR ', $whereClauses);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
    
    public function getArchivStatus() {
        $module = self::getModule();
        $dat = $this->{$module . '_ARCHIV_DAT'};
        $hdz = $this->{$module . '_ARCHIV_HDZ'};
        
        return (object) [
            'exists' => !empty($dat) && !empty($hdz),
            'datum' => $dat ? \Carbon\Carbon::parse($dat)->format('d.m.Y') : null,
            'hdz' => $hdz
        ];
    }
}
