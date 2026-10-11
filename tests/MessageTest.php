<?php

namespace CSSValidator\Tests;

use CSSValidator\Message\Error;
use CSSValidator\Message\Warning;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testMapsDomNode(): void
    {
        $doc = new \DOMDocument();
        $doc->loadXML(
            '<cssvalidationresponse><errors><errorlist>'.
            '<uri>https://example.com/style.css</uri>'.
            '<error>'.
            '<line>5</line>'.
            '<type>generator.unrecognize</type>'.
            '<message> boom </message>'.
            '<errortype>parse-error</errortype>'.
            '<errorsubtype> unrecognized </errorsubtype>'.
            '<skippedstring> skip </skippedstring>'.
            '<context>ctx</context>'.
            '</error>'.
            '</errorlist></errors></cssvalidationresponse>'
        );

        $error = new Error($doc->getElementsByTagName('error')->item(0));

        self::assertSame('https://example.com/style.css', $error->getUri());
        self::assertSame(5, $error->getLine());
        self::assertSame('generator.unrecognize', $error->getType());
        self::assertSame('boom', $error->getMessage());
        self::assertSame('parse-error', $error->getErrorType());
        self::assertSame('unrecognized', $error->getErrorSubType());
        self::assertSame('skip', $error->getSkippedString());
        self::assertSame('ctx', $error->getContext());
    }

    public function testWarningMapsLevel(): void
    {
        $doc = new \DOMDocument();
        $doc->loadXML('<cssvalidationresponse><warnings><warning><line>1</line><level>0</level><message>warn</message></warning></warnings></cssvalidationresponse>');

        $warning = new Warning($doc->getElementsByTagName('warning')->item(0));

        self::assertSame(0, $warning->getLevel());
        self::assertSame(1, $warning->getLine());
        self::assertSame('warn', $warning->getMessage());
    }

    public function testMissingFieldsAreTolerant(): void
    {
        $doc = new \DOMDocument();
        $doc->loadXML('<cssvalidationresponse><error></error></cssvalidationresponse>');

        $error = new Error($doc->getElementsByTagName('error')->item(0));

        self::assertSame('', $error->getUri());
        self::assertSame(0, $error->getLine());
        self::assertNull($error->getType());
        self::assertNull($error->getMessage());
        self::assertSame('', $error->getErrorType());
        self::assertNull($error->getErrorSubType());
        self::assertNull($error->getSkippedString());
        self::assertNull($error->getContext());
    }

    public function testSetters(): void
    {
        $doc = new \DOMDocument();
        $doc->loadXML('<cssvalidationresponse><error><errortype>parse-error</errortype></error></cssvalidationresponse>');

        $error = (new Error($doc->getElementsByTagName('error')->item(0)))
            ->setErrorType('file-not-found')
            ->setErrorSubType('io')
            ->setSkippedString('x')
            ->setContext('ctx')
            ->setLine(7)
            ->setType('type')
            ->setMessage('boom')
            ->setUri('https://example.com');

        self::assertSame('file-not-found', $error->getErrorType());
        self::assertSame('io', $error->getErrorSubType());
        self::assertSame('x', $error->getSkippedString());
        self::assertSame('ctx', $error->getContext());
        self::assertSame(7, $error->getLine());
        self::assertSame('type', $error->getType());
        self::assertSame('boom', $error->getMessage());
        self::assertSame('https://example.com', $error->getUri());
    }
}
