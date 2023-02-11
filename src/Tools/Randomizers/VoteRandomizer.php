<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tools\Randomizers;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, FunctionParameter, FunctionReturn, PublicAPI};
use CondorcetPHP\Condorcet\Vote;

class VoteRandomizer extends ArrayRandomizer
{
    #[PublicAPI]
    #[Description('Generate a new random vote.')]
    #[FunctionReturn('Return the new vote.')]
    public function getNewVote(): Vote
    {
        return new Vote(self::shuffle());
    }
}
