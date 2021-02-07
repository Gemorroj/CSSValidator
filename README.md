# Port PEAR package [CSSValidator](http://pear.php.net/package/Services_W3C_CSSValidator)

[![Continuous Integration](https://github.com/Gemorroj/CSSValidator/workflows/Continuous%20Integration/badge.svg?branch=master)](https://github.com/Gemorroj/CSSValidator/actions?query=workflow%3A%22Continuous+Integration%22)

### Requirements:

- PHP >= 7.3

### Installation:
```bash
composer require gemorroj/cssvalidator
```

### Example:

```php
<?php
use CSSValidator\CSSValidator;

$validator = new CSSValidator();
$result = $validator->validateFragment('#css-code { background: green; }');
$result = $validator->validateFile('/path/to/file.css');
$result = $validator->validateUri('http://example.com/style.css');
$result = $validator->validateUri('http://example.com'); // extract and validate all CSS files on that page

echo $result->getCssLevel();
var_dump($result->isValid());

print_r($result->getErrors());
print_r($result->getWarnings());
```
