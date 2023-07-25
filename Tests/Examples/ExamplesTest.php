<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Examples;

use CondorcetPHP\Condorcet\Condorcet;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;

class ExamplesTest extends TestCase
{
    protected static string $condorcetDefaultMethod;

    public static function setUpBeforeClass(): void
    {
        self::$condorcetDefaultMethod = Condorcet::getDefaultMethod();
    }

    protected function tearDown(): void
    {
        Condorcet::$UseTimer = (new ReflectionClass(Condorcet::class))->getProperty('UseTimer')->getDefaultValue();
        Condorcet::setDefaultMethod(self::$condorcetDefaultMethod);
    }


    public function testOverviewExample(): void
    {
        try {
            include __DIR__.'/../../Examples/1. Overview.php';
        } catch (\Exception $e) {
            throw $e;
        }

        expect(true)->toBeTrue();
    }

    public function testAdvancedObjectManagementExample(): void
    {
        try {
            include __DIR__.'/../../Examples/2. AdvancedObjectManagement.php';
        } catch (\Exception $e) {
            throw $e;
        }

        expect(true)->toBeTrue();
    }

    public function testGlobalHtmlExample(): void
    {
        $this->expectOutputRegex('/\<\/html\>/');

        include __DIR__.'/../../Examples/Examples-with-html/A.Global_Example.php';
    }

    public function testRankingManipulationHtmlExample(): void
    {
        $this->expectOutputRegex('/\<\/html\>/');

        include __DIR__.'/../../Examples/Examples-with-html/B.Ranking_Manipulation.php';
    }
}
