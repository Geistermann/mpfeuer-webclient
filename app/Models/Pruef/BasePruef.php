<?php

namespace App\Models\Pruef;

use App\Models\Firebird\FirebirdModel;
use Illuminate\Support\Str;

abstract class BasePruef extends FirebirdModel
{
    protected static $table = null;
    protected static $module = null;

    public static function getByStammIndex($index)
    {
        $pdo = static::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM " . static::$table . " WHERE " . static::$module. "_INDEX = :idx ORDER BY " . static::$module . "_PRUEF_DAT DESC");
        $stmt->execute(['idx' => $index]);

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getModule()
    {
        return static::$module;
    }

    public static function getTableName()
    {
        return static::$table;
    }

    /**
     * Holt alle Prüfungsdefinitionen aus PAR_PRUEF, die zum Modul gehören
     */
    public static function getAvailablePruefungen()
    {
        $pdo = static::getConnection();

        $sql = "SELECT * FROM PAR_PRUEF WHERE PAR_MODUL = '" . static::$module . "' ORDER BY PAR_PRUEF_LANG";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    /**
     * Erstellt eine neue Prüfung
     *
     * @param string $ghrIndex - Fremdschlüssel zum GHR-Stamm (z. B. GHR_STAMM.GHR_ID)     
     * @param string|null $handzeichen - Kürzel der Person
     * @return bool
     */
    public static function createNewPruefung($module, $pruefModel, $ghrIndex, $parPruefLang, $handzeichen)
    {
        $pdo = static::getConnection();

        $tableName = $pruefModel::getTableName();
        $uuid = strtoupper(Str::uuid()->toString());
        $datum = date('Y-m-d');

        $sql = "INSERT INTO
                    " . $tableName . " (
                    " . $module . "_ID,
                    " . $module . "_INDEX,
                    " . $module . "_PRUEF_LANG,
                    " . $module . "_PRUEF_HDZ,
                    " . $module . "_PRUEF_OK,
                    " . $module . "_PRUEF_DAT,
                    " . $module . "_INFOTEXT
                )
                VALUES (
                    :id,
                    :ghrIndex,
                    :ghrPruefLang,
                    :handzeichen,
                    1,
                    :datum,
                    'Eingegeben via MPFeuer WebClient'
                )";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $uuid,
            ':ghrIndex' => $ghrIndex,
            ':ghrPruefLang' => $parPruefLang,                        
            ':handzeichen' => $handzeichen,
            ':datum' => $datum,
        ]);
    }

    /**
     * Prüfung als erledigt markieren
     */
    public static function markAsDone(string $id, string $handzeichen): bool
    {
        $pdo = static::getConnection();

        $sql = 'UPDATE ' . static::$table . ' 
                SET ' . static::$table . '_HDZ = :handzeichen,
                    ' . static::$table . '_OK = 1
                WHERE ' . static::$module . '_ID = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':handzeichen', $handzeichen);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }
}
