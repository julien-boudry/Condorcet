<?php
/*
    Dodgson part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\CopelandLike_Core;
use Condorcet\Algo\MethodInterface;

// DODGSON is a Condorcet Algorithm | https://en.wikipedia.org/wiki/Dodgson%27s_method
class Dodgson extends CopelandLike_Core implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Dodgson','Dodgson Method','Lewis Carroll'];

    protected $_countType = 'defeat_margin';


/////////// COMPUTE ///////////

    //:: DODGSON ALGORITHM. :://

    protected function looking (array $challenge) : int
    {
        return min($challenge);
    }

}
