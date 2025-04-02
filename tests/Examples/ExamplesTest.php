<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Condorcet;
beforeEach(function (): void {
    $this->condorcetDefaultMethod = Condorcet::getDefaultMethod();
});
afterEach(function (): void {
    Condorcet::$UseTimer = new ReflectionClass(Condorcet::class)->getProperty('UseTimer')->getDefaultValue();
    Condorcet::setDefaultMethod($this->condorcetDefaultMethod);
});

test('overview example', function (): void {
    include __DIR__.'/../../Examples/1. Overview.php';

    expect(true)->toBeTrue();
});

test('advanced object management example', function (): void {
    include __DIR__.'/../../Examples/2. AdvancedObjectManagement.php';

    expect(true)->toBeTrue();
});

test('global html example', function (): void {
    $this->expectOutputRegex('/\<\/html\>/');

    include __DIR__.'/../../Examples/Examples-with-html/A.Global_Example.php';
});

test('ranking manipulation html example', function (): void {
    $this->expectOutputRegex('/\<\/html\>/');

    include __DIR__.'/../../Examples/Examples-with-html/B.Ranking_Manipulation.php';
});
