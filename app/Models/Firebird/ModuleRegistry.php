<?php

namespace App\Models\Firebird;

use Illuminate\Support\Facades\File;
use App\Models\Stamm\BaseStamm;
use App\Models\Pruef\BasePruef;

class ModuleRegistry
{
    protected static $stammModels = null;
    protected static $pruefModels = null;

    /**
     * Alle Stamm-Modelle automatisch erkennen
     */
    public static function getStammModels()
    {
        if (static::$stammModels !== null) {
            return static::$stammModels;
        }

        $path = app_path('Models/Stamm');
        $namespace = 'App\\Models\\Stamm\\';

        static::$stammModels = static::scanModels($path, $namespace, BaseStamm::class);        

        return static::$stammModels;
    }

    /**
     * Alle Prüf-Modelle automatisch erkennen
     */
    public static function getPruefModels()
    {
        if (static::$pruefModels !== null) {
            return static::$pruefModels;
        }

        $path = app_path('Models/Pruef');
        $namespace = 'App\\Models\\Pruef\\';

        static::$pruefModels = static::scanModels($path, $namespace, BasePruef::class);
        
        return static::$pruefModels;
    }

    /**
     * Model-Scanner für Unterordner + Namespace
     */
    protected static function scanModels($path, $namespace, $baseClass)
    {
        if (!is_dir($path)) {            
            return [];
        }

        $files = File::allFiles($path);
        $models = [];

        foreach ($files as $file) {
            $className = $namespace . $file->getFilenameWithoutExtension();        

            if (!class_exists($className)) {
                continue;
            }                    

            // checkt ob dieses Model das Base Model ist
            if ($className != $baseClass) {
                $models[] = $className;
            }
        }
                
        return $models;
    }

    /**
     * Prüftabelle für ein Stamm-Modell finden
     */
    public static function findPruefModelForStamm($stammModel)
    {        
        $module = $stammModel::getModule();        

        foreach (static::getPruefModels() as $pruefModel) {
            if ($pruefModel::getModule() === $module) {
                return $pruefModel;
            }
        }

        return null;
    }
}
