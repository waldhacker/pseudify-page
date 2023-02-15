# Pseudonymise

## Model the pseudonymisation

!!! info
    You can view all the modelling described in this tutorial with comments [in the test folder of the profile templates (TestPseudonymizeProfile.php)](https://github.com/waldhacker/pseudify-profile-templates/blob/0.0.1/src/Profiles/Tests/TestPseudonymizeProfile.php).

### Get an overview

In order to be able to model the pseudonymisation, you need to know exactly in which database columns the data you want to pseudonymise are located.  
Pseudify provides you with tools for this task, which are documented in the chapter ["Analyse"](analyze.md).  

We analyse the database with the following profile (see chapter ["Analyse"](analyze.md)):

```php
<?php

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Encoder\Base64Encoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\ChainedEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\GzEncodeEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\HexEncoder;
use Waldhacker\Pseudify\Core\Processor\Processing\Analyze\TargetDataDecoderContext;
use Waldhacker\Pseudify\Core\Processor\Processing\Analyze\TargetDataDecoderPreset;
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

        $tableDefinition->setTargetDataFrameCuttingLength(length: 0);

        $tableDefinition
          ->addSourceTable(table: 'wh_user', columns: [
              'username',
              'password',
              'first_name',
              'last_name',
              'email',
              'city',
          ])

          ->addSourceString(string: 'regex:(?:[0-9]{1,3}\.){3}[0-9]{1,3}')

          ->excludeTargetColumnTypes(columnTypes: TableDefinition::COMMON_EXCLUED_TARGET_COLUMN_TYPES)

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
                  TargetColumn::create(identifier: 'log_message')->addDataProcessing(dataProcessing: TargetDataDecoderPreset::normalizedJsonString()),
              ]
          ))
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

We already know that the table `wh_user` contains data in the columns `username`, `password`, `first_name`, `last_name`, `email`, `city` which are to be pseudonymised.  
These data are in scalar form. They do not have to be decoded.  
The analysis now provides us with the locations in the database and shows us the corresponding data structures:  

```shell
$ pseudify pseudify:analyze test-profile -vv

wh_user.username (hpagac) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:3;s:8:"username";s:6:"hpagac";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U";s:18:"password_hash_type";s:7:"argon2i";s:18:"password_plaintext";s:8:"[dvGd#gI";s:10:"first_name";s:6:"Donato";s:9:"last_name";s:7:"Keeling";s:5:"email";s:26:"mcclure.ofelia@example.com";s:4:"city";s:16:"North Elenamouth";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"239.27.57.12";}})
wh_user.password ($argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:3;s:8:"username";s:6:"hpagac";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U";s:18:"password_hash_type";s:7:"argon2i";s:18:"password_plaintext";s:8:"[dvGd#gI";s:10:"first_name";s:6:"Donato";s:9:"last_name";s:7:"Keeling";s:5:"email";s:26:"mcclure.ofelia@example.com";s:4:"city";s:16:"North Elenamouth";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"239.27.57.12";}})
wh_user.first_name (Donato) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:3;s:8:"username";s:6:"hpagac";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U";s:18:"password_hash_type";s:7:"argon2i";s:18:"password_plaintext";s:8:"[dvGd#gI";s:10:"first_name";s:6:"Donato";s:9:"last_name";s:7:"Keeling";s:5:"email";s:26:"mcclure.ofelia@example.com";s:4:"city";s:16:"North Elenamouth";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"239.27.57.12";}})
wh_user.last_name (Keeling) -> wh_log.log_data ({"userName":"ronaldo15","email":"mcclure.ofelia@example.com","lastName":"Keeling","ip":"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8"})
wh_user.last_name (Keeling) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:3;s:8:"username";s:6:"hpagac";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U";s:18:"password_hash_type";s:7:"argon2i";s:18:"password_plaintext";s:8:"[dvGd#gI";s:10:"first_name";s:6:"Donato";s:9:"last_name";s:7:"Keeling";s:5:"email";s:26:"mcclure.ofelia@example.com";s:4:"city";s:16:"North Elenamouth";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"239.27.57.12";}})
wh_user.email (mcclure.ofelia@example.com) -> wh_log.log_data ({"userName":"ronaldo15","email":"mcclure.ofelia@example.com","lastName":"Keeling","ip":"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8"})
wh_user.email (mcclure.ofelia@example.com) -> wh_log.log_message ({"message":"foo text \"ronaldo15\", another \"mcclure.ofelia@example.com\""})
wh_user.email (mcclure.ofelia@example.com) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:3;s:8:"username";s:6:"hpagac";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U";s:18:"password_hash_type";s:7:"argon2i";s:18:"password_plaintext";s:8:"[dvGd#gI";s:10:"first_name";s:6:"Donato";s:9:"last_name";s:7:"Keeling";s:5:"email";s:26:"mcclure.ofelia@example.com";s:4:"city";s:16:"North Elenamouth";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"239.27.57.12";}})
wh_user.city (North Elenamouth) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:3;s:8:"username";s:6:"hpagac";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U";s:18:"password_hash_type";s:7:"argon2i";s:18:"password_plaintext";s:8:"[dvGd#gI";s:10:"first_name";s:6:"Donato";s:9:"last_name";s:7:"Keeling";s:5:"email";s:26:"mcclure.ofelia@example.com";s:4:"city";s:16:"North Elenamouth";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"239.27.57.12";}})
wh_user.username (georgiana59) -> wh_log.log_data (a:2:{i:0;s:14:"243.202.241.67";s:4:"user";O:8:"stdClass":5:{s:8:"userName";s:11:"georgiana59";s:8:"lastName";s:5:"Block";s:5:"email";s:19:"nolan11@example.net";s:2:"id";i:2;s:4:"user";R:3;}})
wh_user.username (georgiana59) -> wh_log.log_message ({"message":"bar text \"Block\", another \"georgiana59\""})
wh_user.username (georgiana59) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:4;s:8:"username";s:11:"georgiana59";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:10:"uGZIc|aX4d";s:10:"first_name";s:7:"Maybell";s:9:"last_name";s:8:"Anderson";s:5:"email";s:29:"cassin.bernadette@example.net";s:4:"city";s:17:"South Wilfordland";}s:4:"key2";a:2:{s:2:"id";i:4;s:12:"session_data";s:65:"a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";}";}s:4:"key3";a:1:{s:4:"key4";s:11:"20.1.58.149";}})
wh_user.password ($argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:4;s:8:"username";s:11:"georgiana59";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:10:"uGZIc|aX4d";s:10:"first_name";s:7:"Maybell";s:9:"last_name";s:8:"Anderson";s:5:"email";s:29:"cassin.bernadette@example.net";s:4:"city";s:17:"South Wilfordland";}s:4:"key2";a:2:{s:2:"id";i:4;s:12:"session_data";s:65:"a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";}";}s:4:"key3";a:1:{s:4:"key4";s:11:"20.1.58.149";}})
wh_user.first_name (Maybell) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:4;s:8:"username";s:11:"georgiana59";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:10:"uGZIc|aX4d";s:10:"first_name";s:7:"Maybell";s:9:"last_name";s:8:"Anderson";s:5:"email";s:29:"cassin.bernadette@example.net";s:4:"city";s:17:"South Wilfordland";}s:4:"key2";a:2:{s:2:"id";i:4;s:12:"session_data";s:65:"a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";}";}s:4:"key3";a:1:{s:4:"key4";s:11:"20.1.58.149";}})
wh_user.last_name (Anderson) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:4;s:8:"username";s:11:"georgiana59";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:10:"uGZIc|aX4d";s:10:"first_name";s:7:"Maybell";s:9:"last_name";s:8:"Anderson";s:5:"email";s:29:"cassin.bernadette@example.net";s:4:"city";s:17:"South Wilfordland";}s:4:"key2";a:2:{s:2:"id";i:4;s:12:"session_data";s:65:"a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";}";}s:4:"key3";a:1:{s:4:"key4";s:11:"20.1.58.149";}})
wh_user.email (cassin.bernadette@example.net) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:4;s:8:"username";s:11:"georgiana59";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:10:"uGZIc|aX4d";s:10:"first_name";s:7:"Maybell";s:9:"last_name";s:8:"Anderson";s:5:"email";s:29:"cassin.bernadette@example.net";s:4:"city";s:17:"South Wilfordland";}s:4:"key2";a:2:{s:2:"id";i:4;s:12:"session_data";s:65:"a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";}";}s:4:"key3";a:1:{s:4:"key4";s:11:"20.1.58.149";}})
wh_user.city (South Wilfordland) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:4;s:8:"username";s:11:"georgiana59";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:10:"uGZIc|aX4d";s:10:"first_name";s:7:"Maybell";s:9:"last_name";s:8:"Anderson";s:5:"email";s:29:"cassin.bernadette@example.net";s:4:"city";s:17:"South Wilfordland";}s:4:"key2";a:2:{s:2:"id";i:4;s:12:"session_data";s:65:"a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";}";}s:4:"key3";a:1:{s:4:"key4";s:11:"20.1.58.149";}})
wh_user.username (howell.damien) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"139.81.0.139";}})
wh_user.username (howell.damien) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:15:"187.135.239.239";}})
wh_user.username (howell.damien) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:5;s:12:"session_data";s:42:"a:1:{s:7:"last_ip";s:14:"197.110.248.18";}";}s:4:"key3";a:1:{s:4:"key4";s:14:"83.243.216.115";}})
wh_user.password ($argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"139.81.0.139";}})
wh_user.password ($argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:15:"187.135.239.239";}})
wh_user.password ($argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:5;s:12:"session_data";s:42:"a:1:{s:7:"last_ip";s:14:"197.110.248.18";}";}s:4:"key3";a:1:{s:4:"key4";s:14:"83.243.216.115";}})
wh_user.first_name (Mckayla) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"139.81.0.139";}})
wh_user.first_name (Mckayla) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:15:"187.135.239.239";}})
wh_user.first_name (Mckayla) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:5;s:12:"session_data";s:42:"a:1:{s:7:"last_ip";s:14:"197.110.248.18";}";}s:4:"key3";a:1:{s:4:"key4";s:14:"83.243.216.115";}})
wh_user.last_name (Stoltenberg) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"139.81.0.139";}})
wh_user.last_name (Stoltenberg) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:15:"187.135.239.239";}})
wh_user.last_name (Stoltenberg) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:5;s:12:"session_data";s:42:"a:1:{s:7:"last_ip";s:14:"197.110.248.18";}";}s:4:"key3";a:1:{s:4:"key4";s:14:"83.243.216.115";}})
wh_user.email (conn.abigale@example.net) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"139.81.0.139";}})
wh_user.email (conn.abigale@example.net) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:15:"187.135.239.239";}})
wh_user.email (conn.abigale@example.net) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:5;s:12:"session_data";s:42:"a:1:{s:7:"last_ip";s:14:"197.110.248.18";}";}s:4:"key3";a:1:{s:4:"key4";s:14:"83.243.216.115";}})
wh_user.city (Dorothyfort) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"139.81.0.139";}})
wh_user.city (Dorothyfort) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:15:"187.135.239.239";}})
wh_user.city (Dorothyfort) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:5;s:12:"session_data";s:42:"a:1:{s:7:"last_ip";s:14:"197.110.248.18";}";}s:4:"key3";a:1:{s:4:"key4";s:14:"83.243.216.115";}})
__custom__.__custom__ (1321:57fc:460b:d4d0:d83f:c200:4b:f1c8) -> wh_log.log_data ({"userName":"ronaldo15","email":"mcclure.ofelia@example.com","lastName":"Keeling","ip":"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8"})
__custom__.__custom__ (1321:57fc:460b:d4d0:d83f:c200:4b:f1c8) -> wh_log.ip (1321:57fc:460b:d4d0:d83f:c200:4b:f1c8)
__custom__.__custom__ (155.215.67.191) -> wh_log.log_data ({"userName":"stark.judd","email":"srowe@example.net","lastName":"Boyer","ip":"155.215.67.191"})
__custom__.__custom__ (155.215.67.191) -> wh_log.ip (155.215.67.191)
__custom__.__custom__ (4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98) -> wh_log.log_data (a:2:{i:0;s:38:"4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98";s:4:"user";O:8:"stdClass":5:{s:8:"userName";s:12:"freida.mante";s:8:"lastName";s:5:"Tromp";s:5:"email";s:23:"lafayette64@example.net";s:2:"id";i:10;s:4:"user";R:3;}})
__custom__.__custom__ (4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98) -> wh_log.ip (4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98)
__custom__.__custom__ (243.202.241.67) -> wh_log.log_data (a:2:{i:0;s:14:"243.202.241.67";s:4:"user";O:8:"stdClass":5:{s:8:"userName";s:11:"georgiana59";s:8:"lastName";s:5:"Block";s:5:"email";s:19:"nolan11@example.net";s:2:"id";i:2;s:4:"user";R:3;}})
__custom__.__custom__ (243.202.241.67) -> wh_log.ip (243.202.241.67)
__custom__.__custom__ (132.188.241.155) -> wh_log.log_data (a:2:{i:0;s:15:"132.188.241.155";s:4:"user";O:8:"stdClass":5:{s:8:"userName";s:7:"cyril06";s:8:"lastName";s:8:"Homenick";s:5:"email";s:21:"clinton44@example.net";s:2:"id";i:91;s:4:"user";R:3;}})
__custom__.__custom__ (132.188.241.155) -> wh_log.ip (132.188.241.155)
__custom__.__custom__ (244.166.32.78) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"139.81.0.139";}})
__custom__.__custom__ (139.81.0.139) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"139.81.0.139";}})
__custom__.__custom__ (244.166.32.78) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:15:"187.135.239.239";}})
__custom__.__custom__ (187.135.239.239) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:15:"187.135.239.239";}})
__custom__.__custom__ (20.1.58.149) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:4;s:8:"username";s:11:"georgiana59";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:10:"uGZIc|aX4d";s:10:"first_name";s:7:"Maybell";s:9:"last_name";s:8:"Anderson";s:5:"email";s:29:"cassin.bernadette@example.net";s:4:"city";s:17:"South Wilfordland";}s:4:"key2";a:2:{s:2:"id";i:4;s:12:"session_data";s:65:"a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";}";}s:4:"key3";a:1:{s:4:"key4";s:11:"20.1.58.149";}})
__custom__.__custom__ (1321:57fc:460b:d4d0:d83f:c200:4b:f1c8) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:4;s:8:"username";s:11:"georgiana59";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:10:"uGZIc|aX4d";s:10:"first_name";s:7:"Maybell";s:9:"last_name";s:8:"Anderson";s:5:"email";s:29:"cassin.bernadette@example.net";s:4:"city";s:17:"South Wilfordland";}s:4:"key2";a:2:{s:2:"id";i:4;s:12:"session_data";s:65:"a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";}";}s:4:"key3";a:1:{s:4:"key4";s:11:"20.1.58.149";}})
__custom__.__custom__ (197.110.248.18) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:5;s:12:"session_data";s:42:"a:1:{s:7:"last_ip";s:14:"197.110.248.18";}";}s:4:"key3";a:1:{s:4:"key4";s:14:"83.243.216.115";}})
__custom__.__custom__ (83.243.216.115) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:5;s:8:"username";s:13:"howell.damien";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs";s:18:"password_hash_type";s:8:"argon2id";s:18:"password_plaintext";s:13:"nF5;06?nsS/nE";s:10:"first_name";s:7:"Mckayla";s:9:"last_name";s:11:"Stoltenberg";s:5:"email";s:24:"conn.abigale@example.net";s:4:"city";s:11:"Dorothyfort";}s:4:"key2";a:2:{s:2:"id";i:5;s:12:"session_data";s:42:"a:1:{s:7:"last_ip";s:14:"197.110.248.18";}";}s:4:"key3";a:1:{s:4:"key4";s:14:"83.243.216.115";}})
__custom__.__custom__ (244.166.32.78) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:3;s:8:"username";s:6:"hpagac";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U";s:18:"password_hash_type";s:7:"argon2i";s:18:"password_plaintext";s:8:"[dvGd#gI";s:10:"first_name";s:6:"Donato";s:9:"last_name";s:7:"Keeling";s:5:"email";s:26:"mcclure.ofelia@example.com";s:4:"city";s:16:"North Elenamouth";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"239.27.57.12";}})
__custom__.__custom__ (239.27.57.12) -> wh_meta_data.meta_data (a:3:{s:4:"key1";a:9:{s:2:"id";i:3;s:8:"username";s:6:"hpagac";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U";s:18:"password_hash_type";s:7:"argon2i";s:18:"password_plaintext";s:8:"[dvGd#gI";s:10:"first_name";s:6:"Donato";s:9:"last_name";s:7:"Keeling";s:5:"email";s:26:"mcclure.ofelia@example.com";s:4:"city";s:16:"North Elenamouth";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"239.27.57.12";}})
__custom__.__custom__ (4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98) -> wh_user_session.session_data (a:1:{s:7:"last_ip";s:38:"4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98";})
__custom__.__custom__ (4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98) -> wh_user_session.session_data_json ({"data":{"last_ip":"4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98"}})
__custom__.__custom__ (107.66.23.195) -> wh_user_session.session_data (a:1:{s:7:"last_ip";s:13:"107.66.23.195";})
__custom__.__custom__ (107.66.23.195) -> wh_user_session.session_data_json ({"data":{"last_ip":"107.66.23.195"}})
__custom__.__custom__ (244.166.32.78) -> wh_user_session.session_data (a:1:{s:7:"last_ip";s:13:"244.166.32.78";})
__custom__.__custom__ (244.166.32.78) -> wh_user_session.session_data_json ({"data":{"last_ip":"244.166.32.78"}})
__custom__.__custom__ (1321:57fc:460b:d4d0:d83f:c200:4b:f1c8) -> wh_user_session.session_data (a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";})
__custom__.__custom__ (1321:57fc:460b:d4d0:d83f:c200:4b:f1c8) -> wh_user_session.session_data_json ({"data":{"last_ip":"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8"}})
__custom__.__custom__ (197.110.248.18) -> wh_user_session.session_data (a:1:{s:7:"last_ip";s:14:"197.110.248.18";})
__custom__.__custom__ (197.110.248.18) -> wh_user_session.session_data_json ({"data":{"last_ip":"197.110.248.18"}})

 1209/1209 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100% < 1 sec/< 1 sec 4.0 MiB

summary
=======

 ----------------------- ---------------------------------------------------------------------------------------------- ----------------------------------- 
  source                  data                                                                                           seems to be in                     
 ----------------------- ---------------------------------------------------------------------------------------------- ----------------------------------- 
  __custom__.__custom__   132.188.241.155                                                                                wh_log.ip                          
  __custom__.__custom__   1321:57fc:460b:d4d0:d83f:c200:4b:f1c8                                                          wh_log.ip                          
  __custom__.__custom__   155.215.67.191                                                                                 wh_log.ip                          
  __custom__.__custom__   243.202.241.67                                                                                 wh_log.ip                          
  __custom__.__custom__   4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98                                                         wh_log.ip                          
  __custom__.__custom__   132.188.241.155                                                                                wh_log.log_data                    
  __custom__.__custom__   1321:57fc:460b:d4d0:d83f:c200:4b:f1c8                                                          wh_log.log_data                    
  __custom__.__custom__   155.215.67.191                                                                                 wh_log.log_data                    
  __custom__.__custom__   243.202.241.67                                                                                 wh_log.log_data                    
  __custom__.__custom__   4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98                                                         wh_log.log_data                    
  __custom__.__custom__   1321:57fc:460b:d4d0:d83f:c200:4b:f1c8                                                          wh_meta_data.meta_data             
  __custom__.__custom__   139.81.0.139                                                                                   wh_meta_data.meta_data             
  __custom__.__custom__   187.135.239.239                                                                                wh_meta_data.meta_data             
  __custom__.__custom__   197.110.248.18                                                                                 wh_meta_data.meta_data             
  __custom__.__custom__   20.1.58.149                                                                                    wh_meta_data.meta_data             
  __custom__.__custom__   239.27.57.12                                                                                   wh_meta_data.meta_data             
  __custom__.__custom__   244.166.32.78                                                                                  wh_meta_data.meta_data             
  __custom__.__custom__   83.243.216.115                                                                                 wh_meta_data.meta_data             
  __custom__.__custom__   107.66.23.195                                                                                  wh_user_session.session_data       
  __custom__.__custom__   1321:57fc:460b:d4d0:d83f:c200:4b:f1c8                                                          wh_user_session.session_data       
  __custom__.__custom__   197.110.248.18                                                                                 wh_user_session.session_data       
  __custom__.__custom__   244.166.32.78                                                                                  wh_user_session.session_data       
  __custom__.__custom__   4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98                                                         wh_user_session.session_data       
  __custom__.__custom__   107.66.23.195                                                                                  wh_user_session.session_data_json  
  __custom__.__custom__   1321:57fc:460b:d4d0:d83f:c200:4b:f1c8                                                          wh_user_session.session_data_json  
  __custom__.__custom__   197.110.248.18                                                                                 wh_user_session.session_data_json  
  __custom__.__custom__   244.166.32.78                                                                                  wh_user_session.session_data_json  
  __custom__.__custom__   4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98                                                         wh_user_session.session_data_json 
  wh_user.city            Dorothyfort                                                                                    wh_meta_data.meta_data             
  wh_user.city            North Elenamouth                                                                               wh_meta_data.meta_data             
  wh_user.city            South Wilfordland                                                                              wh_meta_data.meta_data             
  wh_user.email           mcclure.ofelia@example.com                                                                     wh_log.log_data                    
  wh_user.email           mcclure.ofelia@example.com                                                                     wh_log.log_message                 
  wh_user.email           cassin.bernadette@example.net                                                                  wh_meta_data.meta_data             
  wh_user.email           conn.abigale@example.net                                                                       wh_meta_data.meta_data             
  wh_user.email           mcclure.ofelia@example.com                                                                     wh_meta_data.meta_data             
  wh_user.first_name      Donato                                                                                         wh_meta_data.meta_data             
  wh_user.first_name      Maybell                                                                                        wh_meta_data.meta_data             
  wh_user.first_name      Mckayla                                                                                        wh_meta_data.meta_data             
  wh_user.last_name       Keeling                                                                                        wh_log.log_data                    
  wh_user.last_name       Anderson                                                                                       wh_meta_data.meta_data             
  wh_user.last_name       Keeling                                                                                        wh_meta_data.meta_data             
  wh_user.last_name       Stoltenberg                                                                                    wh_meta_data.meta_data             
  wh_user.password        $argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U   wh_meta_data.meta_data             
  wh_user.password        $argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q   wh_meta_data.meta_data             
  wh_user.password        $argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs   wh_meta_data.meta_data             
  wh_user.username        georgiana59                                                                                    wh_log.log_data                    
  wh_user.username        georgiana59                                                                                    wh_log.log_message                 
  wh_user.username        georgiana59                                                                                    wh_meta_data.meta_data             
  wh_user.username        howell.damien                                                                                  wh_meta_data.meta_data             
  wh_user.username        hpagac                                                                                         wh_meta_data.meta_data             
 ----------------------- ---------------------------------------------------------------------------------------------- -----------------------------------
```

We can identify the following data structures and content from the analysis results:


Table: wh_user.username  
Data encoding: scalar  
Data type: scalar  
Contains: username
Example: `georgiana59`

Table: wh_user.password  
Data encoding: scalar  
Data type: scalar  
Contains: password hash
Example: `$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs`

Table: wh_user.first_name  
Data encoding: scalar  
Data type: scalar  
Contains: first name
Example: `Mckayla`

Table: wh_user.last_name  
Data encoding: scalar  
Data type: scalar  
Contains: last name  
Example: `Keeling`

Table: wh_user.email  
Data encoding: scalar  
Data type: scalar  
Contains: e-mail
Example: `mcclure.ofelia@example.com`

Table: wh_user.city  
Data encoding: scalar  
Data type: scalar  
Contains: City
Example: `North Elenamouth`

Table: wh_meta_data.meta_data  
Data encoding: Hex &gt; GzEncode  
Data type: [serialisierte PHP-Daten](https://www.php.net/manual/en/function.serialize.php)  
Contains: username, first name, last name, e-mail, city, password, IPv4, IPv6  
Example: `a:3:{s:4:"key1";a:9:{s:2:"id";i:3;s:8:"username";s:6:"hpagac";s:8:"password";s:92:"$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U";s:18:"password_hash_type";s:7:"argon2i";s:18:"password_plaintext";s:8:"[dvGd#gI";s:10:"first_name";s:6:"Donato";s:9:"last_name";s:7:"Keeling";s:5:"email";s:26:"mcclure.ofelia@example.com";s:4:"city";s:16:"North Elenamouth";}s:4:"key2";a:2:{s:2:"id";i:3;s:12:"session_data";s:41:"a:1:{s:7:"last_ip";s:13:"244.166.32.78";}";}s:4:"key3";a:1:{s:4:"key4";s:12:"239.27.57.12";}}`  

Table: wh_log.log_data (logType == foo)  
Data encoding: Hex  
Data type: JSON  
Contains: username, first name, last name, e-mail, IPv4, IPv6  
Example: `{"userName":"ronaldo15","email":"mcclure.ofelia@example.com","lastName":"Keeling","ip":"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8"}`  

Table: wh_log.log_data (logType == bar)  
Data encoding: Hex &gt; Base64  
Data type: [serialisierte PHP-Daten](https://www.php.net/manual/en/function.serialize.php)  
Contains: username, first name, last name, e-mail, IPv4, IPv6  
Example: `a:2:{i:0;s:14:"243.202.241.67";s:4:"user";O:8:"stdClass":5:{s:8:"userName";s:11:"georgiana59";s:8:"lastName";s:5:"Block";s:5:"email";s:19:"nolan11@example.net";s:2:"id";i:2;s:4:"user";R:3;}}`  

Table: wh_log.log_message (logType == foo)  
Data encoding: scalar  
Data type: JSON  
Contains: username, e-mail  
Example: `{"message":"foo text \"ronaldo15\", another \"mcclure.ofelia@example.com\""}`  

Table: wh_log.log_message (logType == bar)  
Data encoding: scalar  
Data type: JSON  
Contains: username, last name  
Example: `{"message":"bar text \"Block\", another \"georgiana59\""}`  

Table: wh_log.ip  
Data encoding: scalar  
Data type: scalar  
Contains: IPv4, IPv6  
Example: `155.215.67.191`  

Table: wh_user_session.session_data  
Data encoding: scalar  
Data type: [serialised PHP data](https://www.php.net/manual/en/function.serialize.php)  
Contains: IPv4, IPv6  
Example: `a:1:{s:7:"last_ip";s:38:"4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98";}`  

Table: wh_user_session.session_data_json  
Data encoding: scalar  
Data type: JSON  
Contains: IPv4, IPv6  
Example: `{"data":{"last_ip":"107.66.23.195"}}`  

### Model a "Pseudonymizer Profile"

#### Create a "Profile"

Create a PHP file with any name in the [src/Profiles](https://github.com/waldhacker/pseudify-profile-templates/tree/0.0.1/src/Profiles) folder.  
In the example, the file is called `TestPseudonymizeProfile.php`.  
The file will have the following content:

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
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

After the profile has been created, the cache must be cleared. 

```shell
$ docker run -it -v $(pwd):/data \
  ghcr.io/waldhacker/pseudify cache:clear
```

The command `pseudify pseudify:debug:pseudonymize test-profile` already gives you (empty) information about your profile.

```shell
$ pseudify pseudify:debug:pseudonymize test-profile

Pseudonymization profile "test-profile"
=======================================

Pseudonymize data in this tables
--------------------------------

 ------- -------- --------------- ------------------- 
  Table   column   data decoders   data manipulators  
 ------- -------- --------------- -------------------
```

#### Pseudonymise data of a database column

We start by modelling the scalar database data.  
To do this, you extend the `getTableDefinition()` method in the profile.

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addTable(table: 'wh_user', columns: [
                Column::create(identifier: 'username'),
                Column::create(identifier: 'password'),
                Column::create(identifier: 'first_name'),
                Column::create(identifier: 'last_name'),
                Column::create(identifier: 'email'),
                Column::create(identifier: 'city'),
            ])
            ->addTable(table: 'wh_log', columns: [
                Column::create(identifier: 'ip'),
            ])
        ;

        return $tableDefinition;
    }
}
```

With the method [`addTable()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/TableDefinition.php#L72) you tell pseudify that you want to model a database table.  
In the parameter `columns` one or more database columns can be modelled ([`Column::create()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/Column.php#L112)).  

The command `pseudify pseudify:debug:pseudonymize test-profile` displays the modelled database columns under `pseudonymize data in this tables`.  
Under `data decoders` is `scalar`, which means that the data is not decoded during the pseudonymisation.  

```shell
$ pseudify pseudify:debug:pseudonymize test-profile

Pseudonymization profile "test-profile"
=======================================

Pseudonymize data in this tables
--------------------------------

 --------- --------------------- --------------- ------------------- 
  Table     column                data decoders   data manipulators  
 --------- --------------------- --------------- ------------------- 
  wh_user   username (string)     Scalar                             
  wh_user   password (string)     Scalar                             
  wh_user   first_name (string)   Scalar                             
  wh_user   last_name (string)    Scalar                             
  wh_user   email (string)        Scalar                             
  wh_user   city (string)         Scalar                             
  wh_log    ip (string)           Scalar                             
 --------- --------------------- --------------- -------------------
```

##### Encoded database columns

As already learned in the chapter ["Searching encoded data"](analyze.md#search-encoded-data), it can happen that data in database columns are in encoded form.  
This means that the encoded plaintext must be decoded before pseudonymisation and encoded again after pseudonymisation.  
In our example, the database column `log_message` of the table `wh_log` and the database column `session_data_json` of the table `wh_user_session` contain encoded data in JSON format.  
The database column `session_data` of the table `wh_user_session` contains encoded data in the form of serialised PHP data.  
You have to find out how this data is encoded by looking at the source code or the documentation of the application that uses the database.  

In order for pseudify to pseudonymise the data, the data must first be decoded.  
To do this, the data type (parameter `dataType`) must be passed to the definition of a database column ([`Column::create()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/Column.php#L112)). 

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
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
            ->addTable(table: 'wh_log', columns: [
                // ...
                Column::create(identifier: 'log_message', dataType: Column::DATA_TYPE_JSON),
            ])
            ->addTable(table: 'wh_user_session', columns: [
                Column::create(identifier: 'session_data', dataType: Column::DATA_TYPE_SERIALIZED),
                Column::create(identifier: 'session_data_json', dataType: Column::DATA_TYPE_JSON),
            ])
        ;

        return $tableDefinition;
    }
}
```

The method `Column::create()` can be passed with the parameter `dataType` [a name of a built-in decoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/Column.php#L66-L104).  
This is equivalent to `->setEncoder(encoder: new JsonEncoder())` or `->setEncoder(encoder: new SerializedEncoder())`:

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Encoder\JsonEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
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
            ->addTable(table: 'wh_log', columns: [
                // ...
                Column::create(identifier: 'log_message')->setEncoder(encoder: new JsonEncoder()),
            ])
            ->addTable(table: 'wh_user_session', columns: [
                Column::create(identifier: 'session_data')->setEncoder(encoder: new SerializedEncoder()),
                Column::create(identifier: 'session_data_json')->setEncoder(encoder: new JsonEncoder()),
            ])
        ;

        return $tableDefinition;
    }
}
```

The command `pseudify pseudify:debug:pseudonymize test-profile` now displays the just modelled database columns under `pseudonymize data in this tables`.  
Under `data decoders` the just defined decoders are listed (`Json` / `Serialised`).  

```shell
pseudify pseudify:debug:pseudonymize test-profile

Pseudonymization profile "test-profile"
=======================================

Pseudonymize data in this tables
--------------------------------

 ----------------- -------------------------- --------------- ------------------- 
  Table             column                     data decoders   data manipulators  
 ----------------- -------------------------- --------------- ------------------- 
  wh_user           username (string)          Scalar                             
  wh_user           password (string)          Scalar                             
  wh_user           first_name (string)        Scalar                             
  wh_user           last_name (string)         Scalar                             
  wh_user           email (string)             Scalar                             
  wh_user           city (string)              Scalar                             
  wh_log            ip (string)                Scalar                             
  wh_log            log_message (text)         Json                               
  wh_user_session   session_data (blob)        Serialized                         
  wh_user_session   session_data_json (text)   Json                               
 ----------------- -------------------------- --------------- -------------------
```

###### Multiple encoded database columns

As already learned in the chapter ["Searching multiple encoded data"](analyze.md#search-multiple-encoded-data), it can happen that data in database columns are stored in multiple encoded form.  
In our example, the database column `meta_data` of the table `wh_meta_data` contains multiple encoded data.  
In order for pseudify to pseudonymise the data, the data must first be converted from hexadecimal representation form to binary format and then the binary data must still be decompressed from ZLIB format.  
To perform multiple decoding, the [`ChainedEncoder`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/ChainedEncoder.php) can be used.  
With the ChainedEncoder, several decoders can be configured, which then decode the data in sequence.  

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Encoder\ChainedEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\GzEncodeEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\HexEncoder;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
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
            ->addTable(table: 'wh_meta_data', columns: [
                Column::create(identifier: 'meta_data')->setEncoder(encoder: new ChainedEncoder(encoders: [
                    new HexEncoder(),
                    new GzEncodeEncoder(defaultContext: [
                        GzEncodeEncoder::ENCODE_LEVEL => 5,
                        GzEncodeEncoder::ENCODE_ENCODING => ZLIB_ENCODING_GZIP,
                    ]),
                ])),
            ])
        ;

        return $tableDefinition;
    }
}
```

When pseudonymising the database column `meta_data` of the table `wh_meta_data`, pseudify will then first process the data of the database column using [the method `decode()` of HexEncoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/HexEncoder.php#L38)
and then by [the `decode()` method of the GzEncodeEncoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/GzEncodeEncoder.php#L46), so that the result can then be pseudonymised.  
Afterwards, pseudify will process the pseudonymised data using [the `encode()` method of the GzEncodeEncoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/GzEncodeEncoder.php#L60)
and then process it using [the `encode()` method of the HexEncoder](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/HexEncoder.php#L50) and write it back to the database.  

```shell
pseudify pseudify:debug:pseudonymize test-profile

Pseudonymization profile "test-profile"
=======================================

Pseudonymize data in this tables
--------------------------------

 ----------------- -------------------------- --------------- ------------------- 
  Table             column                     data decoders   data manipulators  
 ----------------- -------------------------- --------------- ------------------- 
  wh_user           username (string)          Scalar                             
  wh_user           password (string)          Scalar                             
  wh_user           first_name (string)        Scalar                             
  wh_user           last_name (string)         Scalar                             
  wh_user           email (string)             Scalar                             
  wh_user           city (string)              Scalar                             
  wh_log            ip (string)                Scalar                             
  wh_log            log_message (text)         Json                               
  wh_user_session   session_data (blob)        Serialized                         
  wh_user_session   session_data_json (text)   Json                               
  wh_meta_data      meta_data (blob)           Hex<>GzEncode                      
 ----------------- -------------------------- --------------- -------------------
```

You will now see under `Pseudonymize data in these tables` that the names `Hex<>GzEncode` are listed under `data decoders` of the database column `wh_meta_data`.  
This signals to you that the data will first be decoded using the HexEncoder and then using the GzEncodeEncoder.  

###### Differently encoded database columns

As already learned in the chapter ["Searching differently encoded data"](analyze.md#search-differently-encoded-data), it can happen that data in database columns are stored in differently encoded form.  
Based on conditions, applications store the data in different formats.  
In our example, the data in the database column `log_data` of the table `wh_log` is serialised PHP data, which has been encoded in Base64 format and then stored in hexadecimal form if the database column `log_type` contains the value `bar`.  
The data of the database column `log_data` are in JSON format, which were stored in hexadecimal form if the database column `log_type` contains the value `foo`.  

In both cases (`log_type` == `foo` and `log_type` == `bar`) the data can first be converted from hexadecimal representation to binary format.  
The further decodings must be modelled manually:

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Encoder\Base64Encoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\ChainedEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\GzEncodeEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\HexEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\JsonEncoder;
use Waldhacker\Pseudify\Core\Processor\Processing\DataProcessing;
use Waldhacker\Pseudify\Core\Processor\Processing\Pseudonymize\DataManipulatorContext;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
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
            ->addTable(table: 'wh_log', columns: [
                // ...
                Column::create(identifier: 'log_data', dataType: Column::DATA_TYPE_HEX)
                    ->addDataProcessing(dataProcessing: new DataProcessing(identifier: 'process logs',
                        processor: function (DataManipulatorContext $context): void {
                            $row = $context->getDatebaseRow();
                            if ('foo' === $row['log_type']) {
                                $encoder = new ChainedEncoder(encoders: [new Base64Encoder(), new JsonEncoder()]);
                            } else if ('bar' === $row['log_type']) {
                                $encoder = new SerializedEncoder();
                            } else {
                                return;
                            }

                            $logData = $encoder->decode(data: $context->getDecodedData());

                            // pseudonymize the data
                            // ... 
                        }
                    )),
            ])
            // ...
        ;

        return $tableDefinition;
    }
}
```

With the method [`addDataProcessing()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/Column.php#L184), further manual data transformations can be programmed in addition to the decoding of the data.  
The `DataProcessings` are executed after the decoding of the data.  
Any number of `DataProcessings` can be defined, which are processed one after the other.  

A [`DataProcessing`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/DataProcessing.php#L19) consists of a unique identification per database column (parameter `identifier`) and
an [anonymous function](https://www.php.net/manual/en/functions.anonymous.php) (parameter `processor`).  
The anonymous function is called with a parameter `context` of the type [`DataManipulatorContext`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Pseudonymize/DataManipulatorContext.php).  
The `DataManipulatorContext` can be used to obtain various information about the data set to be processed:

* `$context->getRawData()`: The original data of the database column.
* `$context->getDecodedData()`: The data of the column after decoding
* `$context->getDatebaseRow()`: Contains the original data of all columns of the row being processed
* `$context->getProcessedData()`: Contains the processed data set by [`$context->setProcessedData()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Pseudonymize/DataManipulatorContext.php#L84), otherwise the data of the database column after decoding.

In our example, we use the value of the database column `log_type` to determine how the data must be further decoded.  
If the value of `log_type` is `foo`, the data is decoded using the Base64Encoder() and then using the JsonEncoder().  
If the value of `log_type` is `bar`, the data is decoded using the SerializedEncoder().  
The data is now decoded and can then be pseudonymised.  

```shell
pseudify pseudify:debug:pseudonymize test-profile

Pseudonymization profile "test-profile"
=======================================

Pseudonymize data in this tables
--------------------------------

 ----------------- -------------------------- --------------- ------------------- 
  Table             column                     data decoders   data manipulators  
 ----------------- -------------------------- --------------- ------------------- 
  wh_user           username (string)          Scalar                             
  wh_user           password (string)          Scalar                             
  wh_user           first_name (string)        Scalar                             
  wh_user           last_name (string)         Scalar                             
  wh_user           email (string)             Scalar                             
  wh_user           city (string)              Scalar                             
  wh_log            ip (string)                Scalar                             
  wh_log            log_message (text)         Json                               
  wh_log            log_data (blob)            Hex             process logs       
  wh_user_session   session_data (blob)        Serialized                         
  wh_user_session   session_data_json (text)   Json                               
  wh_meta_data      meta_data (blob)           Hex<>GzEncode                      
 ----------------- -------------------------- --------------- -------------------
```

##### Fake scalar data (pseudonymise)

Pseudonymisation is implemented at column level using the method [`addDataProcessing()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/Column.php#L184).  
In the chapter [`differently encoded database columns`](#differently-encoded-database-columns) the `DataProcessings` have already been described.  
In a `DataProcessing` not only a possibly required extended decoding / encoding of the data takes place, but above all the pseudonymisation itself.  
Each defined database column (`Column::create()`) needs at least one `DataProcessing` so that pseudify knows how to pseudonymise the data in the database column.  

We start again with the scalar data structures that are easy to pseudonymise.  
To do this, you expand the defined database columns (`Column::create()`) in the profile.

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Faker\Provider\Person;
use Waldhacker\Pseudify\Core\Processor\Processing\DataProcessing;
use Waldhacker\Pseudify\Core\Processor\Processing\Pseudonymize\DataManipulatorPreset;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addTable(table: 'wh_user', columns: [
                Column::create(identifier: 'username')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'userName'
                    )),
                Column::create(identifier: 'password')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'argon2iPassword'
                    )),
                Column::create(identifier: 'first_name')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'firstName',
                        fakerArguments: [Person::GENDER_FEMALE]
                    )),
                Column::create(identifier: 'last_name')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'lastName'
                    )),
                Column::create(identifier: 'email')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'safeEmail'
                    )),
                Column::create(identifier: 'city')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'city'
                    )),
            ])
            // ...
        ;

        return $tableDefinition;
    }
}
```

The prepared `DataProcessing` [`DataManipulatorPreset::scalarData()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Pseudonymize/DataManipulatorPreset.php#L30) is passed to the method [`addDataProcessing()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/Column.php#L184).  

!!! info
    `DataManipulatorPreset::scalarData()` already implements everything necessary to pseudonymise simple data structures in the database.  

To the argument `fakerFormatter` you pass a formatter provided by [the FakerPHP/Faker component](https://fakerphp.github.io/) or [implemented by yourself](../setup/configuration.md#registering-custom-faker-formatters) e.g. [`email`](https://fakerphp.github.io/formatters/internet/#email).  
If a formatter supports [arguments, such as with the `firstName` formatter](https://github.com/FakerPHP/Faker/blob/v1.20.0/src/Faker/Generator.php#L454), these can be passed with the `fakerArguments` argument.  

An analysis of the profile now looks like this:

```shell
$ pseudify pseudify:debug:pseudonymize test-profile

Pseudonymization profile "test-profile"
=======================================

Pseudonymize data in this tables
--------------------------------

 ----------------- -------------------------- --------------- ----------------------------- 
  Table             column                     data decoders   data manipulators            
 ----------------- -------------------------- --------------- ----------------------------- 
  wh_user           username (string)          Scalar          autogenerated-63db8d8426b5f  
  wh_user           password (string)          Scalar          autogenerated-63db8d8426b64  
  wh_user           first_name (string)        Scalar          autogenerated-63db8d84270ab  
  wh_user           last_name (string)         Scalar          autogenerated-63db8d84270b0  
  wh_user           email (string)             Scalar          autogenerated-63db8d84270b1  
  wh_user           city (string)              Scalar          autogenerated-63db8d84270b2  
  wh_log            ip (string)                Scalar                                       
  wh_log            log_message (text)         Json                                         
  wh_log            log_data (blob)            Hex             foo logs                     
  wh_user_session   session_data (blob)        Serialized                                   
  wh_user_session   session_data_json (text)   Json                                         
  wh_meta_data      meta_data (blob)           Hex<>GzEncode                                
 ----------------- -------------------------- --------------- -----------------------------
```

Under `data manipulators` you can now see that `DataProcessings` have been modelled for some columns.  
The values (e.g. `autogenerated-63db8d8426b5f`) are automatically generated by pseudify.  
If you want to have human-readable identifiers listed for a better overview, you can pass the argument `processingIdentifier` to the method `DataManipulatorPreset::scalarData()`.  

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Faker\Provider\Person;
use Waldhacker\Pseudify\Core\Processor\Processing\DataProcessing;
use Waldhacker\Pseudify\Core\Processor\Processing\Pseudonymize\DataManipulatorPreset;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
{
    public function getIdentifier(): string
    {
        return 'test-profile';
    }

    public function getTableDefinition(): TableDefinition
    {
        $tableDefinition = new TableDefinition(identifier: $this->getIdentifier());

        $tableDefinition
            ->addTable(table: 'wh_user', columns: [
                Column::create(identifier: 'username')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'userName',
                        processingIdentifier: 'fake user names'
                    )),
                Column::create(identifier: 'password')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'argon2iPassword',
                        processingIdentifier: 'fake argon2i passwords'
                    )),
                Column::create(identifier: 'first_name')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'firstName',
                        fakerArguments: [Person::GENDER_FEMALE],
                        processingIdentifier: 'fake female first names'
                    )),
                Column::create(identifier: 'last_name')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'lastName',
                        processingIdentifier: 'fake last names'
                    )),
                Column::create(identifier: 'email')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'safeEmail',
                        processingIdentifier: 'fake safe email addresses'
                    )),
                Column::create(identifier: 'city')
                    ->addDataProcessing(dataProcessing: DataManipulatorPreset::scalarData(
                        fakerFormatter: 'city',
                        processingIdentifier: 'fake citiy names'
                    )),
            ])
            // ...
        ;

        return $tableDefinition;
    }
}
```

```shell
$ pseudify pseudify:debug:pseudonymize test-profile

