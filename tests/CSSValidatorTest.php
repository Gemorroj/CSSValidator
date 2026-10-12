<?php

namespace CSSValidator\Tests;

use CSSValidator\CSSValidator;
use CSSValidator\Exception;
use CSSValidator\Options\Options;
use CSSValidator\Options\ProfileType;
use CSSValidator\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class CSSValidatorTest extends TestCase
{
    /**
     * The validator fetches the URI server-side and needs a "text/css" response,
     * so the fixtures are taken from the immutable 3.5.0 tag via a JS CDN.
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
        $css = '#valid-css-test { background: green; }';
        $file = \tempnam(\sys_get_temp_dir(), 'cssvalidator-');
        self::assertNotFalse($file);
        \file_put_contents($file, $css);

        try {
            $result = (new CSSValidator())->validateFile($file);
        } finally {
            \unlink($file);
        }

        self::assertEmpty($result->getErrors());
        self::assertEmpty($result->getWarnings());
        self::assertTrue($result->isValid());
        self::assertSame(ProfileType::Css3->value, $result->getCssLevel());
    }

    public function testValidateFileMissing(): void
    {
        $this->expectException(Exception::class);

        (new CSSValidator())->validateFile(__DIR__.'/does-not-exist.css');
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

    public function testConstructorDefaults(): void
    {
        $validator = new CSSValidator();

        self::assertSame(ProfileType::Css3->value, $validator->getOptions()->getProfile());
        self::assertSame('https://jigsaw.w3.org/css-validator/validator', $validator->getValidatorUri());
    }

    public function testConstructorWithCustomConfiguration(): void
    {
        $options = (new Options())->setProfile(ProfileType::Css21);
        $httpClient = HttpClient::create();

        $validator = new CSSValidator($options, $httpClient, 'http://localhost:8888/validator');

        self::assertSame($options, $validator->getOptions());
        self::assertSame($httpClient, $validator->getHttpClient());
        self::assertSame('http://localhost:8888/validator', $validator->getValidatorUri());
    }

    public function testParseResponse(): void
    {
        $response = $this->parseResponse($this->soapResponse());

        self::assertSame('css3', $response->getCssLevel());
        self::assertFalse($response->isValid());
        self::assertCount(1, $response->getErrors());
        self::assertCount(1, $response->getWarnings());

        $error = $response->getErrors()[0];

        self::assertSame('file://localhost/TextArea', $error->getUri());
        self::assertSame(1, $error->getLine());
        self::assertSame('parse-error', $error->getErrorType());
        self::assertSame('unrecognized', $error->getErrorSubType());
        self::assertSame('generator.unrecognize', $error->getType());
        self::assertSame('Parse Error', $error->getMessage());

        $warning = $response->getWarnings()[0];

        self::assertSame(0, $warning->getLevel());
        self::assertSame('out-of-range', $warning->getType());
        self::assertSame('out of range', $warning->getMessage());
    }

    public function testParseResponseWithoutResult(): void
    {
        $response = $this->parseResponse('<cssvalidationresponse></cssvalidationresponse>');

        self::assertSame(ProfileType::Css3->value, $response->getCssLevel());
        self::assertFalse($response->isValid());
        self::assertSame([], $response->getErrors());
        self::assertSame([], $response->getWarnings());
    }

    public function testParseResponseNonXml(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed load xml');

        $this->parseResponse('oops, not xml');
    }

    public function testParseResponseSoapFault(): void
    {
        try {
            $this->parseResponse(self::soapFault());
            self::fail('Expected Exception to be thrown.');
        } catch (Exception $e) {
            self::assertSame('Error at index 0 in: "text"', $e->getMessage());
        }
    }

    public function testParseResponseFaultWithoutReason(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The validator reported a SOAP fault.');

        $this->parseResponse(
            '<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope"><env:Body><env:Fault/></env:Body></env:Envelope>'
        );
    }

    private function parseResponse(string $xml): Response
    {
        $validator = new class extends CSSValidator {
            public function parse(string $xml): Response
            {
                return $this->parseSOAP12Response($xml);
            }
        };

        return $validator->parse($xml);
    }

    private function soapResponse(): string
    {
        return '<?xml version=\'1.0\' encoding="utf-8"?>'.
            '<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope" xmlns:m="http://www.w3.org/2005/07/css-validator"><env:Body>'.
            '<m:cssvalidationresponse>'.
            '<m:uri>file://localhost/TextArea</m:uri>'.
            '<m:csslevel>css3</m:csslevel>'.
            '<m:validity>false</m:validity>'.
            '<m:result>'.
            '<m:errors><m:errorlist><m:uri>file://localhost/TextArea</m:uri>'.
            '<m:error><m:line>1</m:line><m:errortype>parse-error</m:errortype><m:errorsubtype>unrecognized</m:errorsubtype>'.
            '<m:skippedstring>x</m:skippedstring><m:type>generator.unrecognize</m:type><m:message>Parse Error</m:message></m:error>'.
            '</m:errorlist></m:errors>'.
            '<m:warnings><m:warninglist><m:uri>file://localhost/TextArea</m:uri>'.
            '<m:warning><m:line>1</m:line><m:level>0</m:level><m:type>out-of-range</m:type><m:message>out of range</m:message></m:warning>'.
            '</m:warninglist></m:warnings>'.
            '</m:result>'.
            '</m:cssvalidationresponse>'.
            '</env:Body></env:Envelope>';
    }

    private static function soapFault(): string
    {
        return '<?xml version=\'1.0\' encoding="utf-8"?>'.
            '<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope" xmlns:m="http://www.w3.org/2005/07/css-validator"><env:Body>'.
            '<env:Fault><env:Code><env:Value>env:Sender</env:Value></env:Code>'.
            '<env:Reason><env:Text xml:lang="en-US">Error at index 0 in: "text"</env:Text></env:Reason>'.
            '</env:Fault>'.
            '</env:Body></env:Envelope>';
    }
}
