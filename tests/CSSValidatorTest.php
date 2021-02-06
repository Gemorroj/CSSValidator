<?php

namespace CSSValidator\Tests;

use CSSValidator\CSSValidator;
use PHPUnit\Framework\TestCase;

class CSSValidatorTest extends TestCase
{
    public function testValidCSS(): void
    {
        $css = '#valid-css-test { background: green; }';
        $validator = new CSSValidator();
        $result = $validator->validateFragment($css);
        self::assertEmpty($result->getErrors());
    }

    public function testInvalidCSS(): void
    {
        $css = '#invalid-css-test!!!ing { bac#%^&kground: green; }';
        $validator = new CSSValidator();
        $result = $validator->validateFragment($css);
        self::assertNotEmpty($result->getErrors());
    }
}
