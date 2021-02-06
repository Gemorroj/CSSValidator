<?php
namespace CSSValidator\Tests;

use PHPUnit\Framework\TestCase;

class CSSValidatorTest extends TestCase
{
    public function testValidCSS(): void
    {
        $css = '#valid-css-test { background: green; }';
        $validator = new \CSSValidator\CSSValidator();
        $result = $validator->validateFragment($css);
        $this->assertEquals(0, count($result->getErrors()));
    }

    public function testInvalidCSS(): void
    {
        $css = '#invalid-css-test!!!ing { bac#%^&kground: green; }';
        $validator = new \CSSValidator\CSSValidator();
        $result = $validator->validateFragment($css);
        $this->assertNotEquals(0, count($result->getErrors()));
    }
}
