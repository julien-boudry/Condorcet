<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, InternalModulesAPI, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\{CondorcetUtil, Vote};
use CondorcetPHP\Condorcet\DataManager\VotesManager;
use CondorcetPHP\Condorcet\Throwable\{FileDoesNotExistException, VoteInvalidFormatException, VoteMaxNumberReachedException, VoteException};
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;

// Manage Results for Election class
trait VotesProcess
{
    /////////// CONSTRUCTOR ///////////

    // Data and global options
    protected VotesManager $_Votes; // Votes list
    protected int $_voteFastMode = 0; // When parsing vote, avoid unnecessary checks


/////////// VOTES LIST ///////////

    // How many votes are registered ?
    #[PublicAPI]
    #[Description('Count the number of actual registered and valid vote for this election. This method ignore votes constraints, only valid vote will be counted.')]
    #[FunctionReturn('Number of valid and registered vote into this election.')]
    #[Related('Election::getVotesList', 'Election::countValidVoteWithConstraints')]
    public function countVotes(
        #[FunctionParameter('Tag into string separated by commas, or an Array')]
        array|null|string $tags = null,
        #[FunctionParameter('Count Votes with this tag ou without this tag-')]
        bool $with = true
    ): int {
        return $this->_Votes->countVotes(VoteUtil::tagsConvert($tags), $with);
    }

    #[PublicAPI]
    #[Description('Count the number of actual invalid (if constraints functionality is enabled) but registered vote for this election.')]
    #[FunctionReturn('Number of valid and registered vote into this election.')]
    #[Related('Election::countValidVoteWithConstraints', 'Election::countVotes', 'Election::sumValidVotesWeightWithConstraints')]
    public function countInvalidVoteWithConstraints(): int
    {
        return $this->_Votes->countInvalidVoteWithConstraints();
    }

    #[PublicAPI]
    #[Description("Count the number of actual registered and valid vote for this election. This method don't ignore votes constraints, only valid vote will be counted.")]
    #[FunctionReturn('Number of valid and registered vote into this election.')]
    #[Related('Election::countInvalidVoteWithConstraints', 'Election::countVotes', 'Election::sumValidVotesWeightWithConstraints')]
    public function countValidVoteWithConstraints(): int
    {
        return $this->countVotes() - $this->countInvalidVoteWithConstraints();
    }

    // Sum votes weight
    #[PublicAPI]
    #[Description('Sum total votes weight in this election. If vote weight functionality is disable (default setting), it will return the number of registered votes. This method ignore votes constraints.')]
    #[FunctionReturn('(Int) Total vote weight')]
    #[Related('Election::sumValidVotesWeightWithConstraints')]
    public function sumVotesWeight(): int
    {
        return $this->_Votes->sumVotesWeight(false);
    }

    #[PublicAPI]
    #[Description("Sum total votes weight in this election. If vote weight functionality is disable (default setting), it will return the number of registered votes. This method don't ignore votes constraints, only valid vote will be counted.")]
    #[FunctionReturn('(Int) Total vote weight')]
    #[Related('Election::countValidVoteWithConstraints', 'Election::countInvalidVoteWithConstraints')]
    public function sumValidVotesWeightWithConstraints(): int
    {
        return $this->_Votes->sumVotesWeight(true);
    }

    // Get the votes registered list
    #[PublicAPI]
    #[Description('Get registered vote list.')]
    #[FunctionReturn('Populated by each Vote object.')]
    #[Related('Election::countVotes', 'Election::getVotesListAsString')]
    public function getVotesList(
        #[FunctionParameter('Tags list as a string separated by commas or array')]
        array|null|string $tags = null,
        #[FunctionParameter('Get votes with these tags or without')]
        bool $with = true
    ): array {
        return $this->_Votes->getVotesList(VoteUtil::tagsConvert($tags), $with);
    }