Pseudonymization profile "test-profile"
=======================================

Pseudonymize data in this tables
--------------------------------

 ----------------- -------------------------- --------------- --------------------------- 
  Table             column                     data decoders   data manipulators          
 ----------------- -------------------------- --------------- --------------------------- 
  wh_user           username (string)          Scalar          fake user names            
  wh_user           password (string)          Scalar          fake argon2i passwords     
  wh_user           first_name (string)        Scalar          fake female first names    
  wh_user           last_name (string)         Scalar          fake last names            
  wh_user           email (string)             Scalar          fake safe email addresses  
  wh_user           city (string)              Scalar          fake citiy names           
  wh_log            ip (string)                Scalar                                     
  wh_log            log_message (text)         Json                                       
  wh_log            log_data (blob)            Hex             foo logs                   
  wh_user_session   session_data (blob)        Serialized                                 
  wh_user_session   session_data_json (text)   Json                                       
  wh_meta_data      meta_data (blob)           Hex<>GzEncode                              
 ----------------- -------------------------- --------------- ---------------------------
```

##### Fake non-scalar data (pseudonymise)

In complex data structures, one usually only wants to pseudonymise very specific properties.  
The data of the database column `session_data_json` in the table `wh_meta_data` are suitable as an example.  
This contains JSON strings such as `{"data":{"last_ip": "107.66.23.195"}}`.  
We now want to specifically replace the value of the `last_ip` property with fake data.  

For this we define a `DataProcessing` (`addDataProcessing()`) at the database column (`Column::create()`).  
In the chapter ["Differently encoded database columns"](#differently-encoded-database-columns) we already mentioned the [`DataManipulatorContext`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Pseudonymize/DataManipulatorContext.php), which is passed to the anonymous function of a `DataProcessing`.  

Various information about the data set to be processed can be obtained through the `DataManipulatorContext`:

* `$context->getRawData()`: The original data of the database column.
* `$context->getDecodedData()`: The data of the column after decoding.
* `$context->getDatebaseRow()`: Contains the original data of all columns of the row being processed.
* `$context->getProcessedData()`: Contains the processed data set by [`$context->setProcessedData()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Pseudonymize/DataManipulatorContext.php#L84), otherwise the data of the column after decoding.

