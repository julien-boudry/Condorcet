<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Condorcet;
beforeEach(function () {
    $this->condorcetDefaultMethod = Condorcet::getDefaultMethod();
});
afterEach(function () {
    Condorcet::$UseTimer = (new ReflectionClass(Condorcet::class))->getProperty('UseTimer')->getDefaultValue();
    Condorcet::setDefaultMethod($this->condorcetDefaultMethod);
});

test('overview example', function () {
    try {
        include __DIR__.'/../../Examples/1. Overview.php';
    } catch (\Exception $e) {
        throw $e;
    }

    expect(true)->toBeTrue();
});

test('advanced object management example', function () {
    try {
        include __DIR__.'/../../Examples/2. AdvancedObjectManagement.php';
    } catch (\Exception $e) {
        throw $e;
    }

    expect(true)->toBeTrue();
});

test('global html example', function () {
    $this->expectOutputRegex('/\<\/html\>/');

    include __DIR__.'/../../Examples/Examples-with-html/A.Global_Example.php';
});

test('ranking manipulation html example', function () {
    $this->expectOutputRegex('/\<\/html\>/');

    include __DIR__.'/../../Examples/Examples-with-html/B.Ranking_Manipulation.php';
});