    #[PublicAPI]
    #[Description('Get registered vote list.')]
    #[FunctionReturn("Return a string like :<br>\nA > B > C * 3<br>\nA = B > C * 6")]
    #[Related('Election::parseVotes')]
    public function getVotesListAsString(
        #[FunctionParameter('Depending of the implicit ranking rule of the election, will complete or not the ranking. If $withContext is false, ranking are never adapted to the context.')]
        bool $withContext = true
    ): string {
        return $this->_Votes->getVotesListAsString($withContext);
    }

    public function getVotesManager(): VotesManager
    {
        return $this->_Votes;
    }

    #[PublicAPI]
    #[Description("Same as Election::getVotesList. But Return a PHP generator object.\nUsefull if your work on very large election with an external DataHandler, because it's will not using large memory amount.")]
    #[FunctionReturn('Populated by each Vote object.')]
    #[Related('Election::getVotesList')]
    public function getVotesListGenerator(
        #[FunctionParameter('Tags list as a string separated by commas or array')]
        array|null|string $tags = null,
        #[FunctionParameter('Get votes with these tags or without')]
        bool $with = true
    ): \Generator {
        return $this->_Votes->getVotesListGenerator(VoteUtil::tagsConvert($tags), $with);
    }

    #[PublicAPI]
    #[Description("Same as Election::getVotesList. But Return a PHP generator object.\nUsefull if your work on very large election with an external DataHandler, because it's will not using large memory amount.")]
    #[FunctionReturn('Populated by each Vote object.')]
    #[Related('Election::getVotesListGenerator', 'Election::getVotesList')]
    public function getVotesValidUnderConstraintGenerator(
        #[FunctionParameter('Tags list as a string separated by commas or array')]
        array|null|string $tags = null,
        #[FunctionParameter('Get votes with these tags or without')]
        bool $with = true
    ): \Generator {
        return $this->_Votes->getVotesValidUnderConstraintGenerator($tags, $with);
    }

    #[InternalModulesAPI]
    public function getVoteKey(Vote $vote): ?int
    {
        return $this->_Votes->getVoteKey($vote);
    }


    /////////// ADD & REMOVE VOTE ///////////

    // Add a single vote. Array key is the rank, each candidate in a rank are separate by ',' It is not necessary to register the last rank.
    #[PublicAPI]
    #[Description('Add a vote to an election.')]
    #[FunctionReturn('The vote object.')]
    #[Throws(VoteMaxNumberReachedException::class)]
    #[Example('Manual - Vote Management', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote')]
    #[Related('Election::parseVotes', 'Election::addVotesFromJson', 'Election::removeVote', 'Election::getVotesList')]
    public function addVote(
        #[FunctionParameter('String or array representation. Or CondorcetPHP\Condorcet\Vote object. If you not provide yourself Vote object, a new one will be generate for you')]
        array|string|Vote $vote,
        #[FunctionParameter('String separated by commas or an array. Will add tags to the vote object for you. But you can too add it yourself to Vote object')]
        array|string|null $tags = null
    ): Vote {
        $this->prepareVoteInput($vote, $tags);

        // Check Max Vote Count
        if (self::$_maxVoteNumber !== null && $this->countVotes() >= self::$_maxVoteNumber) {
            throw new VoteMaxNumberReachedException(self::$_maxVoteNumber);
        }

        // Register vote
        return $this->registerVote($vote, $tags); // Return the vote object
    }

    public function prepareUpdateVote(Vote $existVote): void
    {
        $this->_Votes->UpdateAndResetComputing(key: $this->getVoteKey($existVote), type: 2);
    }

    public function finishUpdateVote(Vote $existVote): void
    {
        $this->_Votes->UpdateAndResetComputing(key: $this->getVoteKey($existVote), type: 1);

        if ($this->_Votes->isUsingHandler()) {
            $this->_Votes[$this->getVoteKey($existVote)] = $existVote;
        }
    }

