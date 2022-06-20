<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\HighestAverageMethod;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use PHPUnit\Framework\TestCase;

class HighestAveragesMethodTest extends TestCase
{
    private readonly Election $election;

    public function setUp(): void
    {
        $this->election = new Election;
    }

    public function testFranceLegislatives2022_1erTour (): void
    {
        $this->election->setNumberOfSeats(577);
        $this->election->allowsVoteWeight(true);

        $this->election->parseCandidates(
            'Divers extrême gauche
            Parti radical de gauche
            Nouvelle union populaire écologique et sociale
            Divers gauche
            Ecologistes
            Divers
            Régionaliste
            Ensemble ! (Majorité présidentielle)
            Divers centre
            Union des Démocrates et des Indépendants
            Les Républicains
            Divers droite
            Droite souverainiste
            Reconquête !
            Rassemblement National
            Divers extrême droite'
        );

        $this->election->parseVotes('
            Divers extrême gauche	^266412
            Parti radical de gauche	^126689
            Nouvelle union populaire écologique et sociale	^5836079
            Divers gauche	^713574
            Ecologistes	^608314
            Divers	^192624
            Régionaliste	^291384
            Ensemble ! (Majorité présidentielle)	^5857364
            Divers centre	^283612
            Union des Démocrates et des Indépendants	^198062
            Les Républicains	^2370440
            Divers droite	^530782
            Droite souverainiste	^249603
            Reconquête !	^964775
            Rassemblement National	^4248537
            Divers extrême droite	^6457
        ');

        // self::assertSame([], $this->election->getResult('SainteLague')->getStats()['Seats per Candidates']);
        // self::assertSame([], $this->election->getResult('Jefferson')->getStats()['Seats per Candidates']);
    }
}