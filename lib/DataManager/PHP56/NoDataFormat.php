<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
//declare(strict_types=1);

namespace Condorcet\DataManager\PHP56;

class NoDataFormat
{
    public function dataCallBack ($data)
    {
        return $data;
    }
}
