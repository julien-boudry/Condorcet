<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Examples;

use PHPUnit\Framework\Attributes\BackupStaticProperties;
use PHPUnit\Framework\TestCase;

class ExamplesTest extends TestCase
{
    public function testOverviewExample(): void
    {
        try {
            include __DIR__.'/../../Examples/1. Overview.php';
        } catch (\Exception $e) {
            throw $e;
        }

        self::assertTrue(true);
    }

    public function testAdvancedObjectManagementExample(): void
    {
        try {
            include __DIR__.'/../../Examples/2. AdvancedObjectManagement.php';
        } catch (\Exception $e) {
            throw $e;
        }

        self::assertTrue(true);
    }

    #[BackupStaticProperties(true)]
    public function testGlobalHtmlExample(): void
    {
        $this->expectOutputRegex('/\<\/html\>/');

        try {
            include __DIR__.'/../../Examples/Examples-with-html/A.Global_Example.php';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    #[BackupStaticProperties(true)]
    public function testRankingManipulationHtmlExample(): void
    {
        $this->expectOutputRegex('/\<\/html\>/');

        try {
            include __DIR__.'/../../Examples/Examples-with-html/B.Ranking_Manipulation.php';
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
