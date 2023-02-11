# Installation

Pseudify can be used in 2 variants

* via docker container (recommended)
* via phar archive

## docker Image (recommended)

!!! info "Dependencies"
    The following components must be installed:

    * [docker](https://docs.docker.com/get-docker/)

The docker container contains all the dependencies needed to run pseudify with all supported database types.  

* Start with the profile templates

!!! info "Profile templates"
    The [profile templates](https://github.com/waldhacker/pseudify-profile-templates) contain the basic configuration for pseudify and provide basic profiles for various applications.  
    They are the ideal basis for modelling the pseudonymisation of your application.

Download the profile templates:

```shell
docker run -it -v $(pwd):/app -u $(id -u):$(id -g) \
  composer create-project --no-dev --remove-vcs waldhacker/pseudify-profile-templates .
```

* Create and edit the .env

```shell
cp .env.example .env
```

* Test if everything works

```shell
docker run -it -v $(pwd):/data \
  ghcr.io/waldhacker/pseudify pseudify:information
```

## PHAR Archiv

!!! info "Dependencies"
    If the PHAR archive is used, the required dependencies must be installed manually.
    The following components must be installed:

    * PHP 8.1

    The following PHP extensions must be installed depending on which database types are used:

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

* Start with the profile templates

!!! info "Profile Templates"
    The [profile templates](https://github.com/waldhacker/pseudify-profile-templates) contain the basic configuration for pseudify and provide basic profiles for various applications.  
    They are the ideal basis for modelling the pseudonymisation of your application.

Download the profile templates:

```shell
docker run -it -v $(pwd):/app -u $(id -u):$(id -g) \
  composer create-project --no-dev --remove-vcs waldhacker/pseudify-profile-templates .
```

* Download the PHAR archive to the same folder where the pseudify profiles from the previous step were installed.

```shell
curl -sLo pseudify https://github.com/waldhacker/pseudify-core/releases/latest/download/pseudify.phar
chmod u+x pseudify
```

* Create and edit the .env

```shell
cp .env.example .env
```

* Test if everything works

```shell
./pseudify pseudify:information
```

!!! info "Alternative installation location"
    The pseudify PHAR archive can also be installed in another location (e.g. globally under `/usr/bin/pseudify`).
    The parameter `--data` can be used to tell pseudify the path to the pseudify profiles to be used.
    ```shell
    /usr/bin/pseudify --data /home/project/path/to/pseudify-profile-templates pseudify:information
    ```
