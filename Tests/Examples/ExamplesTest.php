<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;

use PHPUnit\Framework\TestCase;


class ExamplesTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testOverviewExample()
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
    public function testAdvancedObjectManagementExample()
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
    public function testGlobalHtmlExample()
    {
        $r = true;

        try {
            ob_start();
                include __DIR__.'/../../Examples/Examples-with-html/A.Global_Example.php';
            ob_end_clean();

        } catch (Exception $e) {
            throw $e;
            $r = false;
        }

        self::assertTrue($r);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRankingManipulationHtmlExample()
    {
        $r = true;

        try {
            ob_start();
                include __DIR__.'/../../Examples/Examples-with-html/B.Ranking_Manipulation.php';
            ob_end_clean();
        } catch (Exception $e) {
            throw $e;
            $r = false;
        }

        self::assertTrue($r);
    }


}
