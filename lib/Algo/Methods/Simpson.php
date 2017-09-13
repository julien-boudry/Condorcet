<?php
/*
    Simpson part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\CopelandLike_Core;
use Condorcet\Algo\MethodInterface;

// SIMPSON is a Condorcet Algorithm
class Simpson extends CopelandLike_Core implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Simpson','Simpson Method'];

    protected $_countType = 'worst_defeat';


/////////// COMPUTE ///////////

    //:: SIMPSON ALGORITHM. :://

    protected function looking (array $challenge) : int
    {
        return min($challenge);
    }
}
