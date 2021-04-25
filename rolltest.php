<?php
declare(strict_types=1);

use Fervo\Rollo\DiceExpressionParser;
use Fervo\Rollo\RollExpressionParser;

include __DIR__.'/vendor/autoload.php';

$dep = new DiceExpressionParser();
$rep = new RollExpressionParser($dep);

$expr = implode(' ', array_slice($argv, 1));

echo $expr . PHP_EOL;

$result = $rep->parseRollExpression($expr);
$result->setDeltaGreenMode(false);
$result->roll();

echo $result->getResultDescription() . PHP_EOL;
