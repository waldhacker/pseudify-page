# Overview

## Features

&#127881; Analyse and pseudonymise supported databases of any application  
&#127881; Find hidden personal data  
&#127881; Data integrity: same input data generate same pseudonyms across all database columns  
&#127881; Analyse and pseudonymise single encoded data  
&#127881; Analyse and pseudonymise multiple encoded data  
&#127881; Analyse and pseudonymise complex data structures such as JSON or serialised PHP data  
&#127881; Analyse and pseudonymise dynamic data  
&#127881; 12 Built-in decoders / encoders  
&#127881; Extensibility with own decoders / encoders  
&#127881; 100+ integrated localisable fake data formats thanks to FakerPHP  
&#127881; Extensibility with own fake data formats  
&#127881; 7 integrated database platforms are supported via Doctrine DBAL  
&#127881; Extensibility with own database platforms  
&#127881; Modelling of the profiles in PHP  

## Supported databases

### MySQL

|             |doctrine dbal support              |pseudify support                         |driver in docker image                  |
|:------------|:---------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**MySQL 5.1**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**MySQL 5.5**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**MySQL 5.6**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 5.7**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 8.0**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|

&#x2A; supported in principle, but not tested

---

### MariaDB

|                |doctrine dbal support              |pseudify support                   |driver in docker image                  |
|:---------------|:---------------------------------------:|:---------------------------------------:|:---------------------------------------:|
|**MariaDB 10.2**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.3**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.4**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.5**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.6**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.7**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.8**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.9**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|

---

### PostgreSQL

|                  |doctrine dbal support              |pseudify support                         |driver in docker image                  |
|:-----------------|:---------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**PostgreSQL 9.4**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**PostgreSQL 9.5**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 9.6**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 10** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 11** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 12** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 13** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 14** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 15** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|

&#x2A; supported in principle, but not tested

---

### MS SQL Server

|                   |doctrine dbal support              |pseudify support                         |driver in docker image                  |
|:------------------|:---------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**SQL Server 2014**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**SQL Server 2016**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**SQL Server 2017**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**SQL Server 2019**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**SQL Server 2022**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|

&#x2A; supported in principle, but not tested

---

### SQLite

|              |doctrine dbal support              |pseudify support                           |driver in docker image                  |
|:-------------|:---------------------------------------:|:-----------------------------------------------:|:---------------------------------------:|
|**SQLite 1.x**|<span style="color:red">&#x274C;</span>  |<span style="color:red">&#x274C;</span>          |<span style="color:red">&#x274C;</span>  |
|**SQLite 2.x**|<span style="color:red">&#x274C;</span>  |<span style="color:red">&#x274C;</span>          |<span style="color:red">&#x274C;</span>  |
|**SQLite 3.x**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</PDO_ODBCspan>|<span style="color:green">&#x2714;</span>|

---

### Oracle

|                      |doctrine dbal support              |pseudify support                         |driver in docker image                |
|:---------------------|:---------------------------------------:|:---------------------------------------------:|:-------------------------------------:|
|**Oracle Database 11**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|
|**Oracle Database 12**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|
|**Oracle Database 19**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|
|**Oracle Database 21**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|

&#x2A; supported in principle, but not tested

---

### IBM DB2

|            |doctrine dbal support                    |pseudify support                         |driver in docker image                  |
|:-----------|:---------------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**DB2 9.x** |<span style="color:red">&#x2753;</span>        |<span style="color:red">&#x2753;</span>        |<span style="color:red">&#x274C;</span>  |
|**DB2 10.x**|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>  |
|**DB2 11.x**|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>  |

&#x2A; supported in principle, but not tested