    public function checkVoteCandidate(Vote $vote): bool
    {
        if ($this->_voteFastMode === 0) {
            $linkCount = $vote->countLinks();
            $linkCheck = ($linkCount === 0 || ($linkCount === 1 && $vote->haveLink($this)));

            foreach ($vote->getAllCandidates() as $candidate) {
                if (!$linkCheck && $candidate->getProvisionalState() && !$this->isRegisteredCandidate(candidate: $candidate, strictMode: true) && $this->isRegisteredCandidate(candidate: $candidate, strictMode: false)) {
                    return false;
                }
            }
        }

        if ($this->_voteFastMode < 2) {
            $ranking = $vote->getRanking();

            $change = $this->convertRankingCandidates($ranking);

            if ($change) {
                $vote->setRanking(
                    $ranking,
                    (abs($vote->getTimestamp() - microtime(true)) > 0.5) ? ($vote->getTimestamp() + 0.001) : null
                );
            }
        }

        return true;
    }

    public function convertRankingCandidates(array &$ranking): bool
    {
        $change = false;

        foreach ($ranking as &$choice) {
            foreach ($choice as &$candidate) {
                if (!$this->isRegisteredCandidate($candidate, true)) {
                    if ($candidate->getProvisionalState() && $this->isRegisteredCandidate(candidate: $candidate, strictMode: false)) {
                        $candidate = $this->_Candidates[$this->getCandidateKey((string) $candidate)];
                        $change = true;
                    }
                }
            }
        }

        return $change;
    }

    // Write a new vote
    protected function registerVote(Vote $vote, array|string|null $tags): Vote
    {
        // Vote identifiant
        $tags === null || $vote->addTags($tags);

        // Register
        try {
            $vote->registerLink($this);
            $this->_Votes[] = $vote;
        } catch (CondorcetInternalException $e) {
            // Security : Check if vote object is not already registered
            throw new VoteException('seats are already registered');
        }

        return $vote;
    }

    #[PublicAPI]
    #[Description('Remove Votes from an election.')]
    #[FunctionReturn("List of removed CondorcetPHP\Condorcet\Vote object.")]
    #[Example('Manual - Vote management', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-2.-Manage-Vote')]
    #[Related('Election::addVote', 'Election::getVotesList', 'Election::removeVotesByTags')]
    public function removeVote(
        #[FunctionParameter('Vote object')]
        Vote $vote
    ): bool {
        $key = $this->getVoteKey($vote);
        if ($key !== null) {
            $deletedVote = $this->_Votes[$key];

            unset($this->_Votes[$key]);

            $deletedVote->destroyLink($this);

            return true;
        } else {
            throw new VoteException('cannot remove vote, is not registered in this election');
        }
    }

    #[PublicAPI]
    #[Description("Remove Vote from an election using tags.\n\n```php\n\$election->removeVotesByTags('Charlie') ; // Remove vote(s) with tag Charlie\n\$election->removeVotesByTags('Charlie', false) ; // Remove votes without tag Charlie\n\$election->removeVotesByTags('Charlie, Julien', false) ; // Remove votes without tag Charlie AND without tag Julien.\n\$election->removeVotesByTags(array('Julien','Charlie')) ; // Remove votes with tag Charlie OR with tag Julien.\n```")]
    #[FunctionReturn("List of removed CondorcetPHP\Condorcet\Vote object.")]
    #[Example('Manual - Vote management', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-2.-Manage-Vote')]
    #[Related('Election::addVote', 'Election::getVotesList', 'Election::removeVotes')]
    public function removeVotesByTags(
        #[FunctionParameter('Tags as string separated by commas or array')]
        array|string $tags,
        #[FunctionParameter('Votes with these tags or without')]
        bool $with = true
    ): array {
        $rem = [];

        // Prepare Tags
        $tags = VoteUtil::tagsConvert($tags);

        // Deleting
        foreach ($this->getVotesList($tags, $with) as $key => $value) {
            $deletedVote = $this->_Votes[$key];
            $rem[] = $deletedVote;

            unset($this->_Votes[$key]);

            $deletedVote->destroyLink($this);
        }

        return $rem;
    }


