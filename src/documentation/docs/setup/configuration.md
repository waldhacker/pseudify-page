# Configuration

## The install package

The installation package contains everything necessary for the proper operation of pseudify.  
This includes the configuration files to connect pseudify to a database and examples of how pseudify can be functionally extended.  
The files are to be understood as start templates that you can adapt to your own requirements.

Content:

* **docker-compose.yml**: Starts pseudify with the GUI for analyzing the database and modeling pseudonymization tasks (we call it the `analyze setup`).
* **docker-compose.llm-addon.yml**: Extends the `analyze setup` with AI capabilities. Pseudify uses this locally running LLM to determine personally identifiable information (PII). The data is processed exclusively on your computer and does not leave it.
* **docker-compose.database.yml**: Contains an example of how a database server can be started via docker compose if you need it.
* **userdata/**: This folder contains everything you need to configure and extend pseudify.
* **userdata/.env.example**: An example of the basic configuration of pseudify. Pseudify mainly uses env variables for the basic configuration.
* **userdata/config/**: This folder contains files for advanced configuration.
* **userdata/src/**: This folder contains the analysis and pseudonymization profile(s) that you have created with the GUI. This folder also includes examples for custom functional extensions.
* **userdata/src/Encoder/**: This folder contains an example of a custom data encoder implementation (`Rot13Encoder`).
* **userdata/src/Faker/**: This folder contains an example of a custom data faker implementation (`BobRossLipsum`).
* **userdata/src/Processing/**: This folder contains an example of a custom condition expression implementation (`isBobRoss()`).
* **userdata/src/Profiles/**: This folder contains the pseudify profiles. It can contain the `low-level profiles (PHP)` or the YAML profiles created via the GUI.
* **userdata/src/Profiles/Yaml/**: This folder contains the YAML pseudify profiles created via the GUI.
* **userdata/src/Types/**: This folder contains an example of custom database type implementations (`Enum` and `Set`).

## Configuration options

### .env

The basic configuration of pseudify takes place using values in an `.env` file.  
The [`install package`](https://github.com/waldhacker/pseudify-ai/releases/latest/) contain an [exemplary .env file](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/.env.example) which can be used as a basis for your own configuration.  

#### PSEUDIFY_FAKER_LOCALE

Default: en_US

Pseudify uses the [FakerPHP/Faker component](https://fakerphp.github.io/) to generate the pseudonyms.  
The component allows [the generation of language-specific values](https://fakerphp.github.io/#language-specific-formatters).  
Supported values of `PSEUDIFY_FAKER_LOCALE` can be found in the [FakerPHP/Faker Repository](https://github.com/FakerPHP/Faker/tree/v1.20.0/src/Faker/Provider).  

##### Example

```shell
PSEUDIFY_FAKER_LOCALE=de_DE
```

#### PSEUDIFY_DATABASE_DRIVER

Default: pdo_mysql  
Resolves to connection parameter: [`doctrine.dbal.connections.default.driver`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L5)

The value of `PSEUDIFY_DATABASE_DRIVER` must be [a supported driver of the Doctrine DBAL component](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#driver).  
The pseudify docker container comes with the following driver support:

* pdo_mysql (A MySQL driver that uses the pdo_mysql PDO extension
* mysqli (A MySQL driver that uses the mysqli extension
* pdo_pgsql (A PostgreSQL driver that uses the pdo_pgsql PDO extension)
* pdo_sqlite (An SQLite driver that uses the pdo_sqlite PDO extension)
* sqlite3 (An SQLite driver that uses the sqlite3 extension)
* pdo_sqlsrv (A Microsoft SQL Server driver that uses pdo_sqlsrv PDO)
* sqlsrv (A Microsoft SQL Server driver that uses the sqlsrv PHP extension)

!!! info
    Support for the `oci8` driver for Oracle databases should be possible (pull requests are welcome).

##### Example

```shell
PSEUDIFY_DATABASE_DRIVER=pdo_mysql
```

#### PSEUDIFY_DATABASE_HOST

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.host`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L6)

The host name under which the database server can be reached.  
This value is only used when using the following drivers:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)

##### Example

```shell
PSEUDIFY_DATABASE_HOST=host.docker.internal
```

#### PSEUDIFY_DATABASE_PORT

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.port`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L7)

The port under which the database server can be reached.  
This value is only used when using the following drivers:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)

##### Example

```shell
PSEUDIFY_DATABASE_PORT=3306
```

#### PSEUDIFY_DATABASE_USER

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.user`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L8)

The user name of the database.  
This value is only used when using the following drivers:

* [pdo_sqlite](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlite)
* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)

##### Example

```shell
PSEUDIFY_DATABASE_USER=pseudify
```

#### PSEUDIFY_DATABASE_PASSWORD

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.password`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L9)

The password of the database.  
This value is only used when using the following drivers:

* [pdo_sqlite](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlite)
* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)

##### Example

```shell
PSEUDIFY_DATABASE_PASSWORD='super(!)sEcReT'
```

#### PSEUDIFY_DATABASE_SCHEMA

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.dbname`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L10) or [`doctrine.dbal.connections.default.path`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L11)

For the following drivers, `PSEUDIFY_DATABASE_SCHEMA` corresponds to the database name:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-oci-oci8)
* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)

For the following drivers, `PSEUDIFY_DATABASE_SCHEMA` corresponds to the file system path to the database:

* [pdo_sqlite](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlite)
* [sqlite3](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#sqlite3)

##### Example

```shell
PSEUDIFY_DATABASE_SCHEMA=wordpress_prod
```

#### PSEUDIFY_DATABASE_CHARSET

Default: utf8mb4
Resolves to connection parameter: [`doctrine.dbal.connections.default.charset`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L12)

The character set used during the connection to the database.  
This value is only used when using the following drivers:

* [pdo_mysql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-mysql)
* [mysqli](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#mysqli)
* [pdo_pgsql](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-pgsql-pgsql)
* [oci8](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-oci-oci8)

##### Example

```shell
PSEUDIFY_DATABASE_CHARSET=utf8mb4
```

#### PSEUDIFY_DATABASE_VERSION

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.server_version`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L13)

Doctrine comes with different database platform implementations for some vendors to support version-specific features, dialects and behaviours.  
The drivers automatically detect the platform version and instantiate the appropriate platform class.  
If you want to disable automatic database platform detection and explicitly select the platform version implementation, you can do this with the value in `PSEUDIFY_DATABASE_VERSION`.  

!!! info
    If you are using a MariaDB database, you should prefix the value `PSEUDIFY_DATABASE_VERSION` with `mariadb-` (example: mariadb-10.2).

##### Example

```shell
PSEUDIFY_DATABASE_VERSION=8.0
```

#### PSEUDIFY_DATABASE_SSL_INSECURE

Default: &lt;empty&gt;  
Resolves to connection parameter: [`doctrine.dbal.connections.default.options.TrustServerCertificate`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L15)

If the value of `PSEUDIFY_DATABASE_SSL_INSECURE` is set to `1`, no check of the TLS certificate of the database server is performed.

This value is only used when using the following drivers:

* [pdo_sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)
* [sqlsrv](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#pdo-sqlsrv-sqlsrv)

```shell
PSEUDIFY_DATABASE_SSL_INSECURE=1
```

### Advanced connection settings

If you need to configure other driver options, you can do so in the install package file [`userdata/config/configuration.yaml`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/config/configuration.yaml#L8).  
Examples and information for driver options can be found in the following documents:

* [Symfony DoctrineBundle - Doctrine DBAL Configuration](https://symfony.com/doc/6.4/reference/configuration/doctrine.html#doctrine-dbal-configuration)
* [Doctrine DBAL- Connection Details](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/configuration.html#connection-details)

After changes of the connection settings, the cache must be cleared 

```shell
pseudify cache:clear
```

#### Multiple connection configurations

It is possible to configure multiple connections.  
The [connection named `default`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml#L4) is used as the default connection.  
In the install package file [`userdata/config/configuration.yaml`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/config/configuration.yaml#L8) further connections can be configured under a different name.

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

The following commands accept the `--connection` parameter:

* `pseudify:analyze`
* `pseudify:autoconfiguration`
* `pseudify:debug:analyze`
* `pseudify:debug:pseudonymize`
* `pseudify:debug:table_schema`
* `pseudify:pseudonymize`

## Custom extensions

### Registering custom database types

If user-defined database types are required, you can define them at connection level in the install package file [`userdata/config/configuration.yaml`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/config/configuration.yaml#L10-L12).  

Example implementations for user-defined database types can be found in the following install package files:

* [userdata/src/Types/TYPO3/EnumType.php](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/src/Types/TYPO3/EnumType.php)
* [userdata/src/Types/TYPO3/SetType.php](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/src/Types/TYPO3/SetType.php)

These user-defined database types can then be configured in the install package file [`userdata/config/configuration.yaml`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/config/configuration.yaml)

```yaml
doctrine:
  dbal:
    types:
      enum: Waldhacker\Pseudify\Types\TYPO3\EnumType
      set: Waldhacker\Pseudify\Types\TYPO3\SetType
    connections:
      default:
        mapping_types:
          enum: enum
          set: set
```

Examples and information for user-defined data types can be found in the following documents:

* [Symfony DoctrineBundle - Registering custom Mapping Types](https://symfony.com/doc/6.4/doctrine/dbal.html#registering-custom-mapping-types)
* [Symfony DoctrineBundle - Registering custom Mapping Types in the SchemaTool](https://symfony.com/doc/6.4/doctrine/dbal.html#registering-custom-mapping-types-in-the-schematool)
* [Doctrine DBAL - Custom Mapping Types](https://www.doctrine-project.org/projects/doctrine-dbal/en/3.9/reference/types.html#custom-mapping-types)

After adding custom data types, the cache must be cleared. 

```shell
pseudify cache:clear
```

### Registering custom faker formatters

The [FakerPHP/Faker component](https://fakerphp.github.io/) comes with a lot of predefined formatters to generate various data formats.  
If you want to use custom formatters, you can look at the implementation of the [BobRossLipsumProvider](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/src/Faker/BobRossLipsumProvider.php) example.  
The custom formatter must implement the interface [`Waldhacker\Pseudify\Core\Faker\FakeDataProviderInterface`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/src/Faker/FakeDataProviderInterface.php) to be integrated into the system.  
The best way to see how formatters can generate data is to look at [the providers in the FakerPHP/Faker project](https://github.com/FakerPHP/Faker/tree/v1.20.0/src/Faker/Provider).  

After adding custom faker formatters, the cache must be cleared.  

```shell
pseudify cache:clear
```

### Registering custom decoders / encoders

If you want to use custom decoders / encoders, you can see an implementation in the example of the [Rot13Encoder](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/src/Encoder/Rot13Encoder.php).  
The custom decoder / encoder must implement the interface [`Waldhacker\Pseudify\Core\Processor\Encoder\EncoderInterface`](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/src/Processor/Encoder/EncoderInterface.php) to be integrated into the system.  
The best way to see how decoders / encoders can decode and encode data is to look at [the built-in decoders/encoders](https://github.com/waldhacker/pseudify-ai/tree/2.0.0/src/src/Processor/Encoder) like the [Base64Encoder](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/src/Processor/Encoder/Base64Encoder.php).  
If you want your decoder / encoder to be configurable via the GUI, your encoder / decoder must provide a form type that defines the configuration form.  
Look at the [Base64Encoder](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/src/Processor/Encoder/Base64Encoder.php#L101) which provides the [Base64EncoderType](http://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/src/Gui/Form/ProfileDefinition/Column/Encoder/Base64EncoderType.php#L40) to get an idea how to provide a configuration form.  

Additional information for user-defined form types can be found in the following document:

* [Symfony Forms - How to Create a Custom Form Field Type](https://symfony.com/doc/6.4/form/create_custom_field_type.html#defining-the-form-type)

After adding custom decoders/encoders, the cache must be cleared. 

```shell
pseudify cache:clear
```

!!! note
    User-defined decoders / encoders should follow the `<Format>Encoder` naming convention (e.g. `HexEncoder`, `Rot13Encoder` etc.).
    This ensures that debug commands like `pseudify:debug:analyse` can represent the names of the decoders / encoders well.  

### Register custom condition expressions

If you want to use custom condition expressions, you can see an implementation in the example of the [ConditionExpressionProvider](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/src/Processing/ConditionExpressionProvider.php#L34-L41).  
You need to define an description and an [`Symfony\Component\ExpressionLanguage\ExpressionFunction`](https://symfony.com/doc/6.4/components/expression_language.html#registering-functions) implementation which implements the `evaluator`.

Additional information for user-defined expressions can be found in the following document:

* [Symfony ExpressionLanguage - Extending the ExpressionLanguage](https://symfony.com/doc/6.4/components/expression_language.html#extending-the-expressionlanguage)

## Manage database access

Database access can be managed in various ways.  
Some variants are presented below.  

### Access a database running on your host system

#### Pseudify is running as a standalone binary (`pseudonymization setup`)

Add the parameter `--add-host=host.docker.internal:host-gateway` to the `docker run` command.  
The option `PSEUDIFY_DATABASE_HOST` in the install package file [userdata/.env](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/.env) must be set to `host.docker.internal`.   

!!! note
    For this variant to work, the port of the database server on the docker gateway (host system) must be open.

Then run pseudify like:

```shell
$ docker run --rm -it --add-host=host.docker.internal:host-gateway -v "$(pwd)/userdata/":/opt/pseudify/userdata/ \
    ghcr.io/waldhacker/pseudify-ai:2.0 pseudify:debug:table_schema
```

#### pseudify is running using docker compose (`analyze setup`)

Add the OPTION `extra_hosts: ['host.docker.internal:host-gateway']` in the install package file [docker-compose.yml](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/docker-compose.yml) like:

```yaml
services:
  pseudify:
    # ...
    extra_hosts:
      - 'host.docker.internal:host-gateway'
```

The option `PSEUDIFY_DATABASE_HOST` in the install package file [userdata/.env](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/.env) must be set to `host.docker.internal`.   

Then start pseudify like:

```shell
$ docker compose up -d
```

### Access a database using docker services

#### pseudify is running as a standalone binary (`pseudonymization setup`)

[Create a docker network](https://docs.docker.com/engine/reference/commandline/network_create/) with the name `pseudify-net` (if none already exists):

```shell
$ docker network create pseudify-net
```

Start a database server using the network `pseudify-net` (`--network pseudify-net`).  
The database container is given the name `mariadb_10_5` (`--name mariadb_10_5`).  
Therefore the option `PSEUDIFY_DATABASE_HOST` in the install package file [userdata/.env](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/.env) must be set to `mariadb_10_5`.   

!!! note
    For the import of the test database (`-v "$(pwd)"/database-data:/docker-entrypoint-initdb.d`) to work correctly, the command must be executed in the main directory of the install package.

```shell
$ docker run --rm --detach \
    --network pseudify-net \
    --name mariadb_10_5 \
    --env MARIADB_USER=pseudify \
    --env MARIADB_PASSWORD='P53ud1fy(!)w4ldh4ck3r' \
    --env MARIADB_ROOT_PASSWORD='P53ud1fy(!)w4ldh4ck3r' \
    --env MARIADB_DATABASE=pseudify_utf8mb4 \
    -v "$(pwd)"/database-data:/docker-entrypoint-initdb.d \
    mariadb:10.5
```

Then run pseudify like:

```shell
$ docker run --rm -it --add-host=host.docker.internal:host-gateway -v "$(pwd)/userdata/":/opt/pseudify/userdata/ \
    ghcr.io/waldhacker/pseudify-ai:2.0 pseudify:debug:table_schema
```

#### pseudify is running using docker compose (`analyze setup`)

You can use the install package file [docker-compose.database.yml](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/docker-compose.database.yml) and adapt it to your needs.  
The option `PSEUDIFY_DATABASE_HOST` in the install package file [userdata/.env](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/.env) must be set to database [service name](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/docker-compose.database.yml#L7) like `mariadb_10_5`.   

Then start pseudify like:

```shell
$ docker compose -f docker-compose.yml -f docker-compose.database.yml up -d
```

## Debug the configuration

Commands exist to check the configuration of the system.

### pseudify:information

The command `pseudify pseudify:information` lists:

* available profiles to analyse the database (`Registered analyse profiles`)
* available profiles to pseudonymise the database (`Registered pseudonymize profiles`)
* registered database types
* registered condition expressions
* registered encoders / decoders
* database drivers available in the system (`Available built-in database drivers`)
* information per configured connection (`Connection information for connection "<connecntion name>"`)
* information about which database types are associated with which doctrine implementations (`Registered doctrine database data type mappings`)
* information about the doctrine driver implementations used and the system driver used (`Connection details`).

### debug:config DoctrineBundle

The command lists the combined database configuration, which consists of the [core configuration](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/src/config/packages/doctrine.yaml)
and the [user-defined configuration from the install package](https://github.com/waldhacker/pseudify-ai/blob/2.0.0/userdata/config/configuration.yaml).

### debug:dotenv

The command lists the values from the `.env` file.
