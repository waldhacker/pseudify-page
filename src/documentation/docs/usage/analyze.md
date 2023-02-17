# Analyse

## Model the analysis

!!! info
    All the modelling described in this tutorial can be viewed with comments [in the Test folder of the Profile Templates (TestAnalyzeProfile.php)](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Profiles/Tests/TestAnalyzeProfile.php).

### Get an overview

To get an overview of the data in the database, you can use the command `pseudify:debug:table_schema`.  
Of course, you can also use any other tool of your choice.  

```shell
$ pseudify pseudify:debug:table_schema

wh_log
------

 -------------------- --------- --------------------------------------------------------------------------------------------------------- 
  column               type      data example                                                                                             
 -------------------- --------- --------------------------------------------------------------------------------------------------------- 
  id                   integer   6                                                                                                        
  log_type             string    foo                                                                                                      
  log_data             blob      613a323a7b693a303b733a33383a223466623a313434373a646566623a396434373a613265303a613336613a313064333a66...  
  log_message          text      {"message":"foo text \"ronaldo15\", another \"mcclure.ofelia@example.com\""}                             
  ip                   string    4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98                                                                   
 -------------------- --------- --------------------------------------------------------------------------------------------------------- 

wh_meta_data
------------

 --------------------- --------- --------------------------------------------------------------------------------------------------------- 
  column                type      data example                                                                                             
 --------------------- --------- --------------------------------------------------------------------------------------------------------- 
  id                    integer   5                                                                                                        
  meta_data             blob      1f8b08000000000000036592dd6ea33010855f65657159116ca0818922f52fca6ea5d52a4bab46bd89066c821b302c769246...  
 --------------------- --------- --------------------------------------------------------------------------------------------------------- 

wh_user
-------

 -------------------- --------- ---------------------------------------------------------------------------------------------- 
  column               type      data example                                                                                  
 -------------------- --------- ---------------------------------------------------------------------------------------------- 
  id                   integer   5                                                                                             
  username             string    howell.damien                                                                                 
  password             string    $argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs  
  first_name           string    Mckayla                                                                                       
  last_name            string    Stoltenberg                                                                                   
  email                string    cassin.bernadette@example.net                                                                 
  city                 string    South Wilfordland                                                                             
 -------------------- --------- ---------------------------------------------------------------------------------------------- 

wh_user_session
---------------

 ------------------- --------- -------------------------------------------------------------------- 
  column              type      data example                                                        
 ------------------- --------- -------------------------------------------------------------------- 
  id                  integer   5                                                                   
  session_data        blob      a:1:{s:7:"last_ip";s:38:"4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98";}  
  session_data_json   text      {"data":{"last_ip":"4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98"}}
 ------------------- --------- --------------------------------------------------------------------
```

The command outputs all tables of the database one after the other and lists their columns.  
The column `column` contains the name of the database column.  
The column `type` contains the [human-readable name of the data type](https://github.com/doctrine/dbal/blob/3.5.x/src/Types/Types.php#L13-L41) of the database column.  
The column `data example` contains the longest data record that can be found in the database in this column. After 100 characters, the data will be truncated.  

Search for personal data that you want to pseudonymise.  
Search for names, user names, passwords, addresses, email addresses, IP addresses, telephone numbers, ID numbers such as insurance numbers, profile data such as height or weight, etc.  

!!! info
    If you need suggestions, read the chapter ["What to pseudonymise?"](../whatToPseudonymize.md).

It is best to note the columns with directly visible personal data, i.e. the columns that contain data in plain text and not those with more complex data structures such as JSON (e.g. the column `wh_log.log_message`) or those in which the data is available in encoded form (e.g. the column `wh_log.log_data`).  
In the example, the preferred columns would be:

* `wh_log.ip`
* `wh_user.username`
* `wh_user.password`
* `wh_user.first_name`
* `wh_user.last_name`
* `wh_user.email`
* `wh_user.city`

### Model an "Analyze Profile"

#### Create a "Profile"

In the folder [src/Profiles](https://github.com/waldhacker/pseudify-profile-templates/tree/0.0.1/src/Profiles) create a PHP file with any name.  
In the example, the file is called `TestAnalyzeProfile.php`.  
The file will have the following content:

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        return $tableDefinition;
    }
}
```

The `getIdentifier()` method must return a unique identifier of your profile and should only consist of letters, numbers or the characters `-` and `_` and must not contain any spaces.  

After creating the profile, the cache must be cleared. 

```shell
$ pseudify cache:clear
```

The command `pseudify pseudify:debug:analyse test-profile` already gives you information about your profile.

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 ------- -------- --------------- ----------------- 
  Table   column   data decoders   data collectors  
 ------- -------- --------------- ----------------- 

Search data in this tables
--------------------------

 ----------------- -------------------------- --------------- ----------------------- 
  Table             column                     data decoders   special data decoders  
 ----------------- -------------------------- --------------- ----------------------- 
  wh_log            id (integer)               Scalar          no further processing  
  wh_log            log_type (string)          Scalar          no further processing  
  wh_log            log_data (blob)            Scalar          no further processing  
  wh_log            log_message (text)         Scalar          no further processing  
  wh_log            ip (string)                Scalar          no further processing  
  wh_meta_data      id (integer)               Scalar          no further processing  
  wh_meta_data      meta_data (blob)           Scalar          no further processing  
  wh_user           id (integer)               Scalar          no further processing  
  wh_user           username (string)          Scalar          no further processing  
  wh_user           password (string)          Scalar          no further processing  
  wh_user           first_name (string)        Scalar          no further processing  
  wh_user           last_name (string)         Scalar          no further processing  
  wh_user           email (string)             Scalar          no further processing  
  wh_user           city (string)              Scalar          no further processing  
  wh_user_session   id (integer)               Scalar          no further processing  
  wh_user_session   session_data (blob)        Scalar          no further processing  
  wh_user_session   session_data_json (text)   Scalar          no further processing  
 ----------------- -------------------------- --------------- ----------------------- 
```

#### Define source data

!!! info
    The "Analyze Profile" is used to determine in which "unlit corners" of the database other personal data are hiding.  
    We therefore use the personal data already known to us, which we identified in the first step, to find them in the rest of the database.  

We have identified personal data in the following columns:

* `wh_log.ip`
* `wh_user.username`
* `wh_user.password`
* `wh_user.first_name`
* `wh_user.last_name`
* `wh_user.email`
* `wh_user.city`

You must now tell pseudify that you want to use the data in these columns as source data.  
To do this, you extend the `getTableDefinition()` method in the profile.

