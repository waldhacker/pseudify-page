# Konfiguration

## .env

Die Grundkonfiguration von pseudify findet mittels Werten in einer `.env` Datei statt.  
Die [Profile Templates](https://github.com/waldhacker/pseudify-profile-templates) beinhalten eine [exemplarische .env Datei](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/.env.example) welche als Grundlage für die eigene Konfiguration verwendet werden kann.  

### APP_SECRET

Default: &lt;leer&gt;

Pseudify cached die Eingangsdaten, um pro Pseudonymisierungslauf gleiche Pseudonyme für gleiche Eingangsdaten erzeugen zu können.  
Damit die zu pseudonymisierenden Eingangsdaten nicht im Klartext im Cache abgespeichert werden, werden sie zur Sicherheit mittels SHA-256 Hash-Algorithmus verarbeitet und dann abgespeichert.  
Damit von den SHA-256 Hashwerten im Cache keine Rückschlüsse auf die Eingangsdaten gezogen werden können, **wird dringend empfohlen den Wert von `APP_SECRET` auf einen möglichst langen zufälligen Wert zu setzen**.  
Der Wert von `APP_SECRET` **ist als Geheimnis zu behandeln**, so wie ein Passwort.  

#### Beispiel

```shell
APP_SECRET=6ba571b0a3e7150a4b7e5b918e81ce8f
```

### PSEUDIFY_FAKER_LOCALE

Default: en_US

Pseudify benutzt die [FakerPHP/Faker Komponente](https://fakerphp.github.io/), um die Pseudonyme zu generieren.  
Die Komponente erlaubt [die Generierung von sprachspezifischen Werten](https://fakerphp.github.io/#language-specific-formatters).  
Unterstützte Werte von `PSEUDIFY_FAKER_LOCALE` finden sich im [FakerPHP/Faker Repository](https://github.com/FakerPHP/Faker/tree/v1.20.0/src/Faker/Provider).  

#### Beispiel

```shell
PSEUDIFY_FAKER_LOCALE=de_DE
```

### PSEUDIFY_DATABASE_DRIVER

Default: pdo_mysql  
Wird aufgelöst zu Verbindungsparameter: [`doctrine.dbal.connections.default.driver`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L5)

Der Wert von `PSEUDIFY_DATABASE_DRIVER` muss [ein unterstützter Treiber der Doctrine DBAL Komponente](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#driver) sein.  
Der pseudify docker Container wird mit folgender Treiberunterstützung ausgeliefert:

* pdo_mysql (A MySQL driver that uses the pdo_mysql PDO extension
* mysqli (A MySQL driver that uses the mysqli extension
* pdo_pgsql (A PostgreSQL driver that uses the pdo_pgsql PDO extension)
* pdo_sqlite (An SQLite driver that uses the pdo_sqlite PDO extension)
* sqlite3 (An SQLite driver that uses the sqlite3 extension)
* pdo_sqlsrv (A Microsoft SQL Server driver that uses pdo_sqlsrv PDO)
* sqlsrv (A Microsoft SQL Server driver that uses the sqlsrv PHP extension)

!!! info
    Die Unterstützung des `oci8` Treibers für Oracle Datenbanken im docker Container ist in Vorbereitung (pull requests sind willkommen).

#### Beispiel

```shell
PSEUDIFY_DATABASE_DRIVER=pdo_mysql
```

### PSEUDIFY_DATABASE_HOST

Default: &lt;leer&gt;  
Wird aufgelöst zu Verbindungsparameter: [`doctrine.dbal.connections.default.host`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L6)

Der Hostname unter welchem der Datenbankserver erreichbar ist.  
Dieser Wert wird nur bei der Nutzung der folgenden Treiber verwendet:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

#### Beispiel

```shell
PSEUDIFY_DATABASE_HOST=host.docker.internal
```

### PSEUDIFY_DATABASE_PORT

Default: &lt;leer&gt;  
Wird aufgelöst zu Verbindungsparameter: [`doctrine.dbal.connections.default.port`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L7)

Der Port unter welchem der Datenbankserver erreichbar ist.  
Dieser Wert wird nur bei der Nutzung der folgenden Treiber verwendet:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

#### Beispiel

```shell
PSEUDIFY_DATABASE_PORT=3306
```

### PSEUDIFY_DATABASE_USER

Default: &lt;leer&gt;  
Wird aufgelöst zu Verbindungsparameter: [`doctrine.dbal.connections.default.user`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L8)

Der Benutzername der Datenbank.  
Dieser Wert wird nur bei der Nutzung der folgenden Treiber verwendet:

* [pdo_sqlite](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlite)
* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

#### Beispiel

```shell
PSEUDIFY_DATABASE_USER=pseudify
```

### PSEUDIFY_DATABASE_PASSWORD

Default: &lt;leer&gt;  
Wird aufgelöst zu Verbindungsparameter: [`doctrine.dbal.connections.default.password`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L9)

Das Passwort der Datenbank.  
Dieser Wert wird nur bei der Nutzung der folgenden Treiber verwendet:

* [pdo_sqlite](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlite)
* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

#### Beispiel

```shell
PSEUDIFY_DATABASE_PASSWORD='super(!)sEcReT'
```

### PSEUDIFY_DATABASE_SCHEMA

Default: &lt;leer&gt;  
Wird aufgelöst zu Verbindungsparameter: [`doctrine.dbal.connections.default.dbname`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L10) / [`doctrine.dbal.connections.default.path`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L11)

Der Name der Datenbank.  
Bei folgenden Treibern entspricht `PSEUDIFY_DATABASE_SCHEMA` dem Datenbanknamen:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

Bei folgenden Treibern entspricht `PSEUDIFY_DATABASE_SCHEMA` dem Dateisystempfad zur Datenbank:

* [pdo_sqlite](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlite)
* [sqlite3](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#sqlite3)

#### Beispiel

```shell
PSEUDIFY_DATABASE_SCHEMA=wordpress_prod
```

### PSEUDIFY_DATABASE_CHARSET

Default: utf8mb4
Wird aufgelöst zu Verbindungsparameter: [`doctrine.dbal.connections.default.charset`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L12)

Der Zeichensatz, der bei der Verbindung zur Datenbank verwendet wird.
Dieser Wert wird nur bei der Nutzung der folgenden Treiber verwendet:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)

#### Beispiel

```shell
PSEUDIFY_DATABASE_CHARSET=utf8mb4
```

### PSEUDIFY_DATABASE_VERSION

Default: &lt;leer&gt;  
Wird aufgelöst zu Verbindungsparameter: [`doctrine.dbal.connections.default.server_version`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L13)

Doctrine wird mit verschiedenen Datenbankplattform-Implementierungen für einige Anbieter geliefert, um versionsspezifische Funktionen, Dialekte und Verhaltensweisen zu unterstützen.  
Die Treiber erkennen automatisch die Plattformversion und instanziieren die entsprechende Plattformklasse.  
Wenn Du die automatische Erkennung der Datenbankplattform deaktivieren und die Implementierung der Plattformversion explizit auswählen möchtest, kannst Du dies mit dem Wert in `PSEUDIFY_DATABASE_VERSION` erledigen.  

!!! info
    Wenn Du eine MariaDB-Datenbank verwendest, solltest Du dem Wert `PSEUDIFY_DATABASE_VERSION` den Präfix `mariadb-` voranstellen (Beispiel: mariadb-10.2).

#### Beispiel

```shell
PSEUDIFY_DATABASE_VERSION=8.0
```

### PSEUDIFY_DATABASE_SSL_INSECURE

Default: &lt;leer&gt;  
Wird aufgelöst zu Verbindungsparameter: [`doctrine.dbal.connections.default.options.TrustServerCertificate`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L15)

Wird der Wert von `PSEUDIFY_DATABASE_SSL_INSECURE` auf `1` gesetzt, so wird keine Überprüfung des TLS-Zertifikats des Datenbankservers vorgenommen.

Dieser Wert wird nur bei der Nutzung der folgenden Treiber verwendet:

* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

```shell
PSEUDIFY_DATABASE_SSL_INSECURE=1
```

## Erweiterte Verbindungseinstellungen

Wenn Du weitere Treiberoptionen konfigurieren musst, so kannst Du dies in der Datei [`config/configuration.yaml`](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml#L4) tun.  
Beispiele und Informationen für Treiberoptionen finden sich in folgenden Dokumenten:

* [Symfony DoctrineBundle - Doctrine DBAL Configuration](https://symfony.com/doc/current/reference/configuration/doctrine.html#doctrine-dbal-configuration)
* [Doctrine DBAL- Connection Details](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#connection-details)

Nach Änderungen an den Verbindungseinstellungen muss der Cache geleert werden 

```shell
pseudify cache:clear
```

### Multiple Verbindungskonfigurationen

Es ist möglich, mehrere Verbindungen zu konfigurieren.  
Als Standardverbindung wird die [Verbindung mit dem Namen `default` verwendet](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L4).  
In der Datei [`config/configuration.yaml`](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml#L4) können weitere Verbindungen unter anderem Namen konfiguriert werden.


```yaml
doctrine:
  dbal:
    connections:
      myCustomConnection:
        driver: sqlsrv
        # ...
```

Die konfigurierten Verbindungen können mit dem Parameter `--connection` verwendet werden

```shell
pseudify pseudify:pseudonymize --connection myCustomConnection myPseudonymizationProfileName
```

```shell
pseudify pseudify:debug:pseudonymize --connection myCustomConnection myPseudonymizationProfileName
```

```shell
pseudify pseudify:analyze --connection myCustomConnection myAnalysisProfileName
```

```shell
pseudify pseudify:debug:analyze --connection myCustomConnection myAnalysisProfileName
```

```shell
pseudify pseudify:debug:table_schema --connection myCustomConnection
```

## Registrieren von benutzerdefinierten Datentypen

Werden benutzerdefinierte Datentypen benötigt, so kannst Du diese auf Verbindungsebene in der Datei [`config/configuration.yaml`](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml#L5-L13) definieren.  

Beispielimplementationen für benutzerdefinierte Datentypen finden sich in folgenden Dateien:

* [src/Types/TYPO3/EnumType.php](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Types/TYPO3/EnumType.php)
* [src/Types/TYPO3/SetType.php](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Types/TYPO3/SetType.php)

Diese benutzerdefinierten Datentypen können dann mittels Konfiguration in der Datei [`config/configuration.yaml`](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml) verwendet werden

```yaml
doctrine:
  dbal:
    connections:
      default:
        types:
          enum: Waldhacker\Pseudify\Types\TYPO3\EnumType
          set: Waldhacker\Pseudify\Types\TYPO3\SetType
        mapping_types:
          enum: enum
          set: set
```

Beispiele und Informationen für benutzerdefinierte Datentypen finden sich in folgenden Dokumenten:

* [Symfony DoctrineBundle - Registering custom Mapping Types](https://symfony.com/doc/current/doctrine/dbal.html#registering-custom-mapping-types)
* [Symfony DoctrineBundle - Registering custom Mapping Types in the SchemaTool](https://symfony.com/doc/current/doctrine/dbal.html#registering-custom-mapping-types-in-the-schematool)
* [Doctrine DBAL - Custom Mapping Types](https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html#custom-mapping-types)

Nach dem Hinzufügen benutzerdefinierter Datentypen muss der Cache geleert werden 

```shell
pseudify cache:clear
```

## Registrieren von benutzerdefinierten Faker-Formatierern

Die [FakerPHP/Faker Komponente](https://fakerphp.github.io/) bringt eine Menge vordefinierter Formatierer mit um diverse Datenformate zu generieren.  
Wenn Du benutzerdefinierte Formatierer verwenden möchtest, so kannst Du die Implementierung am Beispiel des [BobRossLipsumProvider](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Faker/BobRossLipsumProvider.php) abschauen.  
Der benutzerdefinierte Formatierer muss das Interface [`Waldhacker\Pseudify\Core\Faker\FakeDataProviderInterface`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Faker/FakeDataProviderInterface.php) implementieren, damit er ins System integriert wird.  
Wie Formatierer Daten generieren können lässt sich am Besten anhand [der Provider im FakerPHP/Faker Projekt](https://github.com/FakerPHP/Faker/tree/v1.20.0/src/Faker/Provider) abschauen.  

Nach dem Hinzufügen benutzerdefinierter Faker-Formatierern muss der Cache geleert werden 

```shell
pseudify cache:clear
```

## Registrieren von benutzerdefinierten Dekodierern / Enkodierern

Das pseudify [`EncoderInterface`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/EncoderInterface.php) ist kompatibel zum [`EncoderInterface` und `DecoderInterface` der Symfony serializer Komponente](https://symfony.com/doc/current/components/serializer.html#encoders).  
Wenn Du benutzerdefinierte Dekodierer / Enkodierer verwenden möchtest, so kannst Du die Implementierung am Beispiel des [Rot13Encoder](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Encoder/Rot13Encoder.php) abschauen.  
Der benutzerdefinierte Dekodierer / Enkodierer muss das Interface [`Waldhacker\Pseudify\Core\Processor\Encoder\EncoderInterface`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/EncoderInterface.php) implementieren, damit er ins System integriert wird.  
Wie Dekodierer / Enkodierer Daten dekodieren und encodieren können lässt sich am Besten anhand [der Built-in Dekodierer / Enkodierer](https://github.com/waldhacker/pseudify-core/tree/0.0.1/src/src/Processor/Encoder) abschauen.  

Nach dem hinzufügen benutzerdefinierter Dekodierer / Enkodierer muss der Cache geleert werden 

```shell
pseudify cache:clear
```

!!! note
    Benutzerdefinierte Dekodierer / Enkodierer sollten der Namenskonvention `<Format>Encoder` folgen (z.B. `HexEncoder`, `Rot13Encoder` uws.).
    Dies stellt sicher, dass Debug-Kommandos wie `pseudify:debug:analyze` die Namen der Dekodierer / Enkodierer gut darstellen können.  

## Zugriff auf Host-Datenbankserver aus dem docker Container heraus

Möchte man auf Datenbankserver, welche auf dem Host-System laufen, aus dem docker Container heraus zugreifen, so kann dies auf unterschiedlichen Wegen umgesetzt werden.  
Nachfolgend werden 3 beschrieben.

### add-host Variante

Füge dem `docker run` Befehl den Parameter `--add-host=host.docker.internal:host-gateway` hinzu um innerhalb des docker Containers die IP-Adresse des docker gateways auf dem Host-System unter dem Hostnamen `host.docker.internal` zur Verfügung zu stellen.  
Die Option `PSEUDIFY_DATABASE_HOST` [in der .env Datei](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/.env.example#L6) muss den Wert `host.docker.internal` erhalten.   

!!! note
    Damit diese Variante funktioniert, muss der Port des Datenbankservers auf dem docker gateway geöffnet sein.

#### Beispiel

.env:

```shell
PSEUDIFY_DATABASE_HOST=host.docker.internal
```

Befehl:

```shell
docker run -it -v $(pwd):/data --add-host=host.docker.internal:host-gateway \
  ghcr.io/waldhacker/pseudify pseudify:debug:table_schema
```

### Host-IP Variante

Die Option `PSEUDIFY_DATABASE_HOST` [in der .env Datei](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/.env.example#L6) muss die IP-Adresse des Host-Systems erhalten.   

!!! note
    Damit diese Variante funktioniert, muss der Port des Datenbankservers auf dieser IP des Host-Systems geöffnet sein.

#### Beispiel

.env:

```shell
PSEUDIFY_DATABASE_HOST=192.168.178.31
```

Befehl:

```shell
docker run -it -v $(pwd):/data ghcr.io/waldhacker/pseudify pseudify:debug:table_schema
```

### Geschwisterservice Variante

Der Datenbankserver wird parallel zum pseudify Container mittels docker gestartet.  
Beide Container werden mit demselben docker Netzwerk verbunden und können somit untereinander kommunizieren.  

#### Beispiel

[Erzeugen des gemeinsamen docker Netzwerks](https://docs.docker.com/engine/reference/commandline/network_create/) (falls noch keines vorhanden ist) mit dem Namen `pseudify-net`:

```shell
docker network create pseudify-net
```

Started des Datenbankservers am Beispiel [des MariaDB Containers](https://hub.docker.com/_/mariadb).  
Der Datenbankserver wird gestartet und in das Netzwerk `pseudify-net` aufgenommen (`--network pseudify-net`). Dem Container wird der Name `mariadb_10_5` gegeben (`--name mariadb_10_5`), unter welchem die Datenbank dann für den pseudify Container erreichbar sein wird.  

!!! note
    Damit der Import der Testdatenbank (`-v $(pwd)/tests/mariadb/10.5:/docker-entrypoint-initdb.d`) korrekt funktioniert, muss der Befehl im Hauptverzeichnis der [Profile Templates](https://github.com/waldhacker/pseudify-profile-templates) ausgeführt werden.

```shell
docker run --rm --detach \
  --network pseudify-net \
  --name mariadb_10_5 \
  --env MARIADB_USER=pseudify \
  --env MARIADB_PASSWORD='pseudify(!)w4ldh4ck3r' \
  --env MARIADB_ROOT_PASSWORD='pseudify(!)w4ldh4ck3r' \
  --env MARIADB_DATABASE=pseudify_utf8mb4 \
  -v $(pwd)/tests/mariadb/10.5:/docker-entrypoint-initdb.d \
  mariadb:10.5

cp tests/mariadb/10.5/.env .env
```

.env:

```shell
PSEUDIFY_DATABASE_HOST=mariadb_10_5
```

Befehl:

```shell
docker run -it -v $(pwd):/data --network=pseudify-net \
  ghcr.io/waldhacker/pseudify pseudify:debug:table_schema
```

## Überblick über die Konfiguration

Es existieren Kommandos, um die konfiguration des Systems zu überprüfen

### pseudify:information

Das Kommando `pseudify pseudify:information` listet:

* verfügbare Profile, um die Datenbank zu analysieren (`Registered analyze profiles`)
* verfügbare Profile, um die Datenbank zu pseudonymisieren (`Registered pseudonymize profiles`)
* registrierte Datentypen (`Registered doctrine types`)
* im System verfügbare Datenbanktreiber (`Available built-in database drivers`)
* Informationen pro konfigurierte Verbindung (`Connection information for connection "<connecntion name>"`)
* Informationen welche Datenbank-Datentypen mit welchen Doctrine Implementierungen verknüpft sind (`Registered doctrine database data type mappings`)
* Informationen über die verwendeten Doctrine Treiber-Implementierungen und den verwendeten Systemtreiber (`Connection details`)

```shell
$ pseudify pseudify:information

Registered analyze profiles
---------------------------

 -------------- 
  Profile name  
 -------------- 
  typo3Example  
  test-profile  
 -------------- 

Registered pseudonymize profiles
--------------------------------

 -------------- 
  Profile name  
 -------------- 
  typo3Example  
  test          
 -------------- 

Registered doctrine types
-------------------------

 ---------------------- --------------------------------------------- 
  Doctrine type name     Doctrine type implementation                 
 ---------------------- --------------------------------------------- 
  array                  Doctrine\DBAL\Types\ArrayType                
  ascii_string           Doctrine\DBAL\Types\AsciiStringType          
  bigint                 Doctrine\DBAL\Types\BigIntType               
  binary                 Doctrine\DBAL\Types\BinaryType               
  blob                   Doctrine\DBAL\Types\BlobType                 
  boolean                Doctrine\DBAL\Types\BooleanType              
  date                   Doctrine\DBAL\Types\DateType                 
  date_immutable         Doctrine\DBAL\Types\DateImmutableType        
  dateinterval           Doctrine\DBAL\Types\DateIntervalType         
  datetime               Doctrine\DBAL\Types\DateTimeType             
  datetime_immutable     Doctrine\DBAL\Types\DateTimeImmutableType    
  datetimetz             Doctrine\DBAL\Types\DateTimeTzType           
  datetimetz_immutable   Doctrine\DBAL\Types\DateTimeTzImmutableType  
  decimal                Doctrine\DBAL\Types\DecimalType              
  float                  Doctrine\DBAL\Types\FloatType                
  guid                   Doctrine\DBAL\Types\GuidType                 
  integer                Doctrine\DBAL\Types\IntegerType              
  json                   Doctrine\DBAL\Types\JsonType                 
  object                 Doctrine\DBAL\Types\ObjectType               
  simple_array           Doctrine\DBAL\Types\SimpleArrayType          
  smallint               Doctrine\DBAL\Types\SmallIntType             
  string                 Doctrine\DBAL\Types\StringType               
  text                   Doctrine\DBAL\Types\TextType                 
  time                   Doctrine\DBAL\Types\TimeType                 
  time_immutable         Doctrine\DBAL\Types\TimeImmutableType        
 ---------------------- --------------------------------------------- 

Available built-in database drivers
-----------------------------------

 ------------ ------------------------------------------------------------------------------------ ------------------- 
  Driver       Description                                                                          Installed version  
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  MySQL / MariaDB                                                                                                      
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  pdo_mysql    A MySQL driver that uses the pdo_mysql PDO extension                                 8.1.14             
  mysqli       A MySQL driver that uses the mysqli extension                                        8.1.14             
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  PostgreSQL                                                                                                           
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  pdo_pgsql    A PostgreSQL driver that uses the pdo_pgsql PDO extension                            8.1.14             
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  SQLite                                                                                                               
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  pdo_sqlite   An SQLite driver that uses the pdo_sqlite PDO extension                              8.1.14             
  sqlite3      An SQLite driver that uses the sqlite3 extension                                     8.1.14             
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  SQL Server                                                                                                           
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  pdo_sqlsrv   A Microsoft SQL Server driver that uses pdo_sqlsrv PDO                               5.10.1             
  sqlsrv       A Microsoft SQL Server driver that uses the sqlsrv PHP extension                     5.10.1             
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  Oracle Database                                                                                                      
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  pdo_oci      An Oracle driver that uses the pdo_oci PDO extension (not recommended by doctrine)   N/A                
  oci8         An Oracle driver that uses the oci8 PHP extension                                    N/A                
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  IBM DB2                                                                                                              
 ------------ ------------------------------------------------------------------------------------ ------------------- 
  pdo_ibm      An DB2 driver that uses the pdo_ibm PHP extension                                    N/A                
  ibm_db2      An DB2 driver that uses the ibm_db2 extension                                        N/A                
 ------------ ------------------------------------------------------------------------------------ ------------------- 

Connection information for connection "default"
===============================================

Registered doctrine database data type mappings
-----------------------------------------------

 --------------- -------------------- ------------------------------------- 
  Database type   Doctrine type name   Doctrine type implementation         
 --------------- -------------------- ------------------------------------- 
  bigint          bigint               Doctrine\DBAL\Types\BigIntType       
  binary          binary               Doctrine\DBAL\Types\BinaryType       
  blob            blob                 Doctrine\DBAL\Types\BlobType         
  char            string               Doctrine\DBAL\Types\StringType       
  date            date                 Doctrine\DBAL\Types\DateType         
  datetime        datetime             Doctrine\DBAL\Types\DateTimeType     
  decimal         decimal              Doctrine\DBAL\Types\DecimalType      
  double          float                Doctrine\DBAL\Types\FloatType        
  float           float                Doctrine\DBAL\Types\FloatType        
  int             integer              Doctrine\DBAL\Types\IntegerType      
  integer         integer              Doctrine\DBAL\Types\IntegerType      
  longblob        blob                 Doctrine\DBAL\Types\BlobType         
  longtext        text                 Doctrine\DBAL\Types\TextType         
  mediumblob      blob                 Doctrine\DBAL\Types\BlobType         
  mediumint       integer              Doctrine\DBAL\Types\IntegerType      
  mediumtext      text                 Doctrine\DBAL\Types\TextType         
  numeric         decimal              Doctrine\DBAL\Types\DecimalType      
  real            float                Doctrine\DBAL\Types\FloatType        
  set             simple_array         Doctrine\DBAL\Types\SimpleArrayType  
  smallint        smallint             Doctrine\DBAL\Types\SmallIntType     
  string          string               Doctrine\DBAL\Types\StringType       
  text            text                 Doctrine\DBAL\Types\TextType         
  time            time                 Doctrine\DBAL\Types\TimeType         
  timestamp       datetime             Doctrine\DBAL\Types\DateTimeType     
  tinyblob        blob                 Doctrine\DBAL\Types\BlobType         
  tinyint         boolean              Doctrine\DBAL\Types\BooleanType      
  tinytext        text                 Doctrine\DBAL\Types\TextType         
  varbinary       binary               Doctrine\DBAL\Types\BinaryType       
  varchar         string               Doctrine\DBAL\Types\StringType       
  year            date                 Doctrine\DBAL\Types\DateType         
  json            json                 Doctrine\DBAL\Types\JsonType         
  _text           text                 Doctrine\DBAL\Types\TextType         
 --------------- -------------------- ------------------------------------- 

Connection details
------------------

 --------------------------------------- ----------------------------------------- 
  Name                                    Value                                    
 --------------------------------------- ----------------------------------------- 
  Used connection implementation          Doctrine\DBAL\Connection                 
  Used database driver implementation     Doctrine\DBAL\Driver\PDO\MySQL\Driver    
  Used database platform implementation   Doctrine\DBAL\Platforms\MySQL80Platform  
  Used database platform version          10.5                                     
  Used built-in database driver           pdo_mysql (8.1.14)                       
 --------------------------------------- -----------------------------------------
```

### debug:config DoctrineBundle

Das Kommando listet die zusammengefasste Datenbankkonfiguration auf, welche aus [der Core-Konfiguration](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml)
und [der benutzerdefinierten Konfiguration](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml) besteht.

```shell
$ pseudify debug:config DoctrineBundle

Current configuration for "DoctrineBundle"
==========================================

doctrine:
    dbal:
        connections:
            default:
                driver: '%env(PSEUDIFY_DATABASE_DRIVER)%'
                host: '%env(PSEUDIFY_DATABASE_HOST)%'
                port: '%env(PSEUDIFY_DATABASE_PORT)%'
                user: '%env(PSEUDIFY_DATABASE_USER)%'
                password: '%env(PSEUDIFY_DATABASE_PASSWORD)%'
                dbname: '%env(PSEUDIFY_DATABASE_SCHEMA)%'
                path: '%env(PSEUDIFY_DATABASE_SCHEMA)%'
                charset: '%env(PSEUDIFY_DATABASE_CHARSET)%'
                server_version: '%env(PSEUDIFY_DATABASE_VERSION)%'
                options:
                    TrustServerCertificate: '%env(PSEUDIFY_DATABASE_SSL_INSECURE)%'
                mapping_types:
                    _text: text
                logging: false
                profiling: false
                profiling_collect_backtrace: false
                profiling_collect_schema_errors: true
                default_table_options: {  }
                slaves: {  }
                replicas: {  }
                shards: {  }
        types: {  }
```

### debug:dotenv

Das Kommando listet die Werte aus der `.env` Datei auf.

```shell
$ pseudify debug:dotenv

Dotenv Variables & Files
========================

Variables
---------

 ---------------------------- ----------------------- 
  Variable                     Value                  
 ---------------------------- ----------------------- 
  APP_ENV                      dev                    
  PSEUDIFY_DATABASE_CHARSET    utf8mb4                
  PSEUDIFY_DATABASE_DRIVER     pdo_mysql              
  PSEUDIFY_DATABASE_HOST       mariadb_10_5           
  PSEUDIFY_DATABASE_PASSWORD   pseudify(!)w4ldh4ck3r  
  PSEUDIFY_DATABASE_PORT       3306                   
  PSEUDIFY_DATABASE_SCHEMA     pseudify_utf8mb4       
  PSEUDIFY_DATABASE_USER       pseudify               
  PSEUDIFY_DATABASE_VERSION    10.5                   
 ---------------------------- -----------------------
```
