# 🔥 MPFeuer WebClient – Firebird Barcode & Prüfungsverwaltung

Ein Laravel-basiertes Websystem zur Verwaltung von Geräten und deren vorgeschriebenen Prüfungen in MPFeuer-Umgebungen.
Barcode- oder NFC-Tags können gescannt werden, um automatisch Geräte zu finden und fällige Prüfungen durchzuführen.

---

## 📌 Features

### 🔍 **Barcode- & NFC-Suche**

* Manuelle Suche über Eingabefeld (`/barcode`)
* Automatische Suche über URL-Token (`/nfc/search/{token}`)
* Dynamische Anzeige aller gefundenen Datensätze (aus allen Modulen)

### 🧩 **Modulare Architektur**

Das System erkennt automatisch:

* alle Stamm-Modelle (z. B. `GhrStamm`, `GasStamm`, `KldStamm`, `GelStamm`)
* die zugehörigen Prüf-Modelle (z. B. `GhrPruef`, `GasPruef` usw.)

Diese befinden sich in:

```
app/Models/Stamm/
app/Models/Pruef/
```

Die Zuordnung erfolgt dynamisch über die **ModuleRegistry**.

### 🗂 **Dynamische Darstellungen**

* Modulname, Friendly-Name, Tabellenfelder werden dynamisch ausgewertet
* Views sind universell und funktionieren für jedes Modul gleich

### 📝 **Prüfungsverwaltung**

* Anzeige aller vorhandenen Prüfungen (erledigt, offen, zukünftige)
* Direktes Erledigen einer Prüfung über Button
* Erstellen neuer Prüfungen über Dropdown
  → basierend auf Tabelle `PAR_PRUEF` (modulspezifisch)
* Automatische Generierung von GUID-ähnlichen UUIDs

### 🗄 **Firebird-Integration**

* Direkte PDO-Anbindung über ein Firebird-Basismodell
* Alle Modelle erben dynamische Connection-Logik
* `.env`-basierte Konfiguration:

```
FIREBIRD_HOST=x.x.x.x
FIREBIRD_PORT=3050
FIREBIRD_DB_PATH="C:/.../DATA.FDB"
FIREBIRD_USERNAME=SYSDBA
FIREBIRD_PASSWORD=xxxx
FIREBIRD_CHARSET=UTF8
```

---

## 📁 Projektstruktur

```
app/
 ├─ Models/
 │   ├─ Stamm/        → Geräte-Stammdaten (GhrStamm, KldStamm, …)
 │   ├─ Pruef/        → Prüfungsdaten (GhrPruef, KldPruef, …)
 │   └─ Firebird/     → Basismodelle für DB-Zugriff
 ├─ Http/
 │   ├─ Controllers/
 │   │   ├─ BarcodeSearchController   → für manuelle Suche
 │   │   └─ PruefController           → Anlegen & Erledigen von Prüfungen
 │   └─ Middleware/
 └─ Services/
     └─ ModuleRegistry.php            → automatische Modulerkennung
     
resources/
 └─ views/
     ├─ barcode.blade.php
     ├─ results.blade.php
     └─ layouts/
```

---

## ⚙️ Installation

### 1️⃣ Repository klonen

```bash
git clone https://github.com/DEIN_USER/DEIN_REPO.git
cd DEIN_REPO
```

### 2️⃣ Abhängigkeiten installieren

```bash
composer install
npm install && npm run build
```

### 3️⃣ Environment konfigurieren

```bash
cp .env.example .env
```

Firebird-Einträge ergänzen:

```
FIREBIRD_HOST=192.168.x.x
FIREBIRD_PORT=3050
FIREBIRD_DB_PATH="C:/.../DATA.FDB"
FIREBIRD_USERNAME=SYSDBA
FIREBIRD_PASSWORD=masterkey
FIREBIRD_CHARSET=UTF8
```

### 4️⃣ App-Key generieren

```bash
php artisan key:generate
```

### 5️⃣ Berechtigungen setzen (Linux)

```bash
chmod -R 775 storage bootstrap/cache
```

---

## 🧠 Architektur & Funktionsweise

### 🔌 1. Firebird-Connection

Alle Modelle, die direkt mit Firebird arbeiten, erben:

```
App\Models\Firebird\FirebirdModel
```

Diese stellt automatisch eine PDO-Verbindung her.

### 🧩 2. ModuleRegistry

Erkennt dynamisch alle aktiven Module:

* durchsucht `app/Models/Stamm` nach Klassen
* bildet automatisch Prüftabellen aus `app/Models/Pruef` zu
* stellt Friendly Names bereit (z. B. „Gerät (SRHT)“)

### 🔎 3. Suche

Beide Controller (Barcode & NFC) nutzen dieselbe Registry:

```php
$models = ModuleRegistry::getStammModels();
```

### 📋 4. Ergebnisanzeige

In `results.blade.php` wird dynamisch angezeigt:

* Stammdaten
* vorhandene Prüfungen
* offene Prüfungen (rot markiert)
* zukünftige geplante Prüfungen (gelb)
* erledigte Prüfungen (grün)
* Formular zur Erstellung neuer Prüfungen

---

## 🛠 Prüfungen erstellen

Auf Basis der Tabelle `PAR_PRUEF` (modulspezifisch):

* Dropdown listet alle Prüfungen aus `PAR_MODUL = <Modul>`
* ID wird automatisch als UUID generiert
* Speichern erfolgt über `PruefController@createPruefung`

---

## ✔ Prüfung erledigen

Eine Prüfung gilt als erledigt, wenn:

```
*_PRUEF_HDZ ≠ leer
*_PRUEF_OK  = 1
```

Nach dem Speichern erfolgt:

* JSON-Response
* Success-Nachricht
* automatische Weiterleitung zurück auf `/barcode`

---

## 🧪 Entwicklung & Debugging

### Logfiles anzeigen

```bash
tail -f storage/logs/laravel.log
```

### Manuelle Firebird-Tests

```php
dd(\DB::connection('firebird')->select('SELECT * FROM GHR_STAMM'));
```

---

## 📄 Lizenz

MIT-Lizenz oder nach Wunsch anpassbar.

---

## 🤝 Mitwirken

Pull Requests sind willkommen!
Für große Änderungen bitte erst ein Issue öffnen.
