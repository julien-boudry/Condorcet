<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.94

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

use Condorcet\Condorcet;

// Registering native Condorcet Methods implementation
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\Copeland');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\KemenyYoung');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxWinning');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxMargin');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxOpposition');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\RankedPairs');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeWinning');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeMargin');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeRatio');

// Set the default Condorcet Class algorithm
Condorcet::setDefaultMethod('Schulze');
