<?php

namespace CSSValidator\Tests;

use CSSValidator\Message\Error;
use CSSValidator\Message\Warning;
use CSSValidator\Options\ProfileType;
use CSSValidator\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $response = new Response();

        self::assertSame(ProfileType::Css3->value, $response->getCssLevel());
        self::assertFalse($response->isValid());
        self::assertSame([], $response->getErrors());
        self::assertSame([], $response->getWarnings());
    }

    public function testSetters(): void
    {
        $error = (new Error(self::errorNode()))->setMessage('boom');
        $warning = new Warning(self::warningNode());

        $response = (new Response())
            ->setCssLevel(ProfileType::Css21->value)
            ->setValid(true)
            ->setErrors([$error])
            ->setWarnings([$warning]);

        self::assertSame(ProfileType::Css21->value, $response->getCssLevel());
        self::assertTrue($response->isValid());
        self::assertSame([$error], $response->getErrors());
        self::assertSame([$warning], $response->getWarnings());
    }

    public function testAddErrorAndWarning(): void
    {
        $response = new Response();
        $response->addError(new Error(self::errorNode()));
        $response->addWarning(new Warning(self::warningNode()));

        self::assertCount(1, $response->getErrors());
        self::assertCount(1, $response->getWarnings());
    }

    private static function errorNode(): \DOMElement
    {
        $doc = new \DOMDocument();
        $doc->loadXML('<cssvalidationresponse><error><errortype>parse-error</errortype></error></cssvalidationresponse>');

        return $doc->getElementsByTagName('error')->item(0);
    }

    private static function warningNode(): \DOMElement
    {
        $doc = new \DOMDocument();
        $doc->loadXML('<cssvalidationresponse><warning><level>0</level></warning></cssvalidationresponse>');

        return $doc->getElementsByTagName('warning')->item(0);
    }
}