    /////////// PARSE VOTE ///////////

    // Return the well formatted vote to use.
    #[Throws(VoteInvalidFormatException::class)]
    protected function prepareVoteInput(array|string|Vote &$vote, array|string $tags = null): void
    {
        if (!($vote instanceof Vote)) {
            $vote = new Vote(ranking: $vote, tags: $tags, ownTimestamp: null, electionContext: $this);
        }

        // Check array format && Make checkVoteCandidate
        if (!$this->checkVoteCandidate($vote)) {
            throw new VoteInvalidFormatException;
        }
    }

    #[PublicAPI]
    #[Description('Import votes from a Json source.')]
    #[FunctionReturn('Count of new registered vote.')]
    #[Example('Manual - Add Vote', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote')]
    #[Related('Election::addVote', 'Election::parseVotes', 'Election::addCandidatesFromJson')]
    public function addVotesFromJson(
        #[FunctionParameter('Json string input')]
        string $input
    ): int {
        $input = CondorcetUtil::prepareJson($input);

        $adding = [];
        $count = 0;

        foreach ($input as $record) {
            if (empty($record['vote'])) {
                continue;
            }

            $vote = $record['vote'];
            $tags = !isset($record['tag']) ? null : $record['tag'];
            $multiple = !isset($record['multi']) ? 1 : (int) $record['multi'];
            $weight = !isset($record['weight']) ? 1 : (int) $record['weight'];

            $this->synthesisVoteFromParse($count, $multiple, $adding, $vote, $tags, $weight);
        }

        $this->doAddVotesFromParse($adding);

        return $count;
    }

    #[PublicAPI]
    #[Description('Import votes from a text source. If any invalid vote is found inside, nothing are registered.')]
    #[FunctionReturn('Count of the new registered vote.')]
    #[Example('Manual - Add Vote', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote')]
    #[Related('Election::addVote', 'Election::parseCandidates', 'Election::parseVotesWithoutFail', 'Election::addVotesFromJson')]
    public function parseVotes(
        #[FunctionParameter('String or valid path to a text file')]
        string $input,
        #[FunctionParameter('If true, the input is evalatued as path to text file')]
        bool $isFile = false
    ): int {
        $input = CondorcetUtil::prepareParse($input, $isFile);

        $adding = [];
        $count = 0;

        foreach ($input as $line) {
            // Disallow < and "
            if (preg_match('/<|"/mi', $line) === 1) {
                throw new VoteInvalidFormatException("found '<' or '|' in this line: " . $line);
            }

            // Multiples
            $multiple = VoteUtil::parseAnalysingOneLine(strpos($line, '*'), $line);

            // Vote Weight
            $weight = VoteUtil::parseAnalysingOneLine(strpos($line, '^'), $line);

            // Tags + vote
            if (str_contains($line, '||') === true) {
                $data = explode('||', $line);

                $vote = $data[1];
                $tags = $data[0];
            // Vote without tags
            } else {
                $vote = $line;
                $tags = null;
            }

            $this->synthesisVoteFromParse(count: $count, multiple: $multiple, adding: $adding, vote: $vote, tags: $tags, weight: $weight);
        }

        $this->doAddVotesFromParse($adding);

        return $count;
    }

