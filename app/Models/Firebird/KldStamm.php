<?php

namespace App\Models\Firebird;

use PDO;

class KldStamm extends FirebirdModel
{        
    protected static string $table = 'KLD_STAMM';
    protected static string $barcodeColumn = 'KLD_BARCODE_NR';
    
    public static function all()
    {
        $pdo = static::getConnection();
        $stmt = $pdo->query('SELECT * FROM ' . static::$table);
        return $stmt->fetchAll();
    }

    public static function first()
    {
        $pdo = static::getConnection();
        $stmt = $pdo->query('SELECT FIRST 1 * FROM ' . static::$table);
        return $stmt->fetch();
    }

    /**
     * Suche nach GHR_BARCODE_NR
     *
     * @param string $barcode
     * @return object|null
     */
    public static function findByBarcode(string $barcode)
    {
        $pdo = static::getConnection();

        $stmt = $pdo->prepare('SELECT FIRST 1 * FROM ' . static::$table .' WHERE ' . static::$barcodeColumn . ' = :barcode');
        $stmt->bindValue(':barcode', $barcode);
        $stmt->execute();

        return $stmt->fetch() ?: null;
    }

    /**
     * Getter / Setter für Tabelle und Barcode-Spalte
     */
    public static function setTable(string $tableName): void
    {
        static::$table = $tableName;
    }

    public static function setBarcodeColumn(string $columnName): void
    {
        static::$barcodeColumn = $columnName;
    }
}
