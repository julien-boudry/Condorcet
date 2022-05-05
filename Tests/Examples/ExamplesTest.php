<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Examples;

use PHPUnit\Framework\TestCase;

class ExamplesTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testOverviewExample(): void
    {
        $r = true;

        try {
            include __DIR__.'/../../Examples/1. Overview.php';
        } catch (Exception $e) {
            throw $e;
            $r = false;
        }

        self::assertTrue($r);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAdvancedObjectManagementExample(): void
    {
        $r = true;

        try {
            include __DIR__.'/../../Examples/2. AdvancedObjectManagement.php';
        } catch (Exception $e) {
            throw $e;
            $r = false;
        }

        self::assertTrue($r);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGlobalHtmlExample(): void
    {
        $r = true;

        try {
            \ob_start();
                include __DIR__.'/../../Examples/Examples-with-html/A.Global_Example.php';
            \ob_end_clean();

        } catch (Exception $e) {
            throw $e;
            $r = false;
        }

        self::assertTrue($r);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRankingManipulationHtmlExample(): void
    {
        $r = true;

        try {
            \ob_start();
                include __DIR__.'/../../Examples/Examples-with-html/B.Ranking_Manipulation.php';
            \ob_end_clean();
        } catch (\Exception $e) {
            $r = false;
            throw $e;
        }

        self::assertTrue($r);
    }


}
