<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator;

enum BookLibrary: string
{
    public const BASE = Generate::BOOK_URL.'/3.AsPhpLibrary/';

    case VotingMethods = 'https://www.condorcet.io/#VotingMethods';

    case Installation = self::BASE.'1.Installation';
    case Flow = self::BASE.'2.WorkFlow';

    case Election = self::BASE.'3.CreateAnElection';

    case Candidates = self::BASE.'4.Candidates';

    case Votes = self::BASE.'5.Votes/1.AddVotes';
    case AddVotes = self::BASE.'5.Votes/1.AddVotes';
    case VotesTags = self::BASE.'5.Votes/2.VotesTags';
    case ManageVotes = self::BASE.'5.Votes/3.ManageVotes';
    case VotesConstraints = self::BASE.'5.Votes/4.VoteConstraints';
    case VoteWeight = self::BASE.'5.Votes/5.VoteWeight';

    case Results = self::BASE.'.AddVotes';
    case ResultsWinner = self::BASE.'6.Results/1.WinnerAndLoser';
    case ResultsRanking = self::BASE.'6.Results/2.FullRanking';
    case ResultsImplicitExplicit = self::BASE.'6.Results/4.ImplicitOrExplicitMod';
    case ResultsVotingMethods = self::BASE.'6.Results/5.VotingMethods';

    case Crypto = self::BASE.'7.GoFurther/CryptographicChecksum';
    case ElectionFilesFormats = self::BASE.'7.GoFurther/ElectionFilesFormats';
    case MassiveElection = self::BASE.'7.GoFurther/GetStarteToHandleMillionsOfVotes';
    case Timer = self::BASE.'7.GoFurther/TimerBenchMarking';
}
