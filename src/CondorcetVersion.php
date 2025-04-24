<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet;

// Generic for many Condorcet Class
trait CondorcetVersion
{
    /**
     * The Condorcet PHP version who built this object. Usefull pour serializing Election.
     * @api
     */
    public private(set) string $buildByCondorcetVersion = Condorcet::VERSION;

    /**
     * Get the Condorcet PHP version who built this object. Usefull pour serializing Election.
     * @api
     * @see Condorcet::getVersion
     * @param $major true will return 2.0 and false will return 2.0.0.
     * @return string Condorcet PHP version.
     */
    public function getCondorcetBuilderVersion(
        bool $major = false
    ): string {
        if ($major) {
            $version = explode('.', $this->buildByCondorcetVersion);
            return $version[0] . '.' . $version[1];
        } else {
            return $this->buildByCondorcetVersion;
        }
    }
}
