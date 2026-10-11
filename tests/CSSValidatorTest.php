<?php

namespace CSSValidator\Tests;

use CSSValidator\CSSValidator;
use CSSValidator\Exception;
use CSSValidator\Options\ProfileType;
use PHPUnit\Framework\TestCase;

class CSSValidatorTest extends TestCase
{
    /**
     * Pinned to the 3.5.0 tag: the validator fetches the URL server-side and needs a "text/css" response.
     */
    private const FIXTURES_URI = 'https://cdn.jsdelivr.net/gh/Gemorroj/CSSValidator@3.5.0/tests/fixtures/';

    public function testValidCSSUri(): void
    {
        $result = (new CSSValidator())->validateUri(self::FIXTURES_URI.'valid.css');

        self::assertEmpty($result->getErrors());
        self::assertEmpty($result->getWarnings());
        self::assertTrue($result->isValid());
        self::assertSame(ProfileType::Css3->value, $result->getCssLevel());
    }

    public function testWarningsCSSUri(): void
    {
        $result = (new CSSValidator())->validateUri(self::FIXTURES_URI.'warnings.css');

        self::assertEmpty($result->getErrors());
        self::assertNotEmpty($result->getWarnings());
        self::assertTrue($result->isValid());
        self::assertSame(ProfileType::Css3->value, $result->getCssLevel());
    }

    public function testInvalidCSSUriThrowsException(): void
    {
        $this->expectException(Exception::class);

        (new CSSValidator())->validateUri('data:text/css,%23x%7Bbackground%3Agreen%7D');
    }

    public function testValidCSSFile(): void
    {
        $validator = new CSSValidator();
        $result = $validator->validateFile(__DIR__.'/fixtures/valid.css');
        self::assertEmpty($result->getErrors());
        self::assertEmpty($result->getWarnings());
        self::assertTrue($result->isValid());
        self::assertSame(ProfileType::Css3->value, $result->getCssLevel());
    }

    public function testValidateFileMissing(): void
    {
        $this->expectException(Exception::class);

        (new CSSValidator())->validateFile(__DIR__.'/fixtures/does-not-exist.css');
    }

    public function testValidCSSFragment(): void
    {
        $css = '#valid-css-test { background: green; }';
        $validator = new CSSValidator();
        $result = $validator->validateFragment($css);
        self::assertEmpty($result->getErrors());
        self::assertEmpty($result->getWarnings());
        self::assertTrue($result->isValid());
        self::assertSame(ProfileType::Css3->value, $result->getCssLevel());
    }

    public function testErrorCSSFragment(): void
    {
        $css = '#error-css-test!!!ing { bac#%^&kground: green; }';
        $validator = new CSSValidator();
        $result = $validator->validateFragment($css);

        self::assertFalse($result->isValid());
        self::assertSame(ProfileType::Css3->value, $result->getCssLevel());

        self::assertEmpty($result->getWarnings());
        self::assertCount(2, $result->getErrors());

        $error1 = $result->getErrors()[0];

        self::assertSame('parse-error', $error1->getErrorType());
        self::assertNull($error1->getContext());
        self::assertSame('unrecognized', $error1->getErrorSubType());
        self::assertSame('error-css-test!!!ing {%&kground: green;', $error1->getSkippedString());
        self::assertSame('file://localhost/TextArea', $error1->getUri());
        self::assertSame(1, $error1->getLine());
        self::assertSame('Parse Error', $error1->getMessage());
        self::assertSame('generator.unrecognize', $error1->getType());

        $error2 = $result->getErrors()[1];

        self::assertSame('parse-error', $error2->getErrorType());
        self::assertSame('skippedString', $error2->getErrorSubType());
        self::assertSame('}', $error2->getSkippedString());
        self::assertSame('file://localhost/TextArea', $error2->getUri());
        self::assertSame(1, $error2->getLine());
        self::assertSame('Parse Error', $error2->getMessage());
        self::assertSame('generator.unrecognize', $error2->getType());
    }

    public function testWarningCSSFragment(): void
    {
        $css = '#warning-css-test { color: rgb(256, 1, 1); }';
        $validator = new CSSValidator();
        $result = $validator->validateFragment($css);

        self::assertTrue($result->isValid());
        self::assertSame(ProfileType::Css3->value, $result->getCssLevel());

        self::assertEmpty($result->getErrors());
        self::assertCount(1, $result->getWarnings());

        $warning = $result->getWarnings()[0];

        self::assertSame(0, $warning->getLevel());
        self::assertSame('file://localhost/TextArea', $warning->getUri());
        self::assertSame(1, $warning->getLine());
        self::assertSame('“256” is out of range', $warning->getMessage());
        self::assertSame('out-of-range', $warning->getType());
    }
}