    #[PublicAPI]
    #[Description('Similar to parseVote method. But will ignore invalid line. This method is also far less greedy in memory and must be prefered for very large file input. And to combine with the use of an external data handler.')]
    #[FunctionReturn("Number of invalid records into input (except empty lines). It's not an invalid votes count. Check Election::countVotes if you want to be sure.")]
    #[Example('Manual - Add Vote', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote')]
    #[Related('Election::addVote', 'Election::parseCandidates', 'Election::parseVotes', 'Election::addVotesFromJson')]
    public function parseVotesWithoutFail(
        #[FunctionParameter('String, valid path to a text file or an object SplFileInfo or extending it like SplFileObject')]
        \SplFileInfo|string $input,
        #[FunctionParameter('If true, the string input is evalatued as path to text file')]
        bool $isFile = false,
        #[FunctionParameter('Callback function to execute after each registered vote')]
        ?\Closure $callBack = null
    ): int {
        $inserted_votes_count = 0;
        $fail_count = 0;
        $doCallBack = $callBack !== null;

        if (!$isFile && !($input instanceof \SplFileInfo)) {
            $file = new \SplTempFileObject(256 * 1024 * 1024);
            $file->fwrite($input);
        } elseif ($input instanceof \SplFileObject) {
            $file = $input;
        } else {
            $file = ($input instanceof \SplFileInfo) ? $input : new \SplFileInfo($input);

            if ($file->isFile() && $file->isReadable()) {
                $file = ($file instanceof \SplFileObject) ? $file : $file->openFile('r');
            } else {
                throw new FileDoesNotExistException('Specified input file does not exist. path: '.$input);
            }
        }

        unset($input); // Memory Optimization
        $file->setFlags($file->getFlags() | \SplFileObject::SKIP_EMPTY);
        $file->rewind();

        $char = '';
        $record = '';

        while ($char !== false) {
            $char = $file->fgetc();

            if ($char === ';' || $char === "\n" || $char === '#' || $char === false) {
                try {
                    CondorcetUtil::prepareParse($record, false);

                    $multiple = VoteUtil::parseAnalysingOneLine(strpos(haystack: $record, needle: '*'), $record);

                    for ($i=0; $i < $multiple; $i++) {
                        $inserted_votes_count += $this->parseVotes($record);

                        if ($doCallBack) {
                            $doCallBack = $callBack($inserted_votes_count);
                        }
                    }
                } catch (VoteInvalidFormatException) {
                    ++$fail_count;
                } finally {
                    $record = '';
                }

                if ($char === '#') {
                    $newLine = false;  // Can not use $file->current() then $file->next(): in case of very big election on one lince, can lead to memory limit.
                    while (!$newLine && ($char = $file->fgetc()) !== false) {
                        if ($char === "\n") {
                            $newLine = true;
                        }
                    }
                }
            } else {
                $record .= $char;
            }
        }

        return $fail_count;
    }

    protected function synthesisVoteFromParse(int &$count, int $multiple, array &$adding, array|string|Vote $vote, null|array|string $tags, int $weight): void
    {
        $adding_predicted_count = $count + $multiple;

        if (self::$_maxVoteNumber && self::$_maxVoteNumber < ($this->countVotes() + $adding_predicted_count)) {
            throw new VoteMaxNumberReachedException(self::$_maxParseIteration);
        }

        if (self::$_maxParseIteration !== null && $adding_predicted_count >= self::$_maxParseIteration) {
            throw new VoteMaxNumberReachedException(self::$_maxParseIteration);
        }

        $newVote = new Vote($vote, $tags, null, $this);
        $newVote->setWeight($weight);

        $adding[] = ['multiple' => $multiple, 'vote' => $newVote];

        $count += $multiple;
    }

    protected function doAddVotesFromParse(array $adding): void
    {
        $this->_voteFastMode = 1;

        foreach ($adding as $oneLine) {
            for ($i = 1 ; $i <= $oneLine['multiple'] ; $i++) {
                if ($i === 1) {
                    $finalVoteModel = $this->addVote($oneLine['vote']);
                    $this->_voteFastMode = 2;
                } else {
                    $this->addVote(clone $finalVoteModel);
                }
            }
        }

        $this->_voteFastMode = 0;
    }
}