By using the `DataManipulatorContext`, the data can also be pseudonymised and passed back to pseudify:  

With the method [`fake()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Pseudonymize/DataManipulatorContext.php#L40), the [FakerPHP/Faker component](https://fakerphp.github.io/) can be initialised to create pseudonyms.  
With the method [`setProcessedData()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Pseudonymize/DataManipulatorContext.php#L84) the pseudonymised data structure can be passed back to pseudify.  

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Processing\DataProcessing;
use Waldhacker\Pseudify\Core\Processor\Processing\Pseudonymize\DataManipulatorContext;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
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
            ->addTable(table: 'wh_user_session', columns: [
                // ...
                Column::create(identifier: 'session_data_json', dataType: Column::DATA_TYPE_JSON)
                    ->addDataProcessing(dataProcessing: new DataProcessing(identifier: 'fake IPv4',
                        processor: function (DataManipulatorContext $context): void {
                            $data = $context->getDecodedData();

                            $ip = $data['data']['last_ip'];
                            $fakeIp = $context->fake(source: $ip)->ipv4();
                            $data['data']['last_ip'] = $fakeIp;

                            $context->setProcessedData(processedData: $data);
                        }
                    )),
            ])
            // ...
        ;

        return $tableDefinition;
    }
}
```

With `$data = $context->getDecodedData();` we get the decoded JSON string in the form of an associative array.  
With `$ip = $data['data']['last_ip'];` we get the specific value from the data structure (the IP address) that we want to pseudonymise.  

The call `$context->fake(source: $ip);` initialises the faker.  
The argument `source` is passed the input value to be pseudonymised, in the example this is the IP address.  

This is followed by a call to a formatter provided by [the FakerPHP/Faker component](https://fakerphp.github.io/) or [self-implemented](../setup/configuration.md#registering-custom-faker-formatters), e.g. [`->ipv4()`](https://fakerphp.github.io/formatters/internet/#ipv4).  

!!! info
    If a formatter supports [arguments, such as with the `firstName` formatter](https://github.com/FakerPHP/Faker/blob/v1.20.0/src/Faker/Generator.php#L454), the arguments can be passed to the call (e.g. `->firstName(gender: \Faker\Provider\Person::GENDER_FEMALE)`).  

The pseudonymised data (`$fakeIp`) must then be written back into the data structure (`$data['data']['last_ip'] = $fakeIp;`).  
With `$context->setProcessedData(processedData: $data);` the now pseudonymised data structure is passed to pseudify.  
Pseudify will then encode the data structure again and write it back into the database.  

###### Faking (pseudonymising) different data formats in one database column

As already learned in the chapter ["Differently encoded database columns"](#differently-encoded-database-columns) and ["Searching differently encoded data"](analyze.md#search-differently-encoded-data), it can happen that data in database columns are stored in differently encoded form.  
It can also happen that data in columns are stored in different data formats.  
Conditions must be used to detect the data format in which the data is stored so that it can be pseudonymised accordingly.  

In our example, the plaintext data of the database column `log_data` of the table `wh_log` is serialised PHP data if the database column `log_type` contains the value `bar`.  
The plaintext data of the database column `log_data` is in JSON format if the database column `log_type` contains the value `foo`.  

If the database column `log_data` of the table `wh_log` contains the value `foo`, then the data structure looks like this:

```json
{"message":"foo text \"ronaldo15\", another \"mcclure.ofelia@example.com\""}
                       ^                      ^
                       username               e-mail address
