# Komplexe Datentypen

## PHP - serialisierte Daten verarbeiten

PHP Datenstrukturen lassen sich mittels der Funktion [`serialize()`](https://www.php.net/manual/de/function.serialize.php) in eine speicherbare Repräsentation transformieren.  

Ein PHP Array kann serialisiert werden, um es z.B. in der Datenbank abzuspeichern.

```php
$array = [
    'key1' => 'value1',
    'key2' => 'value2',
];

echo serialize($array);

// output: a:2:{s:4:"key1";s:6:"value1";s:4:"key2";s:6:"value2";}
```

Diese Datenstruktur kann später mit der Funktion [`unserialize()`](https://www.php.net/manual/de/function.unserialize.php) wieder in eine PHP Datenstruktur umgewandelt werden.    
Eine Beschreibung des Datenformats findet man [im PHP Quellcode](https://github.com/php/php-src/blob/7936c8085e7521252214cad295775794dc7be25c/ext/standard/var.c#L992) und teilweise in der [PHP Dokumentation](https://www.phpinternalsbook.com/php5/classes_objects/serialization.html).

Während die Arbeit mit einfachen Datenstrukturen wie Strings oder Arrays für pseudify recht einfach wäre, so wird die Arbeit mit serialisierten PHP Objekten schwieriger.  

```php
use Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject;
echo serialize(new SimpleObject('baz1', 'baz2', 'baz3'));

// output: O:86:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject":3:{s:101:"\x00Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject\x00privateMember";s:4:"baz1";s:18:"*protectedMember";s:4:"baz2";s:12:"publicMember";s:4:"baz3";}`
```

Ein serialisiertes PHP Objekt `O:86:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject":3:{s:101:"\x00Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject\x00privateMember";s:4:"baz1";s:18:"*protectedMember";s:4:"baz2";s:12:"publicMember";s:4:"baz3";}` lässt sich von PHP nur de-serialisieren, wenn
der [PHP autoloader](https://www.php.net/manual/de/language.oop5.autoload.php) Zugriff auf die entsprechenden Quellcodedateien hat, in welcher das Objekt definiert ist.  
Pseudify muss aber ohne den Quellcode irgendwelcher Applikationen lauffähig sein.  

Wie können wir nun gezielt z.B. den Wert der Eigenschaft `publicMember` (`baz3`) pseudonymisieren, ohne fehleranfällige Suchen-und-Ersetzen Strategien auf den Text anwenden zu müssen (z.B. mit wilden regulären Ausdrücken)?  

**Für diesen Anwendungsfall bietet dir pseudify den [`SerializedEncoder`](https://github.com/waldhacker/pseudify-core/blob/0.0.1/src/src/Processor/Encoder/SerializedEncoder.php)!**  

Mit dem `SerializedEncoder` ist es möglich eine serialisierte Datenstruktur in einen [AST](https://de.wikipedia.org/wiki/Syntaxbaum#Abstrakte_Syntaxb%C3%A4ume) umzuwandeln, diesen zu manipulieren und den AST dann wieder in eine serialisierte Datenstruktur zurückzuschreiben.  

### Lasst uns ein paar Beispiele anschauen

#### Integer

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = 1;

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: i:1;

serialized data AST:

IntegerNode {
  -content: 1
}
```

#### Float

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = 1.1;

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: d:1.1000000000000001;

serialized data AST:

FloatNode {
  -content: 1.1
}
```

#### Boolean

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = true;

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: b:1;

serialized data AST:

BooleanNode {
  -content: true
}
```

#### NULL

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = null;

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: N;

serialized data AST:

NullNode {
}
```

#### String

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = 'How nice is this!';

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: s:17:"How nice is this!";

serialized data AST:

StringNode {
  -content: "How nice is this!"
}
```

#### Arrays

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = ['how', 'nice'];

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: a:2:{i:0;s:3:"how";i:1;s:4:"nice";}

serialized data AST:

ArrayNode {
  -properties: array:2 [
    0 => ArrayElementNode {
      -content: StringNode {
        -content: "how"
      }
      -key: IntegerNode {
        -content: 0
      }
    }
    1 => ArrayElementNode {
      -content: StringNode {
        #parentNode: ArrayElementNode {}
        -content: "nice"
      }
      -key: IntegerNode {
        -content: 1
      }
    }
  ]
}
```

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = ['key1' => 'how', 'key2' => 'nice'];

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: a:2:{s:4:"key1";s:3:"how";s:4:"key2";s:4:"nice";}

serialized data AST:

ArrayNode {
  -properties: array:2 [
    "key1" => ArrayElementNode {
      -content: StringNode {
        -content: "how"
      }
      -key: StringNode {
        -content: "key1"
      }
    }
    "key2" => ArrayElementNode {
      -content: StringNode {
        -content: "nice"
      }
      -key: StringNode {
        -content: "key2"
      }
    }
  ]
}
```

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = ['key1' => 'how', 'nice', null, 99 => 123];

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: a:4:{s:4:"key1";s:3:"how";i:0;s:4:"nice";i:1;N;i:99;i:123;}

serialized data AST:

ArrayNode {
  -properties: array:4 [
    "key1" => ArrayElementNode {
      -content: StringNode {
        -content: "how"
      }
      -key: StringNode {
        -content: "key1"
      }
    }
    0 => ArrayElementNode {
      -content: StringNode {
        -content: "nice"
      }
      -key: IntegerNode {
        -content: 0
      }
    }
    1 => ArrayElementNode {
      -content: NullNode {
      }
      -key: IntegerNode {
        -content: 1
      }
    }
    99 => ArrayElementNode {
      -content: IntegerNode {
        -content: 123
      }
      -key: IntegerNode {
        -content: 99
      }
    }
  ]
}
```

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = ['key1' => 'how', 'nice', 'key2' => ['is', 'this']];

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
ArrayNode {
  -properties: array:3 [
    "key1" => ArrayElementNode {
      -content: StringNode {
        -content: "how"
      }
      -key: StringNode {
        -content: "key1"
      }
    }
    0 => ArrayElementNode {
      -content: StringNode {
        -content: "nice"
      }
      -key: IntegerNode {
        -content: 0
      }
    }
    "key2" => ArrayElementNode {
      -content: ArrayNode {
        -properties: array:2 [
          0 => ArrayElementNode {
            -content: StringNode {
              -content: "is"
            }
            -key: IntegerNode {
              -content: 0
            }
          }
          1 => ArrayElementNode {
            -content: StringNode {
              -content: "this"
            }
            -key: IntegerNode {
              -content: 1
            }
          }
        ]
      }
      -key: StringNode {
        -content: "key2"
      }
    }
  ]
}
```

#### Objekte

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = new \Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject('baz1', 'baz2', 'baz3');

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: O:86:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject":3:{s:101:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObjectprivateMember";s:4:"baz1";s:18:"*protectedMember";s:4:"baz2";s:12:"publicMember";s:4:"baz3";}

serialized data AST:

ObjectNode {
  -properties: array:3 [
    "privateMember" => AttributeNode {
      -content: StringNode {
        -content: "baz1"
      }
      -propertyName: "privateMember"
      -scope: "private"
      -className: "Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject"
    }
    "protectedMember" => AttributeNode {
      -content: StringNode {
        -content: "baz2"
      }
      -propertyName: "protectedMember"
      -scope: "protected"
      -className: "*"
    }
    "publicMember" => AttributeNode {
      -content: StringNode {
        -content: "baz3"
      }
      -propertyName: "publicMember"
      -scope: "public"
      -className: null
    }
  ]
  -className: "Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject"
}
```

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$data = new \Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject(null, null, ['key1' => 'value1']);

$serializedData = serialize($data);
$encoder = new SerializedEncoder();
$serializedDataAST = $encoder->decode(data: $serializedData);

echo 'serialized data: ' . $serializedData . PHP_EOL . PHP_EOL;
echo 'serialized data AST:' . PHP_EOL . PHP_EOL;

dump($serializedDataAST);
```

```shell
serialized data: O:86:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject":3:{s:101:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObjectprivateMember";N;s:18:"*protectedMember";N;s:12:"publicMember";a:1:{s:4:"key1";s:6:"value1";}}

serialized data AST:

ObjectNode {
  -properties: array:3 [
    "privateMember" => AttributeNode {
      -content: NullNode {
      }
      -propertyName: "privateMember"
      -scope: "private"
      -className: "Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject"
    }
    "protectedMember" => AttributeNode {
      -content: NullNode {
      }
      -propertyName: "protectedMember"
      -scope: "protected"
      -className: "*"
    }
    "publicMember" => AttributeNode {
      -content: ArrayNode {
        -properties: array:1 [
          "key1" => ArrayElementNode {
            -content: StringNode {
              -content: "value1"
            }
            -key: StringNode {
              -content: "key1"
            }
          }
        ]
      }
      -propertyName: "publicMember"
      -scope: "public"
      -className: null
    }
  ]
  -className: "Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject"
}
```

### Daten erzeugen / manipulieren

!!! info
    Die verfügbaren Methoden der einzelnen Node-Implementierungen kannst du dir [im Repository anschauen](https://github.com/waldhacker/pseudify-core/tree/0.0.1/src/src/Processor/Encoder/Serialized/Node).  

#### Skalare Werte

Skalare Werte zu erzeugen, ist einfach. Es muss nur eine neue Instanz des entsprechenden Datentyps erzeugt werden.

##### Integer

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$encoder = new SerializedEncoder();

$data = 1;
$node = $encoder->decode(data: serialize($data));

echo 'get the node value: ' . var_export($node->getValue(), true) . PHP_EOL;
```

```shell
get the node value: 1
```

##### Float

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$encoder = new SerializedEncoder();

$data = 123.321;
$node = $encoder->decode(data: serialize($data));

echo 'get the node value: ' . var_export($node->getValue(), true) . PHP_EOL;
```

```shell
get the node value: 123.321
```

##### Boolean

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$encoder = new SerializedEncoder();

$data = true;
$node = $encoder->decode(data: serialize($data));

echo 'get the node value: ' . var_export($node->getValue(), true) . PHP_EOL;
```

```shell
get the node value: true
```

##### Null

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$encoder = new SerializedEncoder();

$data = null;
$node = $encoder->decode(data: serialize($data));

echo 'get the node value: ' . var_export($node->getValue(), true) . PHP_EOL;
```

```shell
get the node value: NULL
```

##### String

```php
<?php

use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$encoder = new SerializedEncoder();

$data = 'how nice';
$node = $encoder->decode(data: serialize($data));

echo 'get the node value: ' . var_export($node->getValue(), true) . PHP_EOL;
```

```shell
get the node value: 'how nice'
```

#### Arrays

Das Array sieht so aus:

```php
[
    0 => 'value1',
    'key2' => 'value2',
    'key3' => [
        0 => 'value3',
        'key4' => 'value4'
    ]
];
```

##### get the node value for array key 0

```
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$encoder = new SerializedEncoder();

$data = [0 => 'value1', 'key2' => 'value2', 'key3' => [0 => 'value3', 'key4' => 'value4']];
$node = $encoder->decode(data: serialize($data));

echo PHP_EOL;

// get the node value for array key 0
$value = $node->getPropertyContent(identifier: 0)->getValue();
echo var_export($value, true) . PHP_EOL;
```

```shell
'value1'
```

##### get the node value for array key 'key2'

```
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$encoder = new SerializedEncoder();

$data = [0 => 'value1', 'key2' => 'value2', 'key3' => [0 => 'value3', 'key4' => 'value4']];
$node = $encoder->decode(data: serialize($data));

echo PHP_EOL;

// get the node value for array key 'key2'
$value = $node->getPropertyContent(identifier: 'key2')->getValue();
echo var_export($value, true) . PHP_EOL;
```

```shell
'value2'
```

##### get all array keys of the first array level

```
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\Serialized\Node\ArrayElementNode;

$encoder = new SerializedEncoder();

$data = [0 => 'value1', 'key2' => 'value2', 'key3' => [0 => 'value3', 'key4' => 'value4']];
$node = $encoder->decode(data: serialize($data));

echo PHP_EOL;

// get all array keys of the first array level
$value = array_map(fn(ArrayElementNode $elementNode): string|int => $elementNode->getPropertyName(), $node->getContent());
echo var_export($value, true) . PHP_EOL;
```

```shell
array (
  0 => 0,
  'key2' => 'key2',
  'key3' => 'key3',
)
```

##### replace 'value1' (key 0) with 'new value'

```
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$encoder = new SerializedEncoder();

$data = [0 => 'value1', 'key2' => 'value2', 'key3' => [0 => 'value3', 'key4' => 'value4']];
$node = $encoder->decode(data: serialize($data));
$originalSerializedData = $encoder->encode(data: $node);

echo PHP_EOL;

// replace 'value1' (key 0) with 'new value'
$newValue = 'new value';
$newValueNode = $encoder->decode(data: serialize($newValue));

$node->replaceProperty(identifier: 0, property: $newValueNode);

$newSerializedData = $encoder->encode(data: $node);

echo 'original data: ' . $originalSerializedData . PHP_EOL;
echo 'new data: ' . $newSerializedData . PHP_EOL;
```

```shell
original data: a:3:{i:0;s:6:"value1";s:4:"key2";s:6:"value2";s:4:"key3";a:2:{i:0;s:6:"value3";s:4:"key4";s:6:"value4";}}
new data:      a:3:{i:0;s:9:"new value";s:4:"key2";s:6:"value2";s:4:"key3";a:2:{i:0;s:6:"value3";s:4:"key4";s:6:"value4";}}
```

##### replace 'value4' (key 'key3' => 'key4') with 'newer value'

```
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;

$encoder = new SerializedEncoder();

$data = [0 => 'value1', 'key2' => 'value2', 'key3' => [0 => 'value3', 'key4' => 'value4']];
$node = $encoder->decode(data: serialize($data));
$originalSerializedData = $encoder->encode(data: $node);

echo PHP_EOL;

// replace 'value4' (key 'key3' => 'key4') with 'newer value'
$newValue = 'newer value';
$newValueNode = $encoder->decode(data: serialize($newValue));

$node->getPropertyContent(identifier: 'key3')->replaceProperty(identifier: 'key4', property: $newValueNode);

$newSerializedData = $encoder->encode(data: $node);

echo 'original data: ' . $originalSerializedData . PHP_EOL;
echo 'new data: ' . $newSerializedData . PHP_EOL;
```

```shell
original data: a:3:{i:0;s:6:"value1";s:4:"key2";s:6:"value2";s:4:"key3";a:2:{i:0;s:6:"value3";s:4:"key4";s:6:"value4";}}
new data:      a:3:{i:0;s:6:"value1";s:4:"key2";s:6:"value2";s:4:"key3";a:2:{i:0;s:6:"value3";s:4:"key4";s:11:"newer value";}}
```

#### Objekte

Das Objekt sieht so aus:

```php
class SimpleObject
{
    private $privateMember;
    protected $protectedMember;
    public $publicMember;

    public function __construct($privateMember, $protectedMember, $publicMember)
    {
        $this->privateMember = $privateMember;
        $this->protectedMember = $protectedMember;
        $this->publicMember = $publicMember;
    }
}

```

##### get the node value for class member 'privateMember' ('value1')

```php
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;
use Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject;

$encoder = new SerializedEncoder();

$data = new SimpleObject('value1', 'value2', 'value3');
$node = $encoder->decode(data: serialize($data));

echo PHP_EOL;

// get the node value for class member 'privateMember' ('value1')
$value = $node->getPropertyContent(identifier: 'privateMember')->getValue();
echo var_export($value, true) . PHP_EOL;
```

```shell
'value1'
```

##### get the node value for class member 'protectedMember' ('value2')

```php
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;
use Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject;

$encoder = new SerializedEncoder();

$data = new SimpleObject('value1', 'value2', 'value3');
$node = $encoder->decode(data: serialize($data));

echo PHP_EOL;

// get the node value for class member 'protectedMember' ('value2)
$value = $node->getPropertyContent(identifier: 'protectedMember')->getValue();
echo var_export($value, true) . PHP_EOL;
```

```shell
'value2'
```

##### get the node value for class member 'publicMember' ('value3')

```php
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;
use Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject;

$encoder = new SerializedEncoder();

$data = new SimpleObject('value1', 'value2', 'value3');
$node = $encoder->decode(data: serialize($data));

echo PHP_EOL;

// get the node value for class member 'publicMember' ('value3)
$value = $node->getPropertyContent(identifier: 'publicMember')->getValue();
echo var_export($value, true) . PHP_EOL;
```

```shell
'value3'
```

##### get all (direct) class member names

```
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;
use Waldhacker\Pseudify\Core\Processor\Encoder\Serialized\Node\AttributeNode;
use Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject;

$encoder = new SerializedEncoder();

$data = new SimpleObject('value1', 'value2', 'value3');
$node = $encoder->decode(data: serialize($data));

echo PHP_EOL;

// get all (direct) class member names
$value = array_map(fn(AttributeNode $attributeNode): string => $attributeNode->getPropertyName(), $node->getContent());
echo var_export($value, true) . PHP_EOL;
```

```shell
array (
  'privateMember' => 'privateMember',
  'protectedMember' => 'protectedMember',
  'publicMember' => 'publicMember',
)
```

##### replace 'value3' ('publicMember') with 'newer value'

```php
<?php
use Waldhacker\Pseudify\Core\Processor\Encoder\SerializedEncoder;
use Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject;

$encoder = new SerializedEncoder();

$data = new SimpleObject('value1', 'value2', 'value3');
$node = $encoder->decode(data: serialize($data));
$originalSerializedData = $encoder->encode(data: $node);

echo PHP_EOL;

// replace 'value3' ('publicMember') with 'newer value'
$newValue = 'newer value';
$newValueNode = $encoder->decode(data: serialize($newValue));

$node->replaceProperty(identifier: 'publicMember', property: $newValueNode);

$newSerializedData = $encoder->encode(data: $node);

echo 'original data: ' . $originalSerializedData . PHP_EOL;
echo 'new data: ' . $newSerializedData . PHP_EOL;
```

```shell
original data: O:86:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject":3:{s:101:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObjectprivateMember";s:6:"value1";s:18:"*protectedMember";s:6:"value2";s:12:"publicMember";s:6:"value3";}
new data:      O:86:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObject":3:{s:101:"Waldhacker\Pseudify\Core\Tests\Unit\Processor\Encoder\Serialized\Fixtures\SimpleObjectprivateMember";s:6:"value1";s:18:"*protectedMember";s:6:"value2";s:12:"publicMember";s:11:"newer value";}
```

