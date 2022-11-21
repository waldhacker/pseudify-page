# Overview

todo

## Unterstützte Datenbanken

### MySQL

|             |doctrine dbal Unterstützung              |pseudify Unterstützung                         |Treiber im docker image                  |
|:------------|:---------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**MySQL 5.1**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**MySQL 5.5**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**MySQL 5.6**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 5.7**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 8.0**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|

&#x2A; theoretisch funktional, wurde aber nicht getestet

---

### MariaDB

|                |doctrine dbal Unterstützung              |pseudify Unterstützung                   |Treiber im docker image                  |
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

|                  |doctrine dbal Unterstützung              |pseudify Unterstützung                         |Treiber im docker image                  |
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

&#x2A; theoretisch funktional, wurde aber nicht getestet

---

### MS SQL Server

|                   |doctrine dbal Unterstützung              |pseudify Unterstützung                         |Treiber im docker image                  |
|:------------------|:---------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**SQL Server 2014**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**SQL Server 2016**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**SQL Server 2017**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**SQL Server 2019**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**SQL Server 2022**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|

&#x2A; theoretisch funktional, wurde aber nicht getestet

---

### SQLite

|              |doctrine dbal Unterstützung              |pseudify Unterstützung                           |Treiber im docker image                  |
|:-------------|:---------------------------------------:|:-----------------------------------------------:|:---------------------------------------:|
|**SQLite 1.x**|<span style="color:red">&#x274C;</span>  |<span style="color:red">&#x274C;</span>          |<span style="color:red">&#x274C;</span>  |
|**SQLite 2.x**|<span style="color:red">&#x274C;</span>  |<span style="color:red">&#x274C;</span>          |<span style="color:red">&#x274C;</span>  |
|**SQLite 3.x**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</PDO_ODBCspan>|<span style="color:green">&#x2714;</span>|

---

### Oracle

|                      |doctrine dbal Unterstützung              |pseudify Unterstützung                         |Treiber im docker image                |
|:---------------------|:---------------------------------------:|:---------------------------------------------:|:-------------------------------------:|
|**Oracle Database 11**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|
|**Oracle Database 12**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|
|**Oracle Database 19**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|
|**Oracle Database 21**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|

&#x2A; theoretisch funktional, wurde aber nicht getestet

--

### IBM DB2

|            |doctrine dbal Unterstützung                    |pseudify Unterstützung                         |Treiber im docker image                  |
|:-----------|:---------------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**DB2 9.x** |<span style="color:red">&#x2753;</span>        |<span style="color:red">&#x2753;</span>        |<span style="color:red">&#x274C;</span>  |
|**DB2 10.x**|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>  |
|**DB2 11.x**|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>  |

&#x2A; theoretisch funktional, wurde aber nicht getestet
