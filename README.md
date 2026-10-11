# Port PEAR package [CSSValidator](http://pear.php.net/package/Services_W3C_CSSValidator)

[![Continuous Integration](https://github.com/Gemorroj/CSSValidator/workflows/Continuous%20Integration/badge.svg)](https://github.com/Gemorroj/CSSValidator/actions?query=workflow%3A%22Continuous+Integration%22)

### Requirements:

- PHP >= 8.2

### Installation:
```bash
composer require gemorroj/cssvalidator
```

### Example:

```php
<?php
use CSSValidator\CSSValidator;
use CSSValidator\Options\Options;
use CSSValidator\Options\ProfileType;
use CSSValidator\Options\UserMediumType;
use CSSValidator\Options\WarningType;

$options = (new Options())
    ->setProfile(ProfileType::Css21) // or a string, e.g. 'css21'
    ->setWarning(WarningType::All)
    ->setUserMedium(UserMediumType::Screen)
    ->setLang('en');

$validator = new CSSValidator($options);
$result = $validator->validateFragment('#css-code { background: green; }');
$result = $validator->validateFile('/path/to/file.css');
$result = $validator->validateUri('http://example.com/style.css');
$result = $validator->validateUri('http://example.com'); // extract and validate all CSS files on that page

echo $result->getCssLevel();
var_dump($result->isValid());

print_r($result->getErrors());
print_r($result->getWarnings());
```