```

If the database column `log_data` of the table `wh_log` contains the value `bar`, then the data structure looks like this:

```json
{"message":"bar text \"Block\", another \"georgiana59\""}
                       ^                  ^
                       last name          username
```

Using the data from the `DataManipulatorContext` we can now differentiate which data structure we are dealing with (`'foo' === $row['log_type']`) in order to pseudonymise it accordingly.  

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Waldhacker\Pseudify\Core\Processor\Processing\DataProcessing;
use Waldhacker\Pseudify\Core\Processor\Processing\Pseudonymize\DataManipulatorContext;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
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
            ->addTable(table: 'wh_log', columns: [
                // ...
                Column::create(identifier: 'log_message', dataType: Column::DATA_TYPE_JSON)
                    ->addDataProcessing(dataProcessing: new DataProcessing(identifier: 'process log messages',
                        processor: function (DataManipulatorContext $context): void {
                            $logMessage = $context->getProcessedData();
                            preg_match('/^.*(".*").*(".*")$/', $logMessage['message'], $matches);
                            array_shift($matches);

                            $row = $context->getDatebaseRow();
                            if ('foo' === $row['log_type']) {
                                $userName = trim($matches[0], '"');
                                $mail = trim($matches[1], '"');

                                $logMessage['message'] = strtr($logMessage['message'], [
                                    $matches[0] => sprintf('"%s"', $context->fake(source: $userName)->userName()),
                                    $matches[1] => sprintf('"%s"', $context->fake(source: $mail)->safeEmail()),
                                ]);
                            } else {
                                $lastName = trim($matches[0], '"');
                                $userName = trim($matches[1], '"');

                                $logMessage['message'] = strtr($logMessage['message'], [
                                    $matches[0] => sprintf('"%s"', $context->fake(source: $lastName)->lastName()),
                                    $matches[1] => sprintf('"%s"', $context->fake(source: $userName)->userName()),
                                ]);
                            }

                            $context->setProcessedData(processedData: $logMessage);
                        }
                    )),
                // ...
            ])
            // ...
        ;

        return $tableDefinition;
    }
}

```

