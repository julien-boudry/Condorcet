<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\CondorcetElectionFormat;
use PHPUnit\Framework\TestCase;

class Schulze_STV_Test extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_1(): void
    {
        // Set Tideman A53 to the election object
        (new CondorcetElectionFormat(__DIR__.'/../../../../LargeElectionData/TidemanA53.cvotes'))->setDataToAnElection($this->election);

        // Compare results

        self::assertSame(
            expected: [
                1 => 'j',
                2 => 'a',
                3 => 'g',
                4 => 'd',
                5 => 'f',
                6 => 'b',
                7 => 'e',
                8 => 'c',
                9 => 'i',
                10 => 'h'
            ],
            actual: $this->election->getResult('Schulze STV')->getOriginalResultArrayWithString()
        );

        // Stats from round 1
        self::assertSame(
            expected: [
                '1 to 2' => 316.175711,
                '1 to 3' => 352.129380,
                '1 to 4' => 303.100775,
                '1 to 5' => 307.846154,
                '1 to 6' => 298.883249,
                '1 to 7' => 266.374696,
                '1 to 8' => 349.351351,
                '1 to 9' => 348.337731,
                '1 to 10' => 193.625304,
                '2 to 1' => 143.824289,
                '2 to 3' => 262.462462,
                '2 to 4' => 221.153846,
                '2 to 5' => 240.176991,
                '2 to 6' => 222.913165,
                '2 to 7' => 199.414894,
                '2 to 8' => 265.438066,
                '2 to 9' => 263.253012,
                '2 to 10' => 128.845209,
                '3 to 1' => 107.870620,
                '3 to 2' => 197.537538,
                '3 to 4' => 197.906977,
                '3 to 5' => 201.703470,
                '3 to 6' => 183.197674,
                '3 to 7' => 171.397260,
                '3 to 8' => 240.747664,
                '3 to 9' => 241.022364,
                '3 to 10' => 105.891089,
                '4 to 1' => 156.899225,
                '4 to 2' => 238.846154,
                '4 to 3' => 262.093023,
                '4 to 5' => 248.295455,
                '4 to 6' => 242.234043,
                '4 to 7' => 197.142857,
                '4 to 8' => 264.532578,
                '4 to 9' => 276.260623,
                '4 to 10' => 146.975610,
                '5 to 1' => 152.153846,
                '5 to 2' => 219.823009,
                '5 to 3' => 258.296530,
                '5 to 4' => 211.704545,
                '5 to 6' => 214.494382,
                '5 to 7' => 191.152815,
                '5 to 8' => 259.814815,
                '5 to 9' => 274.842767,
                '5 to 10' => 120.992556,
                '6 to 1' => 161.116751,
                '6 to 2' => 237.086835,
                '6 to 3' => 276.802326,
                '6 to 4' => 217.765957,
                '6 to 5' => 245.505618,
                '6 to 7' => 207.817259,
                '6 to 8' => 280.229885,
                '6 to 9' => 275.190616,
                '6 to 10' => 139.803922,
                '7 to 1' => 193.625304,
                '7 to 2' => 260.585106,
                '7 to 3' => 288.602740,
                '7 to 4' => 262.857143,
                '7 to 5' => 268.847185,
                '7 to 6' => 252.182741,
                '7 to 8' => 306.259947,
                '7 to 9' => 314.604905,
                '7 to 10' => 183.785047,
                '8 to 1' => 110.648649,
                '8 to 2' => 194.561934,
                '8 to 3' => 219.252336,
                '8 to 4' => 195.467422,
                '8 to 5' => 200.185185,
                '8 to 6' => 179.770115,
                '8 to 7' => 153.740053,
                '8 to 9' => 250.125000,
                '8 to 10' => 87.058824,
                '9 to 1' => 111.662269,
                '9 to 2' => 196.746988,
                '9 to 3' => 218.977636,
                '9 to 4' => 183.739377,
                '9 to 5' => 185.157233,
                '9 to 6' => 184.809384,
                '9 to 7' => 145.395095,
                '9 to 8' => 209.875000,
                '9 to 10' => 97.150127,
                '10 to 1' => 266.374696,
                '10 to 2' => 331.154791,
                '10 to 3' => 354.108911,
                '10 to 4' => 313.024390,
                '10 to 5' => 339.007444,
                '10 to 6' => 320.196078,
                '10 to 7' => 276.214953,
                '10 to 8' => 372.941176,
                '10 to 9' => 362.849873,
            ],
            actual: $this->election->getResult('Schulze STV')->getStats()['rounds'][1]
        );
    }

}
