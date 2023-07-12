<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Pairwise;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Utils\VoteUtil;

class FilteredPairwise extends Pairwise
{
    protected readonly array $candidates;
    public readonly ?array $tags;

    public function __construct(
        Election $link,
        array|string|null $tags = null,
        public readonly bool|int $withTags = true
    ) {
        $this->tags = VoteUtil::tagsConvert($tags);

        parent::__construct($link);

        $this->candidates = $link->getCandidatesListAsString();
    }

    protected function getVotesManagerGenerator(): \Generator
    {
        return $this->getElection()->getVotesManager()->getVotesValidUnderConstraintGenerator(tags: $this->tags, with: $this->withTags);
    }

    protected function getCandidateNameFromKey(int $candidateKey): string
    {
        return $this->candidates[$candidateKey];
    }
}
