#Financial number manipulation in PHP
The aim of this package is to facilitate the manipulation and calculs on float numbers without errors.

##Class Number
This is an abstract class for all math numbers. This class preserve the original value from what the number is created.
Numbers can be compared : `lt, le, ge, ge, equals` to other numbers (implementing Number class) or PHP Scalar numbers.

##Class IntegerNumber
Represents an integer number. You can create new integers from strings, float and pure integer values.
NB: conversion from float to integer is used with round :
- `1.2` ==> `1`
- `1.5` ==> `1`
- `1.6` ==> `2`

####Examples
```php
$ints[] = new Enimiste\Math\VO\IntegerNumber(1);

$ints[] = new Enimiste\Math\VO\IntegerNumber(1.0);

$ints[] = new Enimiste\Math\VO\IntegerNumber('1');

$ints[] = new Enimiste\Math\VO\IntegerNumber(1.39);

$ints[] = new Enimiste\Math\VO\IntegerNumber(1.7);

$ints[] = new Enimiste\Math\VO\IntegerNumber(-3);
```

The code below :
```php
foreach($ints as $x) { 
    echo $x->getValue(); 
    echo $x->getOrigin(); 
    echo $x->__toString(); 
}
```

will output the results :
- `1` | `1` | `"1"`
- `1` | `1.0` | `"1"`
- `1` | `"1"` | `"1"`
- `1` | `1.39` | `"1"`
- `2` | `1.7` | `"2"`
- `-3` | `-3` | `"-3"`

