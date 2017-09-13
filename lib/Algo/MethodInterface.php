<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo;

use Condorcet\Result;

// Interface with the aim of verifying the good modular implementation of algorithms.
interface MethodInterface
{
    public function getResult() : Result;
}
