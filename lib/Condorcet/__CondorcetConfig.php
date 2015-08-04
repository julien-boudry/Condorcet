<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.94

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

// Registering native Condorcet Methods implementation
namespace\Condorcet::addAlgos('Copeland');
namespace\Condorcet::addAlgos('KemenyYoung');
namespace\Condorcet::addAlgos( array('Minimax_Winning','Minimax_Margin', 'Minimax_Opposition') );
namespace\Condorcet::addAlgos('RankedPairs');
namespace\Condorcet::addAlgos( array('Schulze', 'Schulze_Margin', 'Schulze_Ratio') );

// Set the default Condorcet Class algorithm
namespace\Condorcet::setDefaultMethod('Schulze');
