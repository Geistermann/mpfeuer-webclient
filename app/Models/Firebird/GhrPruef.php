<?php

namespace App\Models\Firebird;

use PDO;
use Illuminate\Support\Str;

class GhrPruef extends FirebirdModel
{
    protected static string $table = 'GHR_PRUEF';
    protected static string $primaryKey = 'GHR_ID'; 
    protected static string $foreignKey = 'GHR_INDEX';

    /**
     * Gibt alle Prüfungen zu einem GHR_INDEX zurück
     */
    public static function forGhr(string $ghrId): array
    {
        $pdo = static::getConnection();
        $stmt = $pdo->prepare("
            SELECT * FROM " . static::$table . " 
            WHERE " . static::$foreignKey . " = :ghrId 
            ORDER BY " . static::$table . "_DAT DESC
        ");
        $stmt->execute(['ghrId' => $ghrId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Offene Prüfungen (Datum in Zukunft oder unvollständig)
     */
    public static function offenePruefungen(string $ghrIndex): array
    {
        $pdo = static::getConnection();
        $today = Carbon::now()->format('Y-m-d');

        $sql = 'SELECT * FROM ' . static::$table . ' 
                WHERE ' . static::$foreignKey . ' = :ghrIndex
                  AND (' . static::$table . '_DAT > :today OR ' . static::$table . '_HDZ IS NULL OR ' . static::$table . '_OK = 0)
                ORDER BY GHR_PRUEF_DAT ASC';

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ghrIndex', $ghrIndex);
        $stmt->bindValue(':today', $today);
        $stmt->execute();

        return $stmt->fetchAll();
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
                WHERE ' . static::$primaryKey . ' = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':handzeichen', $handzeichen);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    /**
     * Holt alle Prüfungsdefinitionen aus PAR_PRUEF, die zu Modul GHR gehören.
     */
    public static function getAvailablePruefungen()
    {
        $pdo = static::getConnection();

        $sql = "SELECT * FROM PAR_PRUEF WHERE PAR_MODUL = 'GHR' ORDER BY PAR_PRUEF_LANG";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Erstellt eine neue Prüfung in GHR_PRUEF.
     *
     * @param string $ghrIndex - Fremdschlüssel zum GHR-Stamm (z. B. GHR_STAMM.GHR_ID)     
     * @param string|null $handzeichen - Kürzel der Person
     * @return bool
     */
    public static function createNewPruefung($ghrIndex, $parPruefId, $handzeichen)
    {
        $pdo = static::getConnection();

        $uuid = strtoupper(Str::uuid()->toString());
        $datum = date('Y-m-d');

        $sql = "INSERT INTO GHR_PRUEF 
                (GHR_ID, GHR_INDEX, GHR_PRUEF_LANG, GHR_PRUEF_HDZ, GHR_PRUEF_OK, GHR_PRUEF_DAT)
                VALUES (:id, :ghrIndex, 'test prüfung', :handzeichen, 1, :datum)";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $uuid,
            ':ghrIndex' => $ghrIndex,                        
            ':handzeichen' => $handzeichen,
            ':datum' => $datum,
        ]);
    }
}
