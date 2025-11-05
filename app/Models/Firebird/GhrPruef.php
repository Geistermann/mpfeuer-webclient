<?php

namespace App\Models\Firebird;

use PDO;

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
            SELECT * FROM GHR_PRUEF 
            WHERE GHR_INDEX = :ghrId 
            ORDER BY GHR_PRUEF_DAT DESC
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
                  AND (GHR_PRUEF_DAT > :today OR GHR_PRUEF_HDZ IS NULL OR GHR_PRUEF_OK = 0)
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
                SET GHR_PRUEF_HDZ = :handzeichen,
                    GHR_PRUEF_OK = 1
                WHERE ' . static::$primaryKey . ' = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':handzeichen', $handzeichen);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }
}
