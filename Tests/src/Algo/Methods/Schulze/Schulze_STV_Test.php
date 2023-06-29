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
        // Set Tideman A5C to the election object
        (new CondorcetElectionFormat(__DIR__.'/../../../../LargeElectionData/TidemanA53.cvotes'))->setDataToAnElection($this->election);

        // Compare results

        $resultsArray = $this->election->getResult('Schulze STV')->getOriginalResultArrayWithString();

        self::assertSame(
            expected: $this->election->getNumberOfSeats(),
            actual: count($resultsArray)
        );

        self::assertContains('J', $resultsArray);
        self::assertContains('A', $resultsArray);
        self::assertContains('G', $resultsArray);
        self::assertContains('D', $resultsArray);
/*
        self::assertSame(
            expected: [
                1 => 'J',
                2 => 'A',
                3 => 'G',
                4 => 'D',
            ],
            actual: $resultsArray
        );
*/
        // Stats from round 1
        self::assertSame(
            expected: [
                'A to B' => 316.175711,
                'A to C' => 352.129380,
                'A to D' => 303.100775,
                'A to E' => 307.846154,
                'A to F' => 298.883249,
                'A to G' => 266.374696,
                'A to H' => 349.351351,
                'A to I' => 348.337731,
                'A to J' => 193.625304,
                'B to A' => 143.824289,
                'B to C' => 262.462462,
                'B to D' => 221.153846,
                'B to E' => 240.176991,
                'B to F' => 222.913165,
                'B to G' => 199.414894,
                'B to H' => 265.438066,
                'B to I' => 263.253012,
                'B to J' => 128.845209,
                'C to A' => 107.870620,
                'C to B' => 197.537538,
                'C to D' => 197.906977,
                'C to E' => 201.703470,
                'C to F' => 183.197674,
                'C to G' => 171.397260,
                'C to H' => 240.747664,
                'C to I' => 241.022364,
                'C to J' => 105.891089,
                'D to A' => 156.899225,
                'D to B' => 238.846154,
                'D to C' => 262.093023,
                'D to E' => 248.295455,
                'D to F' => 242.234043,
                'D to G' => 197.142857,
                'D to H' => 264.532578,
                'D to I' => 276.260623,
                'D to J' => 146.975610,
                'E to A' => 152.153846,
                'E to B' => 219.823009,
                'E to C' => 258.296530,
                'E to D' => 211.704545,
                'E to F' => 214.494382,
                'E to G' => 191.152815,
                'E to H' => 259.814815,
                'E to I' => 274.842767,
                'E to J' => 120.992556,
                'F to A' => 161.116751,
                'F to B' => 237.086835,
                'F to C' => 276.802326,
                'F to D' => 217.765957,
                'F to E' => 245.505618,
                'F to G' => 207.817259,
                'F to H' => 280.229885,
                'F to I' => 275.190616,
                'F to J' => 139.803922,
                'G to A' => 193.625304,
                'G to B' => 260.585106,
                'G to C' => 288.602740,
                'G to D' => 262.857143,
                'G to E' => 268.847185,
                'G to F' => 252.182741,
                'G to H' => 306.259947,
                'G to I' => 314.604905,
                'G to J' => 183.785047,
                'H to A' => 110.648649,
                'H to B' => 194.561934,
                'H to C' => 219.252336,
                'H to D' => 195.467422,
                'H to E' => 200.185185,
                'H to F' => 179.770115,
                'H to G' => 153.740053,
                'H to I' => 250.125000,
                'H to J' => 87.058824,
                'I to A' => 111.662269,
                'I to B' => 196.746988,
                'I to C' => 218.977636,
                'I to D' => 183.739377,
                'I to E' => 185.157233,
                'I to F' => 184.809384,
                'I to G' => 145.395095,
                'I to H' => 209.875000,
                'I to J' => 97.150127,
                'J to A' => 266.374696,
                'J to B' => 331.154791,
                'J to C' => 354.108911,
                'J to D' => 313.024390,
                'J to E' => 339.007444,
                'J to F' => 320.196078,
                'J to G' => 276.214953,
                'J to H' => 372.941176,
                'J to I' => 362.849873,
            ],
            actual: $this->election->getResult('Schulze STV')->getStats()['rounds'][1]
        );
    }

}