With `$context->setProcessedData(processedData: $logMessage);` the pseudonymised data structure is passed to pseudify.  
Pseudify will now encode the data structure again and write it back into the database.  

##### Advanced application

###### Use different pseudonyms for the same input data

To preserve data integrity, pseudify is designed to always return the same pseudonym for the same input data during a pseudonymisation run.  
This means that during the execution of a pseudonymisation using `pseudify pseudify:pseudonymize test-profile`, all calls to `$context->fake(source: 'Stan')->userName()` will generate e.g. the pseudonym `Klaus` each time and not a different one each time.  
A new pseudonymisation of the original database using `pseudify pseudify:pseudonymize test-profile` will generate e.g. the pseudonym `Roger` for all calls to `$context->fake(source: 'Stan')->userName()` each time and not `Klaus` as in the first pseudonymisation run.  

This ensures two things:

1. Within a pseudonymisation run, the same original data is always replaced with the same pseudonym.
   (The value "Stan" from Table 1 and Table 2 is replaced with "Klaus" in Table 1 and Table 2).
2. Between different pseudonymisation runs, the same original data generate different pseudonyms.
   (In the first pseudonymisation, the value "Stan" from Table 1 and Table 2 is replaced with "Klaus" in both tables,
   in the second pseudonymisation, the value "Stan" from Table 1 and Table 2 is replaced with the value "Roger" in both tables, and so on).

