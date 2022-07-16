<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console\Helper;

use CondorcetPHP\Condorcet\Console\Style\CondorcetStyle;
use CondorcetPHP\Condorcet\{Condorcet, Result};

abstract class FormaterHelper
{
    public static function formatResultTable(Result $result): array
    {
        $resultArray = $result->getResultAsArray(true);

        foreach ($resultArray as $rank => &$line) {
            if (\is_array($line)) {
                $line = implode(',', $line);
            }

            if ($rank === 1 && \count($result[1]) === 1 && $result[1][0] === $result->getCondorcetWinner()) {
                $line .= ' '.CondorcetStyle::CONDORCET_WINNER_SYMBOL_FORMATED;
            } elseif ($rank === max(array_keys($resultArray)) && \count($result[max(array_keys($resultArray))]) === 1 && $result[max(array_keys($resultArray))][0] === $result->getCondorcetLoser()) {
                $line .= ' '.CondorcetStyle::CONDORCET_LOSER_SYMBOL_FORMATED;
            }

            $line = [$rank, $line];
        }

        return $resultArray;
    }

    public static function prepareMethods(array $methodArgument): array
    {
        if (empty($methodArgument)) {
            return [['name' => Condorcet::getDefaultMethod()::METHOD_NAME[0], 'class' => Condorcet::getDefaultMethod()]];
        } else {
            $methods = [];

            foreach ($methodArgument as $oneMethod) {
                if (mb_strtolower($oneMethod) === 'all') {
                    $methods = Condorcet::getAuthMethods(false);
                    $methods = array_map(static fn ($m) => ['name' => $m, 'class' => Condorcet::getMethodClass($m)], $methods);
                    break;
                }

                if (Condorcet::isAuthMethod($oneMethod)) {
                    $method_class = Condorcet::getMethodClass($oneMethod);
                    $method_name = $method_class::METHOD_NAME[0];

                    if (!\in_array(needle: $method_name, haystack: $methods, strict: true)) {
                        $methods[] = ['name' => $method_name, 'class' => $method_class];
                    }
                }
            }

            return $methods;
        }
    }
}
