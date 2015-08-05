<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.94

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

// Registering native Condorcet Methods implementation
namespace\Condorcet::addAlgos(__NAMESPACE__.'\\Algo\\Methods\\Copeland');
namespace\Condorcet::addAlgos(__NAMESPACE__.'\\Algo\\Methods\\KemenyYoung');
namespace\Condorcet::addAlgos(__NAMESPACE__.'\\Algo\\Methods\\Minimax_Winning');
namespace\Condorcet::addAlgos(__NAMESPACE__.'\\Algo\\Methods\\Minimax_Margin');
namespace\Condorcet::addAlgos(__NAMESPACE__.'\\Algo\\Methods\\Minimax_Opposition');
namespace\Condorcet::addAlgos(__NAMESPACE__.'\\Algo\\Methods\\RankedPairs');
namespace\Condorcet::addAlgos(__NAMESPACE__.'\\Algo\\Methods\\Schulze');
namespace\Condorcet::addAlgos(__NAMESPACE__.'\\Algo\\Methods\\Schulze_Margin');
namespace\Condorcet::addAlgos(__NAMESPACE__.'\\Algo\\Methods\\Schulze_Ratio');

// Set the default Condorcet Class algorithm
namespace\Condorcet::setDefaultMethod('Schulze');
