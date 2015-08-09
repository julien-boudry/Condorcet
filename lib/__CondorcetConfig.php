<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.94

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

// Registering native Condorcet Methods implementation
namespace\Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\Copeland');
namespace\Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\KemenyYoung');
namespace\Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxWinning');
namespace\Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxMargin');
namespace\Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxOpposition');
namespace\Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\RankedPairs');
namespace\Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeWinning');
namespace\Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeMargin');
namespace\Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeRatio');

// Set the default Condorcet Class algorithm
namespace\Condorcet::setDefaultMethod('Schulze');
