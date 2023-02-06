# Installation

**todo: translate to en**

Pseudify kann in 2 Varianten verwendet werden

* mittels docker container (empfohlen)
* mittels phar Archiv

## docker Image (empfohlen)

!!! info "Abhängigkeiten"
    Folgende Komponenten müssen installiert werden:

    * [docker](https://docs.docker.com/get-docker/)

Der docker container enthält alle benötigten Abhängigkeiten, um pseudify mit allen unterstützten Datenbanktypen auszuführen.  

* Starte mit den Profile Templates

!!! info "Profile Templates"
    Die [Profile Templates](https://github.com/waldhacker/pseudify-profile-templates) beinhalten die Grundkonfiguration für pseudify und liefern Basisprofile für diverse Applikationen.  
    Sie sind die ideale Grundlage, um die Pseudonymisierung Deiner Applikation zu modellieren.

Download der "Profile Templates":

```shell
docker run -it -v $(pwd):/app -u $(id -u):$(id -g) \
  composer create-project --no-dev --remove-vcs waldhacker/pseudify-profile-templates .
```

* Die .env erzeugen und editieren

```shell
cp .env.example .env
```

* Testen ob alles funktioniert

```shell
docker run -it -v $(pwd):/data \
  ghcr.io/waldhacker/pseudify pseudify:information
```

## PHAR Archiv

!!! info "Abhängigkeiten"
    Wird das PHAR Archiv verwendet, so müssen die benötigten Abhängigkeiten manuell installiert werden.
    Folgende Komponenten müssen installiert werden:

    * PHP 8.1

    Folgende PHP-Erweiterungen müssen installiert werden, je nachdem welche Datenbanktypen verwendet werden:

    * pdo_mysql (A MySQL driver that uses the pdo_mysql PDO extension)
    * mysqli (A MySQL driver that uses the mysqli extension)
    * pdo_pgsql (A PostgreSQL driver that uses the pdo_pgsql PDO extension)
    * pdo_sqlite (An SQLite driver that uses the pdo_sqlite PDO extension)
    * sqlite3 (An SQLite driver that uses the sqlite3 extension)
    * pdo_sqlsrv (A Microsoft SQL Server driver that uses pdo_sqlsrv PDO)
        * [Microsoft ODBC Driver for SQL Server](https://learn.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-ver15)
    * sqlsrv (A Microsoft SQL Server driver that uses the sqlsrv PHP extension)
        * [Microsoft ODBC Driver for SQL Server](https://learn.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-ver15)
    * pdo_oci (An Oracle driver that uses the pdo_oci PDO extension (not recommended by doctrine))
    * oci8 (An Oracle driver that uses the oci8 PHP extension)
    * pdo_ibm (An DB2 driver that uses the pdo_ibm PHP extension)
    * ibm_db2 (An DB2 driver that uses the ibm_db2 extension)

* Starte mit den Profile Templates

!!! info "Profile Templates"
    Die [Profile Templates](https://github.com/waldhacker/pseudify-profile-templates) beinhalten die Grundkonfiguration für pseudify und liefern Basisprofile für diverse Applikationen.  
    Sie sind die ideale Grundlage, um die Pseudonymisierung Deiner Applikation zu modellieren.

Download der "Profile Templates":

```shell
docker run -it -v $(pwd):/app -u $(id -u):$(id -g) \
  composer create-project --no-dev --remove-vcs waldhacker/pseudify-profile-templates .
```

* Das PHAR Archiv in denselben Ordner herunterladen, in dem die pseudify Profile aus dem vorherigen Schritt installiert wurden.

```shell
curl -sLo pseudify https://github.com/waldhacker/pseudify-core/releases/latest/download/pseudify.phar
chmod u+x pseudify
```

* Die .env erzeugen und editieren

```shell
cp .env.example .env
```

* Testen ob alles funktioniert

```shell
./pseudify pseudify:information
```

!!! info "alternativer Installationsort"
    Das pseudify PHAR Archiv kann auch an einem anderen Ort (z.B. global unter `/usr/bin/pseudify`) installiert werden.
    Mit dem Parameter `--data` kann pseudify der Pfad zu den pseudify Profilen, welche verwendet werden sollen, mitgeteilt werden.
    ```shell
    /usr/bin/pseudify --data /home/project/path/to/pseudify-profile-templates pseudify:information
    ```