```php
    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addSourceTable(table: 'wh_log', columns: [
                'ip',
            ])
            ->addSourceTable(table: 'wh_user', columns: [
                'username',
                'password',
                'first_name',
                'last_name',
                'email',
                'city',
            ])
        ;

        return $tableDefinition;
    }
``` 

With the method [`addSourceTable()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TableDefinition.php#L103) you tell pseudify in which database table and in which database columns the source data should be collected.  
Pseudify will then automatically search for occurrences of the source data in all other database columns of the database tables and output them.  
Previously, the output of the command `pseudify:debug:analyse test-profile` contained all database tables and all database columns under `Search data in this tables`.  
Now only the database tables and their database columns that were not defined as source data using `addSourceTable()` are listed there.  

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 --------- --------------------- --------------- ----------------------- 
  Table     column                data decoders   data collectors        
 --------- --------------------- --------------- ----------------------- 
  wh_log    ip (string)           Scalar          default (scalar data)  
  wh_user   username (string)     Scalar          default (scalar data)  
  wh_user   password (string)     Scalar          default (scalar data)  
  wh_user   first_name (string)   Scalar          default (scalar data)  
  wh_user   last_name (string)    Scalar          default (scalar data)  
  wh_user   email (string)        Scalar          default (scalar data)  
  wh_user   city (string)         Scalar          default (scalar data)  
 --------- --------------------- --------------- ----------------------- 

Search data in this tables
--------------------------

 ----------------- -------------------------- --------------- ----------------------- 
  Table             column                     data decoders   special data decoders  
 ----------------- -------------------------- --------------- ----------------------- 
  wh_log            id (integer)               Scalar          no further processing  
  wh_log            log_type (string)          Scalar          no further processing  
  wh_log            log_data (blob)            Scalar          no further processing  
  wh_log            log_message (text)         Scalar          no further processing  
  wh_meta_data      id (integer)               Scalar          no further processing  
  wh_meta_data      meta_data (blob)           Scalar          no further processing  
  wh_user           id (integer)               Scalar          no further processing  
  wh_user_session   id (integer)               Scalar          no further processing  
  wh_user_session   session_data (blob)        Scalar          no further processing  
  wh_user_session   session_data_json (text)   Scalar          no further processing  
 ----------------- -------------------------- --------------- -----------------------
```

##### Encoded data as source data

It happens that data in database columns are in encoded form.  
This means that the encoded plaintext must be decoded during the analysis in order to be able to use it as source data.  
Similar to what is described under ["Search encoded data"](#search-encoded-data), the database columns of the source data can also be decoded.  

The method [`SourceColumn::create()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/SourceColumn.php#L108) can be given [a name of a built-in decoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/SourceColumn.php#L38-L50) with the parameter `dataType`.  

!!! note
    As described in ["Search multiple encoded data"](#search-multiple-encoded-data), the [`ChainedEncoder`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/ChainedEncoder.php) can also be used here to decode multiple-encoded data.  

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\SourceColumn;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addSourceTable(table: 'wh_test_table', columns: [
                SourceColumn::create(identifier: 'wh_test_column', dataType: SourceColumn::DATA_TYPE_HEX),
            ])
        ;

        return $tableDefinition;
    }
}
```

You will now see under `Collect search data from these tables` that the name `Hex` is listed under `data decoders` in the database column `session_data_json`.  
This signals to you that the data will be decoded using the HexEncoder.  

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 ----------------- -------------------------- --------------- ---------------------- 
  Table             column                     data decoders   data collectors     
 ----------------- -------------------------- --------------- ----------------------
  wh_test_table     wh_test_column (text)      Hex             default (scalar data) 
 ----------------- -------------------------- --------------- ---------------------- 

Search data in this tables
--------------------------

 ----------------- --------------------- --------------- ----------------------- 
  Table             column                data decoders   special data decoders  
 ----------------- --------------------- --------------- ----------------------- 
  wh_log            id (integer)          Scalar          no further processing  
  wh_log            log_type (string)     Scalar          no further processing  
  wh_log            log_data (blob)       Scalar          no further processing  
  wh_log            log_message (text)    Scalar          no further processing  
  wh_log            ip (string)           Scalar          no further processing  
  wh_meta_data      id (integer)          Scalar          no further processing  
  wh_meta_data      meta_data (blob)      Scalar          no further processing  
  wh_user           id (integer)          Scalar          no further processing  
  wh_user           username (string)     Scalar          no further processing  
  wh_user           password (string)     Scalar          no further processing  
  wh_user           first_name (string)   Scalar          no further processing  
  wh_user           last_name (string)    Scalar          no further processing  
  wh_user           email (string)        Scalar          no further processing  
  wh_user           city (string)         Scalar          no further processing  
  wh_user_session   id (integer)          Scalar          no further processing  
  wh_user_session   session_data (blob)   Scalar          no further processing  
 ----------------- --------------------- --------------- -----------------------
```

Alternatively, `->setEncoder(encoder: new HexEncoder())` can be used:

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\SourceColumn;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addSourceTable(table: 'wh_test_table', columns: [
                SourceColumn::create(identifier: 'wh_test_column')->setEncoder(encoder: new HexEncoder()),
            ])
        ;

        return $tableDefinition;
    }
}
```

#### Optimise search

Without further definition, pseudify will search for the source data in all database tables and their columns that have not been defined as source data using `addSourceTable()` or [`addColumn()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/SourceTable.php#L89).  
The search can be optimised so that the analysis does not take an unnecessarily long time.  
The aim is usually to search only "text" (strings).

##### Exclude data types

You can exclude columns with certain data types from the search to shorten the search time.  
For example, in most cases it does not make sense to search database columns of the type `integer'.  
Data types can be excluded for certain tables or for all tables.
As soon as data types are excluded at the table level, the globally excluded data types are not additionally excluded for this table.  

!!! info
    You can find the names of the data types [in the source code of the Doctrine project](https://github.com/doctrine/dbal/blob/3.5.x/src/Types/Types.php#L13-L41), e.g. `string`, `integer`, `datetime` etc: `string`, `integer`, `datetime` etc.

!!! info
    There is the constant [`TableDefinition::COMMON_EXCLUED_TARGET_COLUMN_TYPES`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TableDefinition.php#L24-L39), which contains all data types that do not normally have to be scanned.

###### Exclude data types at table level

To exclude all columns with the data type `integer` in the table `wh_meta_data` from the search, you must extend the method `getTableDefinition()` in the profile:

```php
    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            // ...
            ->addTargetTable(table: 'wh_meta_data', excludeColumnTypes: [
                'integer'
            ])
        ;

        return $tableDefinition;
    }
``` 

The method [`addTargetTable()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TableDefinition.php#L169) tells the automatic table configuration that you want to configure the table `wh_meta_data` specifically.  
In the parameter `excludeColumnTypes` you can pass an array of data types to be excluded from the search.  

###### Exclude data types globally

To globally exclude all columns with the data type `integer` from the search in all tables, you must extend the method `getTableDefinition()` in the profile:

```php
    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addSourceTable(table: 'wh_log', columns: [
                'ip',
            ])
            ->addSourceTable(table: 'wh_user', columns: [
                'username',
                'password',
                'first_name',
                'last_name',
                'email',
                'city',
            ])

            ->excludeTargetColumnTypes(columnTypes: [
                'integer'
            ])
        ;

        return $tableDefinition;
    }
```

The method [`excludeTargetColumnTypes()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TableDefinition.php#L298) tells the automatic table configuration,
that in all tables (which have no special exclusions defined) all columns of the data type `integer` are to be excluded from the search.

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 --------- --------------------- --------------- ----------------------- 
  Table     column                data decoders   data collectors        
 --------- --------------------- --------------- ----------------------- 
  wh_log    ip (string)           Scalar          default (scalar data)  
  wh_user   username (string)     Scalar          default (scalar data)  
  wh_user   password (string)     Scalar          default (scalar data)  
  wh_user   first_name (string)   Scalar          default (scalar data)  
  wh_user   last_name (string)    Scalar          default (scalar data)  
  wh_user   email (string)        Scalar          default (scalar data)  
  wh_user   city (string)         Scalar          default (scalar data)  
 --------- --------------------- --------------- ----------------------- 

Search data in this tables
--------------------------

 ----------------- -------------------------- --------------- ----------------------- 
  Table             column                     data decoders   special data decoders  
 ----------------- -------------------------- --------------- ----------------------- 
  wh_log            log_type (string)          Scalar          no further processing  
  wh_log            log_data (blob)            Scalar          no further processing  
  wh_log            log_message (text)         Scalar          no further processing  
  wh_meta_data      meta_data (blob)           Scalar          no further processing  
  wh_user_session   session_data (blob)        Scalar          no further processing  
  wh_user_session   session_data_json (text)   Scalar          no further processing  
 ----------------- -------------------------- --------------- -----------------------
```

You will now see under `Search data in this tables` that all `integer` columns have disappeared.  

As a rule, it is a good idea to integrate the following line in the profile in order to globally [exclude all data types for which it does not make sense to search](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TableDefinition.php#L24-L39):

```php  
->excludeTargetColumnTypes(columnTypes: TableDefinition::COMMON_EXCLUED_TARGET_COLUMN_TYPES)
```

##### Exclude database columns

The automatic table configuration will always exclude database columns from the search first on the basis of the data type.  
In addition, you can define in the profile at table level that database columns are to be excluded from the search based on their name.  
To do this, you must extend the `getTableDefinition()` method in the profile:

```php
    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addSourceTable(table: 'wh_log', columns: [
                'ip',
            ])
            ->addSourceTable(table: 'wh_user', columns: [
                'username',
                'password',
                'first_name',
                'last_name',
                'email',
                'city',
            ])

            ->excludeTargetColumnTypes(columnTypes: TableDefinition::COMMON_EXCLUED_TARGET_COLUMN_TYPES)

            ->addTargetTable(table: 'wh_log', excludeColumns: [
                'log_message',
            ])
        ;

        return $tableDefinition;
    }
```

The method [`addTargetTable()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TableDefinition.php#L169) tells the automatic table configuration that you want to configure the table `wh_log` specifically.  
In the parameter `excludeColumns` you can pass an array of column names which should be excluded from the search.

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 --------- --------------------- --------------- ----------------------- 
  Table     column                data decoders   data collectors        
 --------- --------------------- --------------- ----------------------- 
  wh_log    ip (string)           Scalar          default (scalar data)  
  wh_user   username (string)     Scalar          default (scalar data)  
  wh_user   password (string)     Scalar          default (scalar data)  
  wh_user   first_name (string)   Scalar          default (scalar data)  
  wh_user   last_name (string)    Scalar          default (scalar data)  
  wh_user   email (string)        Scalar          default (scalar data)  
  wh_user   city (string)         Scalar          default (scalar data)  
 --------- --------------------- --------------- ----------------------- 

Search data in this tables
--------------------------

 ----------------- -------------------------- --------------- ----------------------- 
  Table             column                     data decoders   special data decoders  
 ----------------- -------------------------- --------------- ----------------------- 
  wh_log            log_type (string)          Scalar          no further processing  
  wh_log            log_data (blob)            Scalar          no further processing  
  wh_meta_data      meta_data (blob)           Scalar          no further processing  
  wh_user_session   session_data (blob)        Scalar          no further processing  
  wh_user_session   session_data_json (text)   Scalar          no further processing  
 ----------------- -------------------------- --------------- -----------------------
```

You now see under `Search data in these tables` that the column `log_message` of the table `wh_log` has disappeared.  

##### Exclude tables

You can exclude whole tables from the search to shorten the search time.  
This can be done with the method [`excludeTargetTables()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TableDefinition.php#L235).  

```php
    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addSourceTable(table: 'wh_log', columns: [
                'ip',
            ])

            ->excludeTargetColumnTypes(columnTypes: TableDefinition::COMMON_EXCLUED_TARGET_COLUMN_TYPES)

            ->excludeTargetTables(tables: [
                'wh_user',
            ])
        ;

        return $tableDefinition;
    }
```

As you can see, the table `wh_user` is no longer listed under `Search data in these tables`.

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 -------- ------------- --------------- ----------------------- 
  Table    column        data decoders   data collectors        
 -------- ------------- --------------- ----------------------- 
  wh_log   ip (string)   Scalar          default (scalar data)  
 -------- ------------- --------------- ----------------------- 

Search data in this tables
--------------------------

 ----------------- ---------------------------- --------------- ----------------------- 
  Table             column                       data decoders   special data decoders  
 ----------------- ---------------------------- --------------- ----------------------- 
  wh_log            log_type (string)            Scalar          no further processing  
  wh_log            log_data (blob)              Scalar          no further processing  
  wh_log            log_data_plaintext (blob)    Scalar          no further processing  
  wh_log            log_message (text)           Scalar          no further processing  
  wh_meta_data      meta_data (blob)             Scalar          no further processing  
  wh_meta_data      meta_data_plaintext (blob)   Scalar          no further processing  
  wh_user_session   session_data (blob)          Scalar          no further processing  
 ----------------- ---------------------------- --------------- -----------------------
```

Regular expressions can be used in the table names to be excluded, e.g.: `wh_user.*`.  
This makes it possible, for example, to exclude several tables with one expression:

```php
    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addSourceTable(table: 'wh_log', columns: [
                'ip',
            ])

            ->excludeTargetColumnTypes(columnTypes: TableDefinition::COMMON_EXCLUED_TARGET_COLUMN_TYPES)

            ->excludeTargetTables(tables: [
                'wh_user.*',
            ])
        ;

        return $tableDefinition;
    }
```

As you can see, the tables `wh_user` and the table `wh_user_session` are no longer listed under `Search data in these tables`.

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 -------- ------------- --------------- ----------------------- 
  Table    column        data decoders   data collectors        
 -------- ------------- --------------- ----------------------- 
  wh_log   ip (string)   Scalar          default (scalar data)  
 -------- ------------- --------------- ----------------------- 

Search data in this tables
--------------------------

 -------------- ---------------------------- --------------- ----------------------- 
  Table          column                       data decoders   special data decoders  
 -------------- ---------------------------- --------------- ----------------------- 
  wh_log         log_type (string)            Scalar          no further processing  
  wh_log         log_data (blob)              Scalar          no further processing  
  wh_log         log_data_plaintext (blob)    Scalar          no further processing  
  wh_log         log_message (text)           Scalar          no further processing  
  wh_meta_data   meta_data (blob)             Scalar          no further processing  
  wh_meta_data   meta_data_plaintext (blob)   Scalar          no further processing  
 -------------- ---------------------------- --------------- ----------------------- 
```

#### Search encoded data

It happens that data in database columns are in encoded form.  
This means that the encoded plaintext must be decoded during the analysis.  
In our example, the database column `log_data` of the table `wh_log` and the database column `meta_data` of the table `wh_meta_data` contain encoded data.  
You have to find out how this data is encoded by looking at the source code or the documentation of the application that uses the database.  

In our example, the data in the database column `log_data` (with `log_type` = `bar`) is encoded as follows.

Database data:

```shell
613a323a7b693a303b733a31353a223133322e3138382e3234312e313535223b733a343a2275736572223b4f3a383a22737464436c617373223a353a7b733a383a22757365724e616d65223b733a373a22637972696c3036223b733a383a226c6173744e616d65223b733a383a22486f6d656e69636b223b733a353a22656d61696c223b733a32313a22636c696e746f6e3434406578616d706c652e6e6574223b733a323a226964223b693a39313b733a343a2275736572223b523a333b7d7d
```

Encoding through the application:

```php
$plaintext = 'a:2:{i:0;s:15:"132.188.241.155";s:4:"user";O:8:"stdClass":5:{s:8:"userName";s:7:"cyril06";s:8:"lastName";s:8:"Homenick";s:5:"email";s:21:"clinton44@example.net";s:2:"id";i:91;s:4:"user";R:3;}}';
$logData = bin2hex($plaintext);
```

In order for pseudify to search the data (`$plaintext`), the data must first be converted from hexadecimal representation to binary format.  
For this purpose, the data type (parameter `dataType`) can be passed to the definition of a database column (`TargetColumn::create()`).  

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetColumn;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetTable;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            // ...
            ->addTargetTable(table: TargetTable::create(identifier: 'wh_log',
                columns: [
                    TargetColumn::create(identifier: 'log_data', dataType: TargetColumn::DATA_TYPE_HEX),
                ]
            ))
        ;

        return $tableDefinition;
    }
}
```

The method `TargetColumn::create()` can be passed with the parameter `dataType` [a name of a built-in decoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TargetColumn.php#L31-L37).  
This is equivalent to: `->setEncoder(encoder: new HexEncoder())`.

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Encoder\HexEncoder;
use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetColumn;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetTable;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            // ...
            ->addTargetTable(table: TargetTable::create(identifier: 'wh_log',
                columns: [
                    TargetColumn::create(identifier: 'log_data')->setEncoder(encoder: new HexEncoder()),
                ]
            ))
        ;

        return $tableDefinition;
    }
}
```

When searching the database column `log_data`, pseudify will then always process the database column data using [the `decode()` method of the HexEncoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/HexEncoder.php#L38) and then search the result.  

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 --------- --------------------- --------------- ----------------------- 
  Table     column                data decoders   data collectors        
 --------- --------------------- --------------- ----------------------- 
  wh_log    ip (string)           Scalar          default (scalar data)  
  wh_user   username (string)     Scalar          default (scalar data)  
  wh_user   password (string)     Scalar          default (scalar data)  
  wh_user   first_name (string)   Scalar          default (scalar data)  
  wh_user   last_name (string)    Scalar          default (scalar data)  
  wh_user   email (string)        Scalar          default (scalar data)  
  wh_user   city (string)         Scalar          default (scalar data)  
 --------- --------------------- --------------- ----------------------- 

Search data in this tables
--------------------------

 ----------------- -------------------------- --------------- ----------------------- 
  Table             column                     data decoders   special data decoders  
 ----------------- -------------------------- --------------- ----------------------- 
  wh_log            log_data (blob)            Hex             no further processing  
  wh_log            log_type (string)          Scalar          no further processing  
  wh_log            log_message (text)         Scalar          no further processing  
  wh_meta_data      meta_data (blob)           Scalar          no further processing  
  wh_user_session   session_data (blob)        Scalar          no further processing  
  wh_user_session   session_data_json (text)   Scalar          no further processing  
 ----------------- -------------------------- --------------- -----------------------
```

You will now see under `Search data in this tables` that the name `Hex` is listed under `data decoders` in the database column `log_data`.  
This signals to you that the data is being decoded using the HexEncoder.  

##### Search multiple encoded data

It happens that data in database columns are stored in multiple encoded form.  
In our example, the data of the database column `meta_data` are encoded like this:

```php
$plaintext = 'a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"139.81.0.139";}}';
$meta_data = bin2hex(gzencode($plaintext, 5, ZLIB_ENCODING_GZIP));
```

In order for pseudify to search the data (`$plaintext`), the data must first be converted from hexadecimal representation to binary format and then the binary data must still be decompressed from ZLIB format.  
To perform multiple decoding, the [`ChainedEncoder`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/ChainedEncoder.php) can be used.  
With the ChainedEncoder, several decoders can be configured, which then decode the data in sequence.  

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Encoder\ChainedEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\GzEncodeEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\HexEncoder;
use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetColumn;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetTable;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            // ...
            ->addTargetTable(table: TargetTable::create(identifier: 'wh_meta_data',
                columns: [
                    TargetColumn::create(identifier: 'meta_data')->setEncoder(encoder: new ChainedEncoder(encoders: [
                        new HexEncoder(),
                        new GzEncodeEncoder(defaultContext: [
                            GzEncodeEncoder::ENCODE_LEVEL => 5,
                            GzEncodeEncoder::ENCODE_ENCODING => ZLIB_ENCODING_GZIP,
                        ]),
                    ])),
                ]
            ))
        ;

        return $tableDefinition;
    }
}
```

When searching the database column `meta_data` of the table `wh_meta_data`, pseudify will then first process the data of the database column using [the method `decode()` of the HexEncoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/HexEncoder.php#L38)
and then by [the `decode()` method of the GzEncodeEncoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/GzEncodeEncoder.php#L46) and then search the result.  

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 --------- --------------------- --------------- ----------------------- 
  Table     column                data decoders   data collectors        
 --------- --------------------- --------------- ----------------------- 
  wh_log    ip (string)           Scalar          default (scalar data)  
  wh_user   username (string)     Scalar          default (scalar data)  
  wh_user   password (string)     Scalar          default (scalar data)  
  wh_user   first_name (string)   Scalar          default (scalar data)  
  wh_user   last_name (string)    Scalar          default (scalar data)  
  wh_user   email (string)        Scalar          default (scalar data)  
  wh_user   city (string)         Scalar          default (scalar data)  
 --------- --------------------- --------------- ----------------------- 

Search data in this tables
--------------------------

 ----------------- -------------------------- --------------- ----------------------- 
  Table             column                     data decoders   special data decoders  
 ----------------- -------------------------- --------------- ----------------------- 
  wh_meta_data      meta_data (blob)           Hex>GzEncode    no further processing  
  wh_log            log_type (string)          Scalar          no further processing  
  wh_log            log_data (blob)            Scalar          no further processing  
  wh_log            log_message (text)         Scalar          no further processing  
  wh_user_session   session_data (blob)        Scalar          no further processing  
  wh_user_session   session_data_json (text)   Scalar          no further processing  
 ----------------- -------------------------- --------------- -----------------------
```

You will now see under `Search data in this tables` that under `data decoders` of the database column `wh_meta_data` the names `Hex>GzEncode` are listed.  
This signals to you that the data will first be decoded using the HexEncoder and then using the GzEncodeEncoder.  

##### Search differently encoded data

It happens that data in database columns are stored in differently encoded form.  
Based on conditions, applications store the data in different forms.  

In our example, the data of the database column `log_data` are encoded as follows if the database column `log_type` contains the value `bar`.

Database data:

```shell
613a323a7b693a303b733a31353a223133322e3138382e3234312e313535223b733a343a2275736572223b4f3a383a22737464436c617373223a353a7b733a383a22757365724e616d65223b733a373a22637972696c3036223b733a383a226c6173744e616d65223b733a383a22486f6d656e69636b223b733a353a22656d61696c223b733a32313a22636c696e746f6e3434406578616d706c652e6e6574223b733a323a226964223b693a39313b733a343a2275736572223b523a333b7d7d
```

Encoding through the application:

```php
$plaintext = 'a:2:{i:0;s:15:"132.188.241.155";s:4:"user";O:8:"stdClass":5:{s:8:"userName";s:7:"cyril06";s:8:"lastName";s:8:"Homenick";s:5:"email";s:21:"clinton44@example.net";s:2:"id";i:91;s:4:"user";R:3;}}';
$logData = bin2hex($plaintext);
```

In order for pseudify to search the data (`$plaintext`), the data must first be converted from hexadecimal representation to binary format.  

The data of the database column `log_data` are encoded as follows if the database column `log_type` contains the value `foo`.

Database data:

```shell
65794a3163325679546d46745a534936496e4a76626d46735a4738784e534973496d567459576c73496a6f6962574e6a624856795a5335765a6d5673615746415a586868625842735a53356a623230694c434a7359584e30546d46745a534936496b746c5a577870626d63694c434a7063434936496a457a4d6a45364e54646d597a6f304e6a42694f6d51305a4441365a44677a5a6a706a4d6a41774f6a52694f6d5978597a676966513d3d
```

Encoding through the application:

```php
$plaintext = '{"userName":"ronaldo15","email":"mcclure.ofelia@example.com","lastName":"Keeling","ip":"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8"}';
$logData = bin2hex(base64_encode($logDataPlaintext));
```

In order for pseudify to search the data (`$plaintext`), the data must first be converted from hexadecimal representation to binary format and then decoded in Base64 format.  

In both cases (`log_type` == `foo` and `log_type` == `bar`) the data can first be converted from hexadecimal representation to binary format.  
If the database column contains `log_type` == `foo`, the data must then additionally be base64 decoded.  
This can be modelled as follows:

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Encoder\Base64Encoder;
use Waldhacker\Pseudify\Core\Processor\Processing\Analyze\TargetDataDecoderContext;
use Waldhacker\Pseudify\Core\Processor\Processing\DataProcessing;
use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetColumn;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetTable;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            // ...
            ->addTargetTable(table: TargetTable::create(identifier: 'wh_log',
                columns: [
                    TargetColumn::create(identifier: 'log_data', dataType: TargetColumn::DATA_TYPE_HEX)
                        ->addDataProcessing(dataProcessing: new DataProcessing(identifier: 'decode conditional log data',
                            processor: function (TargetDataDecoderContext $context): void {
                                $row = $context->getDatebaseRow();
                                if ('foo' !== $row['log_type']) {
                                    return;
                                }
                                $data = $context->getDecodedData();

                                $encoder = new Base64Encoder();
                                $logData = $encoder->decode(data: $data);

                                $context->setDecodedData(decodedData: $logData);
                            }
                        )),
                ]
            ))
        ;

        return $tableDefinition;
    }
}
```

With the method [`addDataProcessing()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TargetColumn.php#L131), further manual data transformations can be programmed in addition to the decoding of the data.  
The `DataProcessings` are executed after the decoding of the data.  
Any number of `DataProcessings` can be defined, which are processed one after the other.  

A [`DataProcessing`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/DataProcessing.php#L19) consists of a unique identification per database column (parameter `identifier`) and
an [anonymous function](https://www.php.net/manual/en/functions.anonymous.php) (parameter `processor`).  
The anonymous function is called with a parameter `context` of the type [`TargetDataDecoderContext`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Analyze/TargetDataDecoderContext.php).  
The `TargetDataDecoderContext` can be used to obtain various information about the data set to be processed:

* `$context->getRawData()`: The original data of the database column.
* `$context->getDecodedData()`: The data of the database column after decoding
* `$context->getDatebaseRow()`: Contains the original data of all database columns of the row being processed

With the method [`setDecodedData()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Analyze/TargetDataDecoderContext.php#L55) manually processed data can be passed to pseudify.  
This manually processed data is then searched by the analysis.

In our example, we use the value of the column `log_type` to determine whether the data needs to be further decoded using base64.  
If the value of `log_type` is not `foo`, nothing further is processed by the `return` statement.  
If the value of `log_type` is `foo`, the data is decoded by the Base64Encoder() and written back to pseudify by the `setDecodedData()` method.  

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 --------- --------------------- --------------- ----------------------- 
  Table     column                data decoders   data collectors        
 --------- --------------------- --------------- ----------------------- 
  wh_log    ip (string)           Scalar          default (scalar data)  
  wh_user   username (string)     Scalar          default (scalar data)  
  wh_user   password (string)     Scalar          default (scalar data)  
  wh_user   first_name (string)   Scalar          default (scalar data)  
  wh_user   last_name (string)    Scalar          default (scalar data)  
  wh_user   email (string)        Scalar          default (scalar data)  
  wh_user   city (string)         Scalar          default (scalar data)  
 --------- --------------------- --------------- ----------------------- 

Search data in this tables
--------------------------

 ----------------- -------------------------- --------------- ----------------------------- 
  Table             column                     data decoders   special data decoders        
 ----------------- -------------------------- --------------- ----------------------------- 
  wh_log            log_data (blob)            Hex             decode conditional log data  
  wh_log            log_type (string)          Scalar          no further processing        
  wh_log            log_message (text)         Scalar          no further processing        
  wh_meta_data      meta_data (blob)           Scalar          no further processing        
  wh_user_session   session_data (blob)        Scalar          no further processing        
  wh_user_session   session_data_json (text)   Scalar          no further processing        
 ----------------- -------------------------- --------------- -----------------------------
```

You will now see under `Search data in this tables` that the name `Hex` is listed under `data decoders` in the `wh_log` database column.  
This signals to you that the data will first be decoded using the HexEncoder.  
Under `special data decoders` the `DataProcessing` is listed with the identification `decode conditional log data`.  
This signals to you that after decoding the data, it will also be processed using the specified `DataProcessing`.  

##### Normalize JSON Data

If the data to be searched is in JSON format in the database, 
it should be normalised to make it fully searchable by pseudify.  
For example, UTF-8 characters are masked in JSON format, so for example an `Ö` in JSON format is masked by the string `\u00d6`.  

Example dataset:

```json
"{"oldRecord":{"bodytext":"<p>In 2023 sind folgende \u00d6ffentlichkeitsaktionen geplant:<\/p>"}}"
```

Assuming pseudify is to search for occurrences of the word `Öffentlichkeitsaktionen`, pseudify will not find this in the sample dataset due to the masking.  
To normalise the JSON string and make it look like this:

```json
"{"oldRecord":{"bodytext":"<p>In 2023 sind folgende Öffentlichkeitsaktionen geplant:</p>"}}"
```

the `DataProcessing` named [`normalisedJsonString()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Analyze/TargetDataDecoderPreset.php#L29) exists.  
The addition of this `DataProcessing` by using

```php
->addDataProcessing(dataProcessing: TargetDataDecoderPreset::normalizedJsonString())
```
to a database column containing JSON data structures, normalises the JSON string and makes it searchable for pseudify.  

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Processing\Analyze\TargetDataDecoderPreset;
use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetColumn;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TargetTable;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            // ...
            ->addTargetTable(table: TargetTable::create(identifier: 'wh_log',
                columns: [
                    TargetColumn::create(identifier: 'log_message')->addDataProcessing(dataProcessing: TargetDataDecoderPreset::normalizedJsonString()),
                ]
            ))
        ;

        return $tableDefinition;
    }
}
```

#### Define non-scalar source data

Sometimes it is necessary to define data from complex data structures as source data.  
As an example, we want to use data from the database column `session_data_json` of the table `wh_user_session` to use as source data.  
`session_data_json` contains a string in JSON format. In this there is a property called `data` consisting of an array with the property `last_ip` which we want to use as source data.  

```json
{"data": {"last_ip":"107.66.23.195"}}
```

You can pass the method [`SourceColumn::create()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/SourceColumn.php#L108) with the parameter `dataType` [a name of a built-in decoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/SourceColumn.php#L38-L50).  

!!! note
    As described in ["Search multiple encoded data"](#search-multiple-encoded-data), the [`ChainedEncoder`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/ChainedEncoder.php) can also be used here to decode multiple-encoded data.  

The method [`addDataProcessing()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/SourceColumn.php#L164) can now be used to define which data is to be extracted from the decoded data structure in order to use it as source data.  
The `DataProcessings` are executed after the decoding of the data.  
Any number of `DataProcessings` can be defined, which are processed one after the other.  

A `DataProcessing` consists of a unique identification per database column (parameter `identifier`) and an anonymous function (parameter `processor`).  
The anonymous function is called with a parameter `context` of the type [`SourceDataCollectorContext`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Analyze/SourceDataCollectorContext.php).  
The `SourceDataCollectorContext` can be used to obtain various information about the data set to be processed:

* `$context->getRawData()`: The original data of the database column.
* `$context->getDecodedData()`: The data of the database column after decoding
* `$context->getDatebaseRow()`: Contains the original data of all database columns of the row being processed

The [`addCollectedData()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Analyze/SourceDataCollectorContext.php#L75) method can be used to pass the extracted data to pseudify as source data.  
The method `addCollectedData()` can be used any number of times to pass any number of source data to pseudify.  
The method `addCollectedData()` can be passed either a string or a one-dimensional array. If an array is passed, all scalar data in it is extracted and passed to pseudify as source data.  

!!! info
    If no `DataProcessing` is defined, the standard DataProcessing [`SourceDataCollectorPreset::scalarData()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Analyze/SourceDataCollectorPreset.php#L29) is automatically used.
    This only collects the data from a database column if the content contains more than 2 characters.

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Processing\Analyze\SourceDataCollectorContext;
use Waldhacker\Pseudify\Core\Processor\Processing\DataProcessing;
use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\SourceColumn;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addSourceTable(table: 'wh_user_session', columns: [
                SourceColumn::create(identifier: 'session_data_json', dataType: SourceColumn::DATA_TYPE_JSON)
                    ->addDataProcessing(dataProcessing: new DataProcessing(identifier: 'extract ip address',
                        processor: function (SourceDataCollectorContext $context): void {
                            $data = $context->getDecodedData();
                            $context->addCollectedData(data: $data['data']['last_ip']);
                        }
                    )),
            ])
        ;

        return $tableDefinition;
    }
}
```

You will now see under `Collect search data from these tables` that the name `Json` is listed under `data decoders` in the database column `session_data_json`.  
This signals to you that the data will be decoded using the JsonEncoder.  
Under `data collectors` the `DataProcessing` is listed with the identification `extract ip address`.  
This signals to you that after decoding the data, it will also be collected using the specified `DataProcessing`.  

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 ----------------- -------------------------- --------------- -------------------- 
  Table             column                     data decoders   data collectors     
 ----------------- -------------------------- --------------- -------------------- 
  wh_user_session   session_data_json (text)   Json            extract ip address  
 ----------------- -------------------------- --------------- -------------------- 

Search data in this tables
--------------------------

 ----------------- --------------------- --------------- ----------------------- 
  Table             column                data decoders   special data decoders  
 ----------------- --------------------- --------------- ----------------------- 
  wh_log            id (integer)          Scalar          no further processing  
  wh_log            log_type (string)     Scalar          no further processing  
  wh_log            log_data (blob)       Scalar          no further processing  
  wh_log            log_message (text)    Scalar          no further processing  
  wh_log            ip (string)           Scalar          no further processing  
  wh_meta_data      id (integer)          Scalar          no further processing  
  wh_meta_data      meta_data (blob)      Scalar          no further processing  
  wh_user           id (integer)          Scalar          no further processing  
  wh_user           username (string)     Scalar          no further processing  
  wh_user           password (string)     Scalar          no further processing  
  wh_user           first_name (string)   Scalar          no further processing  
  wh_user           last_name (string)    Scalar          no further processing  
  wh_user           email (string)        Scalar          no further processing  
  wh_user           city (string)         Scalar          no further processing  
  wh_user_session   id (integer)          Scalar          no further processing  
  wh_user_session   session_data (blob)   Scalar          no further processing  
 ----------------- --------------------- --------------- -----------------------
```

#### Define custom source data

It is possible to define user-defined source data that does not refer to database columns.  
With the method [`addSourceString()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TableDefinition.php#L348) strings can be defined as source data.  

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addSourceTable(table: 'wh_log', columns: [
                'ip',
            ])
            ->addSourceTable(table: 'wh_user', columns: [
                'username',
                'password',
                'first_name',
                'last_name',
                'email',
                'city',
            ])

            ->addSourceString(string: 'example.com')
            ->addSourceString(string: 'regex:(?:[0-9]{1,3}\.){3}[0-9]{1,3}')

            // ...
        ;

        return $tableDefinition;
    }
}
```

You will now see the user-defined strings that are searched for in the database under 'Search for this strings'.  
As an alternative to static values, it is possible to use regular expressions for the search.  
A regular expression must be identified by the prefix `regex:` and follow the [PCRE regex syntax](https://www.php.net/manual/en/pcre.pattern.php).  
For example, `regex:(?:[0-9]{1,3}\.){3}[0-9]{1,3}` can be used to search for IPv4 addresses.  

```shell
$ pseudify pseudify:debug:analyze test-profile

Analyzer profile "test-profile"
===============================

Basis configuration
-------------------

 ----------------------------------------------- ------- 
  Key                                             Value  
 ----------------------------------------------- ------- 
  Shown characters before and after the finding   10     
 ----------------------------------------------- ------- 

Collect search data from this tables
------------------------------------

 --------- --------------------- --------------- ----------------------- 
  Table     column                data decoders   data collectors        
 --------- --------------------- --------------- ----------------------- 
  wh_log    ip (string)           Scalar          default (scalar data)  
  wh_user   username (string)     Scalar          default (scalar data)  
  wh_user   password (string)     Scalar          default (scalar data)  
  wh_user   first_name (string)   Scalar          default (scalar data)  
  wh_user   last_name (string)    Scalar          default (scalar data)  
  wh_user   email (string)        Scalar          default (scalar data)  
  wh_user   city (string)         Scalar          default (scalar data)  
 --------- --------------------- --------------- ----------------------- 

Search for this strings
-----------------------

 ------------------------------------- 
  String                               
 ------------------------------------- 
  example.com                          
  regex:(?:[0-9]{1,3}\.){3}[0-9]{1,3}  
 -------------------------------------

Search data in this tables
--------------------------

 ----------------- -------------------------- --------------- ----------------------- 
  Table             column                     data decoders   special data decoders  
 ----------------- -------------------------- --------------- ----------------------- 
  wh_log            log_type (string)          Scalar          no further processing  
  wh_log            log_data (blob)            Scalar          no further processing  
  wh_log            log_message (text)         Scalar          no further processing  
  wh_meta_data      meta_data (blob)           Scalar          no further processing  
  wh_user_session   session_data (blob)        Scalar          no further processing  
  wh_user_session   session_data_json (text)   Scalar          no further processing  
 ----------------- -------------------------- --------------- -----------------------
```

### Execute an "Analyze Profile"

An "Analyze Profile" can be executed with the command `pseudify:analyse <profile-name>`.

```shell
$ pseudify pseudify:analyze test-profile

 1224/1224 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100% < 1 sec/< 1 sec 4.0 MiB

summary
=======

 ----------------------------------- ---------------------------------------------------------------------------------------------- ------------------------------ 
  source                              data                                                                                           seems to be in                
 ----------------------------------- ---------------------------------------------------------------------------------------------- ------------------------------ 
  __custom__.__custom__               132.188.241.155                                                                                wh_log.ip                     
  __custom__.__custom__               155.215.67.191                                                                                 wh_log.ip                     
  __custom__.__custom__               243.202.241.67                                                                                 wh_log.ip                     
  __custom__.__custom__               132.188.241.155                                                                                wh_log.log_data               
  __custom__.__custom__               155.215.67.191                                                                                 wh_log.log_data               
  __custom__.__custom__               243.202.241.67                                                                                 wh_log.log_data               
  __custom__.__custom__               example.com                                                                                    wh_log.log_data               
  __custom__.__custom__               example.com                                                                                    wh_log.log_message            
  __custom__.__custom__               139.81.0.139                                                                                   wh_meta_data.meta_data        
  __custom__.__custom__               187.135.239.239                                                                                wh_meta_data.meta_data        
  __custom__.__custom__               197.110.248.18                                                                                 wh_meta_data.meta_data        
  __custom__.__custom__               20.1.58.149                                                                                    wh_meta_data.meta_data        
  __custom__.__custom__               239.27.57.12                                                                                   wh_meta_data.meta_data        
  __custom__.__custom__               244.166.32.78                                                                                  wh_meta_data.meta_data        
  __custom__.__custom__               83.243.216.115                                                                                 wh_meta_data.meta_data        
  __custom__.__custom__               example.com                                                                                    wh_meta_data.meta_data        
  __custom__.__custom__               107.66.23.195                                                                                  wh_user_session.session_data  
  __custom__.__custom__               197.110.248.18                                                                                 wh_user_session.session_data  
  __custom__.__custom__               244.166.32.78                                                                                  wh_user_session.session_data  
  wh_user.city                        Dorothyfort                                                                                    wh_meta_data.meta_data        
  wh_user.city                        North Elenamouth                                                                               wh_meta_data.meta_data        
  wh_user.city                        South Wilfordland                                                                              wh_meta_data.meta_data        
  wh_user.email                       mcclure.ofelia@example.com                                                                     wh_log.log_data               
  wh_user.email                       mcclure.ofelia@example.com                                                                     wh_log.log_message            
  wh_user.email                       cassin.bernadette@example.net                                                                  wh_meta_data.meta_data        
  wh_user.email                       conn.abigale@example.net                                                                       wh_meta_data.meta_data        
  wh_user.email                       mcclure.ofelia@example.com                                                                     wh_meta_data.meta_data        
  wh_user.first_name                  Donato                                                                                         wh_meta_data.meta_data        
  wh_user.first_name                  Maybell                                                                                        wh_meta_data.meta_data        
  wh_user.first_name                  Mckayla                                                                                        wh_meta_data.meta_data        
  wh_user.last_name                   Keeling                                                                                        wh_log.log_data               
  wh_user.last_name                   Anderson                                                                                       wh_meta_data.meta_data        
  wh_user.last_name                   Keeling                                                                                        wh_meta_data.meta_data        
  wh_user.last_name                   Stoltenberg                                                                                    wh_meta_data.meta_data        
  wh_user.password                    $argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U   wh_meta_data.meta_data        
  wh_user.password                    $argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q   wh_meta_data.meta_data        
  wh_user.password                    $argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs   wh_meta_data.meta_data        
  wh_user.username                    georgiana59                                                                                    wh_log.log_data               
  wh_user.username                    georgiana59                                                                                    wh_log.log_message            
  wh_user.username                    georgiana59                                                                                    wh_meta_data.meta_data        
  wh_user.username                    howell.damien                                                                                  wh_meta_data.meta_data        
  wh_user.username                    hpagac                                                                                         wh_meta_data.meta_data        
  wh_user_session.session_data_json   1321:57fc:460b:d4d0:d83f:c200:4b:f1c8                                                          wh_log.ip                     
  wh_user_session.session_data_json   4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98                                                         wh_log.ip                     
  wh_user_session.session_data_json   1321:57fc:460b:d4d0:d83f:c200:4b:f1c8                                                          wh_log.log_data               
  wh_user_session.session_data_json   4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98                                                         wh_log.log_data               
  wh_user_session.session_data_json   1321:57fc:460b:d4d0:d83f:c200:4b:f1c8                                                          wh_meta_data.meta_data        
  wh_user_session.session_data_json   197.110.248.18                                                                                 wh_meta_data.meta_data        
  wh_user_session.session_data_json   244.166.32.78                                                                                  wh_meta_data.meta_data        
  wh_user_session.session_data_json   107.66.23.195                                                                                  wh_user_session.session_data  
  wh_user_session.session_data_json   1321:57fc:460b:d4d0:d83f:c200:4b:f1c8                                                          wh_user_session.session_data  
  wh_user_session.session_data_json   197.110.248.18                                                                                 wh_user_session.session_data  
  wh_user_session.session_data_json   244.166.32.78                                                                                  wh_user_session.session_data  
  wh_user_session.session_data_json   4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98                                                         wh_user_session.session_data  
 ----------------------------------- ---------------------------------------------------------------------------------------------- ------------------------------
```

!!! note
    Depending on the size of the database, the analysis can be finished after seconds or only after hours.  
    Since analyses are usually only carried out infrequently, e.g. to model pseudonymisation with the collected information, we have decided that a somewhat longer runtime of an analysis is justifiable.  

The first line of the analysis indicates how many data have already been analysed and how many are analysed in total (`1148/1148`).  
This is followed by a progress bar and a percentage indication of the progress.  
After that, the runtime and the estimated total time of the analysis are output.  
Finally, the maximum memory consumption so far is output.  

The summary of the analysis finally lists which source data (column `data`) from which source database column (column `source`) can be found in which database columns (column `seems to be in`).  
If there is a `__custom__.__custom__` in the `source` column, this means that the source data does not come from a database column, but was defined using `addSourceString()`.  
If you were not previously aware that certain source data can be found in a database column under `seems to be in`, then you can now take a closer look at these database columns and include them in the modelling of the pseudonymisation.

!!! info
    If there are many database tables and columns, the output of the analysis can become very long and may not fit into the buffer of your terminal.
    In this case, it is worth writing the output to a file.

    ```shell
    pseudify --no-ansi pseudify:debug:analyze test-profile > analysis.log
    ```

#### Output extended information

For debugging or refining the analysis profile, it may be useful to see what data pseudify found in the database data.  
To do this, the command `pseudify:analyse` can be called with the parameter `--verbose`:

```shell
pseudify pseudify:analyze <profil-name> --verbose
```

Now the source data is listed (wh_log.ip (`1321:57fc:460b:d4d0:d83f:c200:4b:f1c8`)) and the location (wh_meta_data.meta_data (...ip";s:37:"`1321:57fc:460b:d4d0:d83f:c200:4b:f1c8`";}";}s:4:...))

<a href="../../img/analyze-debug.png" target="_blank">![](../img/analyze-debug.png)</a>

The number of characters that are output before and after the location can be defined with the [`setTargetDataFrameCuttingLength()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Analyze/TableDefinition.php#L386) method.  
By default, 10 characters are output before and after a target.  
If the value is set to 0, nothing is cut off before and after the target and the complete database content is output.  

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Profile\Analyze\ProfileInterface;
use Waldhacker\Pseudify\Core\Profile\Model\Analyze\TableDefinition;

class TestAnalyzeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            // ...
            ->setTargetDataFrameCuttingLength(length: 15);

        return $tableDefinition;
    }
}
```



