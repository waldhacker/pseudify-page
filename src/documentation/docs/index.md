![](img/logo.svg)

# Overview

## pseudify AI - the database pseudonymizer

**Pseudify** is a AI powered toolbox that helps you to pseudonymize database data.  
You can find hidden personally identifiable information (PII) in your database and you can pseudonymize them.  

### Features

&#127881; Analyze and pseudonymize supported databases from any application  
&#127881; Find hidden personally identifiable information (PII) with or without AI support  
&#127881; Data integrity: same input data generates same pseudonyms across all database columns  
&#127881; Analyze and pseudonymize easily encoded data  
&#127881; Analyze and pseudonymize multi-encoded data  
&#127881; Analyze and pseudonymize complex data structures like JSON or serialized PHP data  
&#127881; Analyze and pseudonymize dynamic data  
&#127881; 12 built-in decoders / encoders  
&#127881; Extensibility with custom decoders / encoders  
&#127881; 100+ built-in localizable fake data formats thanks to [FakerPHP](https://fakerphp.github.io/)  
&#127881; Extensibility with own fake data formats  
&#127881; Support for 7 built-in database platforms thanks to [Doctrine DBAL](https://www.doctrine-project.org/projects/dbal.html)  
&#127881; Extensibility with own database platforms  
&#127881; Modeling of profiles with a powerful GUI  

### Supported databases

#### MySQL

|             |doctrine dbal support                    |pseudify support                               |driver in docker image                   |
|:------------|:---------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**MySQL 5.1**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**MySQL 5.5**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 5.6**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 5.7**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 8.0**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 8.1**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 8.2**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 8.3**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 8.4**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 9.0**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**MySQL 9.1**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|

&#x2A; supported in principle, but not tested

---

#### MariaDB

|                 |doctrine dbal support                    |pseudify support                         |driver in docker image                   |
|:----------------|:---------------------------------------:|:---------------------------------------:|:---------------------------------------:|
|**MariaDB 10.2** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.3** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.4** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.5** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.6** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.7** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.8** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.9** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.10**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 10.11**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 11.0** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 11.1** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 11.2** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 11.3** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 11.4** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 11.5** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 11.6** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|
|**MariaDB 11.7** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>|

---

#### PostgreSQL

|                  |doctrine dbal support                    |pseudify support                               |driver in docker image                   |
|:-----------------|:---------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**PostgreSQL 8**  |<span style="color:green">&#x2714;</span>|<span style="color:red">&#x274C;</span>        |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 9**  |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 10** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 11** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 12** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 13** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 14** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 15** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 16** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**PostgreSQL 17** |<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|

&#x2A; supported in principle, but not tested

---

#### MS SQL Server

|                   |doctrine dbal support                    |pseudify support                               |driver in docker image                   |
|:------------------|:---------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**SQL Server 2014**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**SQL Server 2016**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:green">&#x2714;</span>|
|**SQL Server 2017**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**SQL Server 2019**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|
|**SQL Server 2022**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</span>      |<span style="color:green">&#x2714;</span>|

&#x2A; supported in principle, but not tested

---

#### SQLite

|              |doctrine dbal support                    |pseudify support                                 |driver in docker image                   |
|:-------------|:---------------------------------------:|:-----------------------------------------------:|:---------------------------------------:|
|**SQLite 1.x**|<span style="color:red">&#x274C;</span>  |<span style="color:red">&#x274C;</span>          |<span style="color:red">&#x274C;</span>  |
|**SQLite 2.x**|<span style="color:red">&#x274C;</span>  |<span style="color:red">&#x274C;</span>          |<span style="color:red">&#x274C;</span>  |
|**SQLite 3.x**|<span style="color:green">&#x2714;</span>|<span style="color:green">&#x2714;</PDO_ODBCspan>|<span style="color:green">&#x2714;</span>|

---

#### Oracle

|                      |doctrine dbal support                    |pseudify support                               |driver in docker image                 |
|:---------------------|:---------------------------------------:|:---------------------------------------------:|:-------------------------------------:|
|**Oracle Database 11**|<span style="color:red">&#x274C;</span>  |<span style="color:red">&#x274C;</span>        |<span style="color:red">&#x274C;</span>|
|**Oracle Database 12**|<span style="color:red">&#x274C;</span>  |<span style="color:red">&#x274C;</span>        |<span style="color:red">&#x274C;</span>|
|**Oracle Database 18**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|
|**Oracle Database 19**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|
|**Oracle Database 21**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|
|**Oracle Database 23**|<span style="color:green">&#x2714;</span>|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>|

&#x2A; supported in principle, but not tested

---

#### IBM DB2

|            |doctrine dbal support                          |pseudify support                               |driver in docker image                   |
|:-----------|:---------------------------------------------:|:---------------------------------------------:|:---------------------------------------:|
|**DB2 9**   |<span style="color:red">&#x2753;</span>        |<span style="color:red">&#x2753;</span>        |<span style="color:red">&#x274C;</span>  |
|**DB2 10**  |<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>  |
|**DB2 11**  |<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>  |
|**DB2 12**  |<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:grey">&#x2714;</span> &#x2A;|<span style="color:red">&#x274C;</span>  |

&#x2A; supported in principle, but not tested