If for some reason you want to change this behaviour, you can add the parameter `scope` to the method [`$context->fake()`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Processing/Pseudonymize/DataManipulatorContext.php#L40).  
With this parameter you can instruct pseudify to create different pseudonyms per `scope` for the same input data.  

Example:

`$context->fake(source: 'Stan')->userName()` returns `Greg`  
`$context->fake(source: 'Stan', scope: 'something_else')->userName()` returns `Terry`  
`$context->fake(source: 'Stan')->userName()` returns `Greg`  
`$context->fake(source: 'Stan', scope: 'something_else')->userName()` returns `Terry`  
`$context->fake(source: 'Terry')->userName()` returns `Brian`  
`$context->fake(source: 'Terry', scope: 'something_else')->userName()` returns `Lewis`  

!!! info
    Calls of `$context->fake()` create identical pseudonyms [based on the following variables](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Faker/Faker.php#L64):

    * same source data
    * same formatter
    * same formatter arguments
    * same `scope`
    * same [`APP_SECRET`](../setup/configuration.md#app_secret)

    This applies **within** one pseudonymisation run. A new pseudonymisation run will form other pseudonyms with the same variables.

###### The `onBeforeUpdateData()` life cycle method

Just before pseudify writes the pseudonymised data back to the database, [`the life cycle method is called`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/PseudonymizeProcessor.php#L147-L156).  
This can be [defined](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/Column.php#L224) at a database column (`Column::create()`).  

```php
<?php

declare(strict_types=1);

namespace Waldhacker\Pseudify\Profiles;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\Column as DoctrineColumn;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Column;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\TableDefinition;
use Waldhacker\Pseudify\Core\Profile\Model\Pseudonymize\Table;
use Waldhacker\Pseudify\Core\Profile\Pseudonymize\ProfileInterface;

class TestPseudonymizeProfile implements ProfileInterface
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
            ->addTable(table: 'wh_log', columns: [
                Column::create(identifier: 'log_message')
                    // ...
                    ->onBeforeUpdateData(onBeforeUpdateData: function (QueryBuilder $queryBuilder, Table $table, Column $column, DoctrineColumn $columnInfo, mixed $originalData, mixed $processedData, array $databaseRow): void {
                        // ...
                    }),
                // ...
            ])
            // ...
        ;

        return $tableDefinition;
    }
}
```

In this callback, you have access to all important objects and data in order to be able to carry out data manipulations in some wild scenarios:

* `$originalData`: The original data in the database, which will be replaced immediately.
* `$processedData`: The pseudonymised data, which will be written immediately.
* `$databaseRow`: The entire original data set
* `$queryBuilder`: The [`Query Builder` instance](https://github.com/doctrine/dbal/blob/3.6.x/src/Query/QueryBuilder.php) used to write the data
* `$table`: The [pseudify `table` instance](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/Table.php) modelled by you
* `$column`: The [pseudify `column` instance](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Profile/Model/Pseudonymize/Column.php) modelled by you
* `$columnInfo`: The [doctrine `Column` instance](https://github.com/doctrine/dbal/blob/3.6.x/src/Schema/Column.php) with useful technical information about the database column.

If you want to manipulate something before updating the data, this is possible using the `$queryBuilder` instance. Changing the other objects and data will have no effect.  

### Apply a "Pseudonymizer Profile"

A "Pseudonymizer Profile" can be executed with the command `pseudify:pseudonymize <profile-name>`.  
After the pseudonymisation, the word `done` is displayed.  

```shell
$ pseudify pseudify:pseudonymize test-profile

done
```

#### dry run

You can test your profile without overwriting the data in the database. With the parameter `--dry-run` all SQL statements are listed that pseudify would execute.

```shell
pseudify pseudify:pseudonymize test-profile --dry-run

 UPDATE `wh_user` SET `username` = :dcValue1:'tavares.satterfield' WHERE `username` = :dcValue2:'karl13'
 UPDATE `wh_user` SET `password` = :dcValue1:'$argon2i$v=19$m=8,t=1,p=1$WUlUVS9HOW1PN1dXd1pyeg$GTKopoFTAlCufz2QS/c/DzcaLKm/6xMo80ZNiph48Q4' WHERE `password` = :dcValue2:'$argon2i$v=19$m=8,t=1,p=1$amo3Z28zNTlwZG84TG1YZg$1Ka95oewxn3xs/jLrTN0R9lhIxtNnQynBFRdE/70cAQ'
 UPDATE `wh_user` SET `first_name` = :dcValue1:'Annalise' WHERE `first_name` = :dcValue2:'Jordyn'
 UPDATE `wh_user` SET `last_name` = :dcValue1:'Strosin' WHERE `last_name` = :dcValue2:'Shields'
 UPDATE `wh_user` SET `email` = :dcValue1:'ugutkowski@example.com' WHERE `email` = :dcValue2:'madaline30@example.net'
 UPDATE `wh_user` SET `city` = :dcValue1:'Schoenborough' WHERE `city` = :dcValue2:'Lake Tanner'
 UPDATE `wh_user` SET `username` = :dcValue1:'hollie.walter' WHERE `username` = :dcValue2:'reilly.chase'
 UPDATE `wh_user` SET `password` = :dcValue1:'$argon2i$v=19$m=8,t=1,p=1$S1ZHRS54Z1FsNzBMSE11Mg$40Wr1wY7Az1e/cLZA04Jd25dGUYa5Xwcx1hyVsJv6QI' WHERE `password` = :dcValue2:'$2y$04$O0XKmRw3wl9mni55dSEJXuj3vygjCgdyUviihec.PTiTAu2SS/C6u'
 UPDATE `wh_user` SET `first_name` = :dcValue1:'Daphnee' WHERE `first_name` = :dcValue2:'Keenan'
 UPDATE `wh_user` SET `last_name` = :dcValue1:'Dietrich' WHERE `last_name` = :dcValue2:'King'
 UPDATE `wh_user` SET `email` = :dcValue1:'felicia63@example.org' WHERE `email` = :dcValue2:'johns.percy@example.com'
 UPDATE `wh_user` SET `city` = :dcValue1:'Juddport' WHERE `city` = :dcValue2:'Edwardotown'
 UPDATE `wh_user` SET `username` = :dcValue1:'miller.ullrich' WHERE `username` = :dcValue2:'hpagac'
 UPDATE `wh_user` SET `password` = :dcValue1:'$argon2i$v=19$m=8,t=1,p=1$NHlRS2E2Y0E5Z2JlWEJTLg$Rn20zQq0a+RDa0+x3YfWmaQ27duZdQyHEF7RcnODPFk' WHERE `password` = :dcValue2:'$argon2i$v=19$m=8,t=1,p=1$QXNXbTRMZWxmenBRUzdwZQ$i6hntUDLa3ZFqmCG4FM0iPrpMp6d4D8XfrNBtyDmV9U'
 UPDATE `wh_user` SET `first_name` = :dcValue1:'Susanna' WHERE `first_name` = :dcValue2:'Donato'
 UPDATE `wh_user` SET `last_name` = :dcValue1:'O\'Kon' WHERE `last_name` = :dcValue2:'Keeling'
 UPDATE `wh_user` SET `email` = :dcValue1:'hjaskolski@example.com' WHERE `email` = :dcValue2:'mcclure.ofelia@example.com'
 UPDATE `wh_user` SET `city` = :dcValue1:'Teresachester' WHERE `city` = :dcValue2:'North Elenamouth'
 UPDATE `wh_user` SET `username` = :dcValue1:'caleigh.mayert' WHERE `username` = :dcValue2:'georgiana59'
 UPDATE `wh_user` SET `password` = :dcValue1:'$argon2i$v=19$m=8,t=1,p=1$TEVqLm5JQncyWVZubWExbg$juQQzt5GPMHodHYEii5LhFK1l7kQQB4twaTLi5WId+k' WHERE `password` = :dcValue2:'$argon2i$v=19$m=8,t=1,p=1$SUJJeWZGSGEwS2h2TEw5Ug$kCQm4/5DqnjXc/3SiXwimtTBvbDO9H0Ru1f5hkQvE/Q'
 UPDATE `wh_user` SET `first_name` = :dcValue1:'Kaya' WHERE `first_name` = :dcValue2:'Maybell'
 UPDATE `wh_user` SET `last_name` = :dcValue1:'Ullrich' WHERE `last_name` = :dcValue2:'Anderson'
 UPDATE `wh_user` SET `email` = :dcValue1:'meggie.stracke@example.com' WHERE `email` = :dcValue2:'cassin.bernadette@example.net'
 UPDATE `wh_user` SET `city` = :dcValue1:'Leschmouth' WHERE `city` = :dcValue2:'South Wilfordland'
 UPDATE `wh_user` SET `username` = :dcValue1:'smith.tianna' WHERE `username` = :dcValue2:'howell.damien'
 UPDATE `wh_user` SET `password` = :dcValue1:'$argon2i$v=19$m=8,t=1,p=1$UG5xNENBektsOTk3bmpyTw$BAj6FBUDYe2t6QnW14qC+5S22gsI0iVLWawob1YiXKo' WHERE `password` = :dcValue2:'$argon2i$v=19$m=8,t=1,p=1$ZldmOWd2TDJRb3FTNVpGNA$ORIwp6yekRx02mqM4WCTVhllgXpUpuFJZ1MmbYwAMXs'
 UPDATE `wh_user` SET `first_name` = :dcValue1:'Kelly' WHERE `first_name` = :dcValue2:'Mckayla'
 UPDATE `wh_user` SET `last_name` = :dcValue1:'Block' WHERE `last_name` = :dcValue2:'Stoltenberg'
 UPDATE `wh_user` SET `email` = :dcValue1:'shanny.gulgowski@example.org' WHERE `email` = :dcValue2:'conn.abigale@example.net'
 UPDATE `wh_user` SET `city` = :dcValue1:'Port Wilberfurt' WHERE `city` = :dcValue2:'Dorothyfort'
 UPDATE `wh_log` SET `ip` = :dcValue1:'973a:942c:b9c7:d128:f8d4:c4cf:3d16:d168' WHERE `ip` = :dcValue2:'1321:57fc:460b:d4d0:d83f:c200:4b:f1c8'
 UPDATE `wh_log` SET `log_message` = :dcValue1:'{"message":"foo text \\"jamison59\\", another \\"hjaskolski@example.com\\""}' WHERE `log_message` = :dcValue2:'{"message":"foo text \\"ronaldo15\\", another \\"mcclure.ofelia@example.com\\""}'
 UPDATE `wh_log` SET `ip` = :dcValue1:'187.165.144.16' WHERE `ip` = :dcValue2:'155.215.67.191'
 UPDATE `wh_log` SET `log_message` = :dcValue1:'{"message":"foo text \\"jerald.harber\\", another \\"sporer.tierra@example.org\\""}' WHERE `log_message` = :dcValue2:'{"message":"foo text \\"stark.judd\\", another \\"srowe@example.net\\""}'
 UPDATE `wh_log` SET `ip` = :dcValue1:'6f5a:517a:963e:86fd:d691:c2a9:fe4f:4ea7' WHERE `ip` = :dcValue2:'4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98'
 UPDATE `wh_log` SET `log_message` = :dcValue1:'{"message":"bar text \\"Volkman\\", another \\"bayer.casandra\\""}' WHERE `log_message` = :dcValue2:'{"message":"bar text \\"Tromp\\", another \\"freida.mante\\""}'
 UPDATE `wh_log` SET `ip` = :dcValue1:'237.29.53.144' WHERE `ip` = :dcValue2:'243.202.241.67'
 UPDATE `wh_log` SET `log_message` = :dcValue1:'{"message":"bar text \\"Ruecker\\", another \\"caleigh.mayert\\""}' WHERE `log_message` = :dcValue2:'{"message":"bar text \\"Block\\", another \\"georgiana59\\""}'
 UPDATE `wh_log` SET `ip` = :dcValue1:'253.3.54.48' WHERE `ip` = :dcValue2:'132.188.241.155'
 UPDATE `wh_log` SET `log_message` = :dcValue1:'{"message":"bar text \\"Braun\\", another \\"johann.thompson\\""}' WHERE `log_message` = :dcValue2:'{"message":"bar text \\"Homenick\\", another \\"cyril06\\""}'
 UPDATE `wh_user_session` SET `session_data` = :dcValue1:'a:1:{s:7:"last_ip";s:39:"6f5a:517a:963e:86fd:d691:c2a9:fe4f:4ea7";}' WHERE `session_data` = :dcValue2:'a:1:{s:7:"last_ip";s:38:"4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98";}'
 UPDATE `wh_user_session` SET `session_data_json` = :dcValue1:'{"data":{"last_ip":"6f5a:517a:963e:86fd:d691:c2a9:fe4f:4ea7"}}' WHERE `session_data_json` = :dcValue2:'{"data":{"last_ip":"4fb:1447:defb:9d47:a2e0:a36a:10d3:fd98"}}'
 UPDATE `wh_user_session` SET `session_data` = :dcValue1:'a:1:{s:7:"last_ip";s:13:"223.86.155.35";}' WHERE `session_data` = :dcValue2:'a:1:{s:7:"last_ip";s:13:"107.66.23.195";}'
 UPDATE `wh_user_session` SET `session_data_json` = :dcValue1:'{"data":{"last_ip":"223.86.155.35"}}' WHERE `session_data_json` = :dcValue2:'{"data":{"last_ip":"107.66.23.195"}}'
 UPDATE `wh_user_session` SET `session_data` = :dcValue1:'a:1:{s:7:"last_ip";s:13:"49.170.101.59";}' WHERE `session_data` = :dcValue2:'a:1:{s:7:"last_ip";s:13:"244.166.32.78";}'
 UPDATE `wh_user_session` SET `session_data_json` = :dcValue1:'{"data":{"last_ip":"49.170.101.59"}}' WHERE `session_data_json` = :dcValue2:'{"data":{"last_ip":"244.166.32.78"}}'
 UPDATE `wh_user_session` SET `session_data` = :dcValue1:'a:1:{s:7:"last_ip";s:39:"973a:942c:b9c7:d128:f8d4:c4cf:3d16:d168";}' WHERE `session_data` = :dcValue2:'a:1:{s:7:"last_ip";s:37:"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8";}'
 UPDATE `wh_user_session` SET `session_data_json` = :dcValue1:'{"data":{"last_ip":"973a:942c:b9c7:d128:f8d4:c4cf:3d16:d168"}}' WHERE `session_data_json` = :dcValue2:'{"data":{"last_ip":"1321:57fc:460b:d4d0:d83f:c200:4b:f1c8"}}'
 UPDATE `wh_user_session` SET `session_data` = :dcValue1:'a:1:{s:7:"last_ip";s:13:"78.176.79.162";}' WHERE `session_data` = :dcValue2:'a:1:{s:7:"last_ip";s:14:"197.110.248.18";}'
 UPDATE `wh_user_session` SET `session_data_json` = :dcValue1:'{"data":{"last_ip":"78.176.79.162"}}' WHERE `session_data_json` = :dcValue2:'{"data":{"last_ip":"197.110.248.18"}}'
 UPDATE `wh_meta_data` SET `meta_data` = :dcValue1:'1f8b08000000000000036551b14ec33010fd15e48181c1c44e429bcb0002d105211531b0118ec64dac3a7664bb6a23e8bf6387462d62bbe7f7eededd33420a5f0e32201b3130522214117320b226a584bc743007b275c26aec04099005d275d2b7d44bd41ac9afa447e776c6d6a324e045fa5e3e5c7af2a15f96c9d3d5fa9b4ecca4ac5a746de5875e1c47a06d8ce6b2fe27ec154aedc5de8f4c0a442ff232b9b9d5eef55a3f8e8f0990b5b4ce57d39a3320cf58a31a7444051085676c0ee45e99d5e6588b0ea58a350fb6ae0d670db4d9aac6ecdc46de893d76bd12d4d8266a42582be987d136f42e8df5176f527d0abbdedab0e261ca93c73cf99f3cd32940e19c34baaad18f01662c9c0f2c6a67c755653f5d9b1594cd12ca1246f322cc3fb348a3053b7d6136f6847a9e077d46194f2867f3d071f8013d717beaeb010000' WHERE `meta_data` = :dcValue2:'1f8b080000000000000365525d4f023110fc2fcd3d9a4a7bc7570951236234011340455fc8c215aea1d7d66b112e86ff6e7b7211e3db4e77ba33d32db0987d599630b4e525413d60dd80294322453dc19a3dcb3a0ced2c2f14e41c7948628632bde752e21472c115fae118b076af8b34c0ae1f1041b1d18a8ae8b34fba51deef5cb83eb9307d12bdcb347f7a4de96cf03859c6c3d9f8c5dc8f6fa2a7c9c3deb44abe9d1c1a34ff1825afb7b3974ccacddc3c9bddf0f19d8cf2e5dbfe6634b7958f33cd4506365bb8d2f093999376fa8f682408e5f8c1d551d4b0d96bb4ae949d5eaabbeab0c1d05a14d62deac46d8646ab2d9412aa6c0c4938eb12c2d0d469e9b85af262138e9a0cf11c840c35f56fbbd24a61588a0d487ecd0f901bc9b1e29587d016aeac270d74a15d56ae75e1bbc77a33346c86fed94c1c2e7864b9b542ab450aaeb297f821c048e0b64f4e85a9c3d224c1a4d5c231c5ed8e9f7f26110709f2fb1912741220711777086e605f78fef11bb387fddf33020000'
 UPDATE `wh_meta_data` SET `meta_data` = :dcValue1:'1f8b08000000000000036551cb4ec33010fc15e403070e26cea3259b0308442f08a9880337c2d2b88955c78e6c576d04fd776cd3a845dc763cb33bbb63840cbe2ce440367c64a44228034e81888654028acac20d90ade54661cf8987cc93b617aea34ea052487e25035abbd3a689128f17d97bf570e9c8877a59264f57eb6f3a3193b2eed076b51b077e1c81a6d52a15cd3fe1205128c7f72e321910b528aa6476abecebb57a8c8f0990b530d6d5d39a7320cfd8a01c5540251089676c01e45eead5e658f31e850c75ea6d6de7cf1a69bb95added98db8e37bec07c9a9366dd0f8b056c28dd1d6f72eb571176f427e72b3de1abfe261ca330d79a67ff2cca600b9b542abba411703cc993f1f58d0ce8fab8a61ba362f299b2794258c16a59f7f6691050b76fac23cf6e4b127cd67949519652c341d7e0050c4f2c6eb010000' WHERE `meta_data` = :dcValue2:'1f8b080000000000000365525b4fc23014fe2fcd1ecda4ddc6a5842851319a800920282fe4cc55d6d0b5752dc262f8efb693458c0f4d7a6edfa5a74023fa65684cd1965518f581f67c4c28e219ea739af40ded52b433ac945030e4421c5194ab3d1322cca0e04ca29f1e0dc6ec5599f9b0e7000228374a121e7c0e702f2806dd0b3bc0177a808395c88aa76546e6b78fd3341acd270b7d3f19064fd387bd6e576c3b3db448f1318e9737f3452ec4e6453febdde87185c745faba1f8e5f4cade38c739d83c9d7b6d2ec24e6c49dfd6bd402b8b4ec601b2b7294f45bed2b696697f2ae4eb6287ae7a5b1ebc67187a2f1db162a01b5378a049c5531a6686695b04ca6acdcf85442112b800b7f27ee6ddf949421a47c03825db303145ab050b25a832f735b3548b7aa5436afde55e9aac76633c46f86fcd94ce4075c6498315cc97506b696173b10a0d8f7764e4ab96ecc92380e71bb1d4624ec741dfe1945e429f0ef6788eb19e704773b218e9290443d7fdcc8f11b4093841d36020000'
 UPDATE `wh_meta_data` SET `meta_data` = :dcValue1:'1f8b08000000000000036591416fc3200c85ff0b937a8c0aa149ea5e769b2a6dc749532f99076e820a4914a8d6a8eb7f1f748dd669379efdc17bd808399c3d4860079a38db20ac9316c08c661b0372e3a10276f43476e88845c923acd09269dacce14463603fd080de7ff6a3be422b60bbf7b35b368b7e11e881b6876bf90eab5bf46d1da6816ef7716cfa4e18fd0f1c2c9a2ed0e96ac49731ced36eabbef04deab9b237a30ff51cb104f6829aecd425b50666f16ff7d5dad1a836a918941c1a9bcea200e6a8690c653e8ca80ef448277483a54cf52e11e9ef264cb3ed3379d5bafe18e25397798c228d51fc1b238fca93f7a6ef6a8d01d31b45cc82c0135bde529a2135f2187a5de6711d5228f858ab12341715ec2b2d4149b5875cf322d68a2a3adf99e7c99cffee54cedb10ab3c137299f1b2c8b888d8e5f20dabf0038ffd010000' WHERE `meta_data` = :dcValue2:'1f8b08000000000000036592dd6ea33010855f65657159116ca0818922f52fca6ea5d52a4bab46bd89066c821b302c769246ddbcfbda6ca246eae5f11cfb3be31984103e34444036e240c90421759a01919c4c2444130d0990ad16bdc246102b2905b2166dbf96a8304ec97f47875aefdb9e3b99daeb1ef6eb5631e9eda634f59a697265a6f4aa9b522f7b7e7c142faff36c3edb67ac624fb37dfcbcf636f78b261ac50f7fd4dbb21885995cee65639eee76f9c3aff47bf07b4bcbb8da2c76b3d1624871c15c55a8ab953974e214e6c4e65f8c5d8d5219f16e864a601b9bbffe28fee232e2e79352f6daaccecd8e81fcc4432eea7a680c488d1755fbf4ade2a2d7ad723206221a948395596f61b152f9b9fb3b2e8c1137e21d9bae16be124302fbed853487016d4959bb35d5b7175997366b8dca663a9e67c3dc6cd897d950abb4b09856ad381a744f5ddb1c08d479c7a7c0b27385d04a1a320af1b82c20ba0e72e0110f80276109050b02887228699158ee053a7468fab926d1790b58e0533f4e7c1ad92d381eff01fdaee8ae4c020000'
 UPDATE `wh_meta_data` SET `meta_data` = :dcValue1:'1f8b08000000000000036551cb4ec33010fc15e403070ea6cea349370710885e105211076e84a57113ab8e1dd98eda08faefd82111957adbf1cceeec8e1162f8b69000d9f3819102611570044454a4109016167220bde54661cb8987cc93b615aea14ea05248fe241d5a7bd0a61a251eafe38fe2f1da914ff5ba593cdfec7ee8cccccab241db946ee8f834024dad5524aa0b61275128c78f6e6462206a9d168be59db26fb7ea697c5c00d909635d39af990179c10ae5a0025a019178c6a6401ea4deeea79ab72864a8236f6b1b7fd640eb5ed6fa60f7e29e1fb1ed24a7dad441e3c3da0a378cb6be77a38dbb7a17f28b9b5d6ffc8aa739cf28e4195de43906c8ad155a9515ba31c084f9f381056d36ad2abaf9da2ca72c5bd26c45d9d2cf3c9d59c4c182fd7f6132ff104b539ae7344e7d8fd79f7e01b01c72f9e9010000' WHERE `meta_data` = :dcValue2:'1f8b08000000000000036552616bc23010fd2fa11fa5336dd51a299bcc3926e8409d3abfc8d5461b4c93ac89d332fcef4b3bcb043f047277efde7b970b109ffc68121074a005463d20dd32f6086209ea31d2ea69121274d4341790516443ec1394ca13e5dc4d206354a03f8c02ad4f324fcab06b091cc8f75278ccf98e70d7c9a2b06122dc501176d63cc9de9789371f8ca6b13f9c4f16ea75d277dea76f27d52ee8617a6e7ad9d738583ecf1729e7fb95fa50c7e1688dc759fc79ea8f57baf271a3b94941a71b53287a3573d54eee808a0313869e4d3d8a18b67acdf6a3d0b307f152259b04ed58aecda69eb843d0787b808243351b411c6eaa18133433921b2a629aefcb548b209a01e3e5ddb36fbb9542b810b33d70fa44cf90294e5d412b0f659999a2661ac85c9ab4d8c9dc562ff566bc7233dedd66b08d34d59a49b149c054f6029b03824b6ce7ea94a98add52e16ec7c5b8e97a41e8e2d00adc68f8a506feff0d41dd14fa166f0f6edbde96edb8fc020ca4e19b36020000'
 UPDATE `wh_meta_data` SET `meta_data` = :dcValue1:'1f8b080000000000000365514d4fc3300cfd2f06895bb4a42ba5ee85cb8410425cb8200e95d5863534fd509cc12ab4ff4ed2ad6268a7f8c9cfcf7e2f8409fe30ae115a3d492808f3881582a9a13098148c77083bd6aea74e438032903b63ad766267ad33550347d248ccdf83ab23bc4578ebdaebcd669e386b960d7153fa699cb5320472dba157e682375a32bdd77b7f527fafbf1eeaabede34c5c217c18c7be5c8e4a119e0dfb29d63982a5ffbd979ba7a13fd5ba236363ad82cbe693b81d2cb7e65eefa91bad16d5d0c56e705999a3a04c105eb5d34c55a3d96b07c561c94cc5ccd445663220d6cc66e8cb9a3ccd8a32b84519b9d9e944332efaeb5cc86c25e44a8a340ffa672b92b842fe7dd37a9991a9506922940a6f16060ebfc7a9a250ce010000' WHERE `meta_data` = :dcValue2:'1f8b080000000000000365915f4fc23014c5bf8aa97b240bedc6c64a480c22c428448828c10772dd0a6b5cd7ba967f1abebbedc448c25b4feeb9f7776e2fd0807e6b1a52f4c10e187580264e138a78863a9c061d4ddb146d34ab4a100c591951942b58438a7e6b0ab4dec92a7332b18d1e546b5912ee6dbb38f144b7dd305ddc505dec4de6e3f9fbf374b478dd0b56f6a6b3af6cb798783cca4b33eb3f42b0187c8adb61381835f953a5462acac27e7bbeaac63d73e88b9764e618f88cb9cc41e74b735075b298a213fbc2a70ae0a5617b730afd966d87d9f5fabe3636295af14a9be5d98a7d598291f54e14157056b49407c60a5eae9d6a51c404f0c2bd89ed13695a6c2ae6cb95b5c00ddb835005f353299cc3fe73cacda1a65af3585626bfba2b989d2d3726479de3df2d88bb05b9b805b64a33adb92c971918a88762bb37c5ce1b9fb2725523028a4818fa388afc80f871dbce3f43040e81ffcf1fa2138004894f62bf15fbd8c6381e7f00628e8a6d25020000'
```

!!! note
    The original data and the pseudonymised data are not shown one-to-one.  
    Valid SQL of the first line should be `UPDATE wh_user SET username = 'tavares.satterfield' WHERE username = 'karl13'`.  
    However, `--dry-run` additionally lists the name of the [internally used placeholder](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/query-builder.html#binding-parameters-to-placeholders) (e.g. `:dcValue1:`, `:dcValue2:` ...) before the data, which can simplify debugging.  

