# Configuration

## .env

The basic configuration of pseudify takes place using values in an `.env` file.  
The [profile templates](https://github.com/waldhacker/pseudify-profile-templates) contain an [exemplary .env file](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/.env.example) which can be used as a basis for your own configuration.  

### APP_SECRET

Default: &lt;empty&gt;

Pseudify caches the input data in order to be able to generate identical pseudonyms for identical input data per pseudonymisation run.  
To prevent the input data to be pseudonymised from being stored in plain text in the cache, they are processed for security purposes using the SHA-256 hash algorithm and then stored.  
In order that no conclusions can be drawn from the SHA-256 hash values in the cache to the input data, **it is strongly recommended to set the value of `APP_SECRET` to as long a random value as possible.**  
The value of `APP_SECRET` **is to be treated as a secret**, like a password.  

#### Example

```shell
APP_SECRET=6ba571b0a3e7150a4b7e5b918e81ce8f
```

### PSEUDIFY_FAKER_LOCALE

Default: en_US

Pseudify uses the [FakerPHP/Faker component](https://fakerphp.github.io/) to generate the pseudonyms.  
The component allows [the generation of language-specific values](https://fakerphp.github.io/#language-specific-formatters).  
Supported values of `PSEUDIFY_FAKER_LOCALE` can be found in the [FakerPHP/Faker Repository](https://github.com/FakerPHP/Faker/tree/v1.20.0/src/Faker/Provider).  

#### Example

```shell
PSEUDIFY_FAKER_LOCALE=de_DE
```

### PSEUDIFY_DATABASE_DRIVER

Default: pdo_mysql  
Resolves to connection parameter: [`doctrine.dbal.connections.default.driver`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L5)

The value of `PSEUDIFY_DATABASE_DRIVER` must be [a supported driver of the Doctrine DBAL component](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#driver).  
The pseudify docker container comes with the following driver support:

* pdo_mysql (A MySQL driver that uses the pdo_mysql PDO extension
* mysqli (A MySQL driver that uses the mysqli extension
* pdo_pgsql (A PostgreSQL driver that uses the pdo_pgsql PDO extension)
* pdo_sqlite (An SQLite driver that uses the pdo_sqlite PDO extension)
* sqlite3 (An SQLite driver that uses the sqlite3 extension)
* pdo_sqlsrv (A Microsoft SQL Server driver that uses pdo_sqlsrv PDO)
* sqlsrv (A Microsoft SQL Server driver that uses the sqlsrv PHP extension)

!!! info
    Support for the `oci8` driver for Oracle databases in the docker container is in preparation (pull requests are welcome).

#### Example

```shell
PSEUDIFY_DATABASE_DRIVER=pdo_mysql
```

### PSEUDIFY_DATABASE_HOST

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.host`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L6)

The host name under which the database server can be reached.  
This value is only used when using the following drivers:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

#### Example

```shell
PSEUDIFY_DATABASE_HOST=host.docker.internal
```

### PSEUDIFY_DATABASE_PORT

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.port`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L7)

The port under which the database server can be reached.  
This value is only used when using the following drivers:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

#### Example

```shell
PSEUDIFY_DATABASE_PORT=3306
```

### PSEUDIFY_DATABASE_USER

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.user`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L8)

The user name of the database.  
This value is only used when using the following drivers:

* [pdo_sqlite](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlite)
* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

#### Example

```shell
PSEUDIFY_DATABASE_USER=pseudify
```

### PSEUDIFY_DATABASE_PASSWORD

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.password`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L9)

The password of the database.  
This value is only used when using the following drivers:

* [pdo_sqlite](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlite)
* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

#### Example

```shell
PSEUDIFY_DATABASE_PASSWORD='super(!)sEcReT'
```

### PSEUDIFY_DATABASE_SCHEMA

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.dbname`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L10) or [`doctrine.dbal.connections.default.path`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L11)

For the following drivers, `PSEUDIFY_DATABASE_SCHEMA` corresponds to the database name:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

For the following drivers, `PSEUDIFY_DATABASE_SCHEMA` corresponds to the file system path to the database:

* [pdo_sqlite](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlite)
* [sqlite3](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#sqlite3)

#### Example

```shell
PSEUDIFY_DATABASE_SCHEMA=wordpress_prod
```

### PSEUDIFY_DATABASE_CHARSET

Default: utf8mb4
Resolves to connection parameter: [`doctrine.dbal.connections.default.charset`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L12)

The character set used during the connection to the database.  
This value is only used when using the following drivers:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-oci-oci8)

#### Example

```shell
PSEUDIFY_DATABASE_CHARSET=utf8mb4
```

### PSEUDIFY_DATABASE_VERSION

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.server_version`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L13)

Doctrine comes with different database platform implementations for some vendors to support version-specific features, dialects and behaviours.  
The drivers automatically detect the platform version and instantiate the appropriate platform class.  
If you want to disable automatic database platform detection and explicitly select the platform version implementation, you can do this with the value in `PSEUDIFY_DATABASE_VERSION`.  

!!! info
    If you are using a MariaDB database, you should prefix the value `PSEUDIFY_DATABASE_VERSION` with `mariadb-` (example: mariadb-10.2).

#### Example

```shell
PSEUDIFY_DATABASE_VERSION=8.0
```

### PSEUDIFY_DATABASE_SSL_INSECURE

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.options.TrustServerCertificate`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L15)

If the value of `PSEUDIFY_DATABASE_SSL_INSECURE` is set to `1`, no check of the TLS certificate of the database server is performed.

This value is only used when using the following drivers:

* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#pdo-sqlsrv-sqlsrv)

```shell
PSEUDIFY_DATABASE_SSL_INSECURE=1
```

## Advanced connection settings

If you need to configure other driver options, you can do so in the file [`config/configuration.yaml`](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml#L4).  
Examples and information for driver options can be found in the following documents:

* [Symfony DoctrineBundle - Doctrine DBAL Configuration](https://symfony.com/doc/current/reference/configuration/doctrine.html#doctrine-dbal-configuration)
* [Doctrine DBAL- Connection Details](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#connection-details)

After changes of the connection settings, the cache must be cleared 

```shell
pseudify cache:clear
```

### Multiple connection configurations

It is possible to configure multiple connections.  
The [connection named `default`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml#L4) is used as the default connection.  
In the file [`config/configuration.yaml`](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml#L4) further connections can be configured under a different name.


```yaml
doctrine:
  dbal:
    connections:
      myCustomConnection:
        driver: sqlsrv
        # ...
```

The configured connections can be used with the `--connection` parameter.

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

## Registering custom data types

If user-defined data types are required, you can define them at connection level in the file [`config/configuration.yaml`](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml#L5-L13).  

Example implementations for user-defined data types can be found in the following files:

* [src/Types/TYPO3/EnumType.php](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Types/TYPO3/EnumType.php)
* [src/Types/TYPO3/SetType.php](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Types/TYPO3/SetType.php)

These user-defined data types can then be used by means of configuration in the file [`config/configuration.yaml`](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml)

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

Examples and information for user-defined data types can be found in the following documents:

* [Symfony DoctrineBundle - Registering custom Mapping Types](https://symfony.com/doc/current/doctrine/dbal.html#registering-custom-mapping-types)
* [Symfony DoctrineBundle - Registering custom Mapping Types in the SchemaTool](https://symfony.com/doc/current/doctrine/dbal.html#registering-custom-mapping-types-in-the-schematool)
* [Doctrine DBAL - Custom Mapping Types](https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html#custom-mapping-types)

After adding custom data types, the cache must be cleared. 

```shell
pseudify cache:clear
```

## Registering custom faker formatters

The [FakerPHP/Faker component](https://fakerphp.github.io/) comes with a lot of predefined formatters to generate various data formats.  
If you want to use custom formatters, you can look at the implementation of the [BobRossLipsumProvider](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Faker/BobRossLipsumProvider.php) example.  
The custom formatter must implement the interface [`Waldhacker\Pseudify\Core\Faker\FakeDataProviderInterface`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Faker/FakeDataProviderInterface.php) to be integrated into the system.  
The best way to see how formatters can generate data is to look at [the providers in the FakerPHP/Faker project](https://github.com/FakerPHP/Faker/tree/v1.20.0/src/Faker/Provider).  

After adding custom faker formatters, the cache must be cleared. 

```shell
pseudify cache:clear
```

## Registering custom decoders / encoders

The pseudify [`EncoderInterface`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/EncoderInterface.php) is compatible with the [`EncoderInterface` and `DecoderInterface` of the Symfony serializer component](https://symfony.com/doc/current/components/serializer.html#encoders).  
If you want to use custom decoders / encoders, you can see the implementation in the example of the [Rot13Encoder](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Encoder/Rot13Encoder.php).  
The custom decoder/encoder must implement the interface [`Waldhacker\Pseudify\Core\Processor\Encoder\EncoderInterface`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/EncoderInterface.php) to be integrated into the system.  
The best way to see how decoders/encoders can decode and encode data is to look at [the built-in decoders/encoders](https://github.com/waldhacker/pseudify-core/tree/0.0.1/src/src/Processor/Encoder).  

After adding custom decoders/encoders, the cache must be cleared. 

```shell
pseudify cache:clear
```

!!! note
    User-defined decoders / encoders should follow the `<Format>Encoder` naming convention (e.g. `HexEncoder`, `Red13Encoder` etc.).
    This ensures that debug commands like `pseudify:debug:analyse` can represent the names of the decoders / encoders well.  

## Access to host database servers from the docker container

If you want to access database servers running on the host system from the docker container, this can be done in different ways.  
Three of them are described below.

### add-host variant

Add the parameter `--add-host=host.docker.internal:host-gateway` to the `docker run` command to provide the IP address of the docker gateway on the host system under the host name `host.docker.internal` within the docker container.  
The option `PSEUDIFY_DATABASE_HOST` [in the .env file](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/.env.example#L6) must receive the value `host.docker.internal`.   

!!! note
    For this variant to work, the port of the database server on the docker gateway must be open.

#### Example

.env:

```shell
PSEUDIFY_DATABASE_HOST=host.docker.internal
```

Command:

```shell
docker run -it -v $(pwd):/data --add-host=host.docker.internal:host-gateway \
  ghcr.io/waldhacker/pseudify pseudify:debug:table_schema
```

### Host-IP variant

The `PSEUDIFY_DATABASE_HOST` option [in the .env file](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/.env.example#L6) must be set to the IP address of the host system.   

!!! note
    For this variant to work, the port of the database server must be open on this IP of the host system.

#### Example

.env:

```shell
PSEUDIFY_DATABASE_HOST=192.168.178.31
```

Command:

```shell
docker run -it -v $(pwd):/data ghcr.io/waldhacker/pseudify pseudify:debug:table_schema
```

### Sibling service variant

The database server is started in parallel to the pseudify container using docker.  
Both containers are connected to the same docker network and can thus communicate with each other.  

#### Example

[Create the shared docker network](https://docs.docker.com/engine/reference/commandline/network_create/) (if none already exists) with the name `pseudify-net`:

```shell
docker network create pseudify-net
```

Starting the database server using the example [of the MariaDB container](https://hub.docker.com/_/mariadb).  
The database server is started and included in the network `pseudify-net` (`--network pseudify-net`). The container is given the name `mariadb_10_5` (`--name mariadb_10_5`), under which the database will then be accessible to the pseudify container.  

!!! note
    For the import of the test database (`-v $(pwd)/tests/mariadb/10.5:/docker-entrypoint-initdb.d`) to work correctly, the command must be executed in the main directory of the [profile templates](https://github.com/waldhacker/pseudify-profile-templates).

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

Command:

```shell
docker run -it -v $(pwd):/data --network=pseudify-net \
  ghcr.io/waldhacker/pseudify pseudify:debug:table_schema
```

## Configuration overview

Commands exist to check the configuration of the system.

### pseudify:information

The command `pseudify pseudify:information` lists:

* available profiles to analyse the database (`Registered analyse profiles`)
* available profiles to pseudonymise the database (`Registered pseudonymize profiles`)
* registered doctrine types
* database drivers available in the system (`Available built-in database drivers`)
* information per configured connection (`Connection information for connection "<connecntion name>"`)
* information about which database data types are associated with which doctrine implementations (`Registered doctrine database data type mappings`)
* information about the doctrine driver implementations used and the system driver used (`Connection details`).

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

The command lists the combined database configuration, which consists of the [core configuration](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/config/packages/doctrine.yaml)
and the [user-defined configuration](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/config/configuration.yaml).

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

The command lists the values from the `.env` file.

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
