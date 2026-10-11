<?php

namespace CSSValidator\Tests;

use CSSValidator\Options\Options;
use CSSValidator\Options\ProfileType;
use CSSValidator\Options\UserMediumType;
use CSSValidator\Options\WarningType;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testDefaults(): void
    {
        $options = new Options();

        self::assertSame('css3', $options->getProfile());
        self::assertSame('1', $options->getWarning());
        self::assertSame('all', $options->getUserMedium());
        self::assertSame('en', $options->getLang());
        self::assertSame([
            'profile' => 'css3',
            'warning' => '1',
            'usermedium' => 'all',
            'lang' => 'en',
        ], $options->buildOptions());
    }

    public function testSetProfileWithEnum(): void
    {
        $options = new Options();
        $options->setProfile(ProfileType::Css21);

        self::assertSame('css21', $options->getProfile());
    }

    public function testSetProfileWithString(): void
    {
        $options = new Options();
        $options->setProfile('svg');

        self::assertSame('svg', $options->getProfile());
    }

    public function testSetUserMediumWithEnum(): void
    {
        $options = new Options();
        $options->setUserMedium(UserMediumType::Print);

        self::assertSame('print', $options->getUserMedium());
    }

    public function testSetWarningWithEnum(): void
    {
        $options = new Options();
        $options->setWarning(WarningType::None);

        self::assertSame('no', $options->getWarning());
    }

    public function testBuildOptionsReflectsSetters(): void
    {
        $options = (new Options())
            ->setProfile(ProfileType::Css21)
            ->setWarning(WarningType::All)
            ->setUserMedium(UserMediumType::Screen)
            ->setLang('de');

        self::assertSame([
            'profile' => 'css21',
            'warning' => '2',
            'usermedium' => 'screen',
            'lang' => 'de',
        ], $options->buildOptions());
    }

    public function testSetLang(): void
    {
        $options = new Options();
        $options->setLang('fr');

        self::assertSame('fr', $options->getLang());
    }

    public function testSetProfileWithUnknownValueThrows(): void
    {
        try {
            (new Options())->setProfile('bogus');
            self::fail('Expected InvalidArgumentException to be thrown.');
        } catch (\InvalidArgumentException $e) {
            self::assertSame('Unknown profile "bogus".', $e->getMessage());
        }
    }

    public function testSetUserMediumWithUnknownValueThrows(): void
    {
        try {
            (new Options())->setUserMedium('bogus');
            self::fail('Expected InvalidArgumentException to be thrown.');
        } catch (\InvalidArgumentException $e) {
            self::assertSame('Unknown user medium "bogus".', $e->getMessage());
        }
    }

    public function testSetWarningWithUnknownValueThrows(): void
    {
        try {
            (new Options())->setWarning('bogus');
            self::fail('Expected InvalidArgumentException to be thrown.');
        } catch (\InvalidArgumentException $e) {
            self::assertSame('Unknown warning level "bogus".', $e->getMessage());
        }
    }
}
