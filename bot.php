<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Fervo\Rollo\DiceExpressionParser;
use Fervo\Rollo\DieInterface;
use Fervo\Rollo\RollExpressionParser;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/.env');

$diceParser = new DiceExpressionParser();
$rollParser = new RollExpressionParser($diceParser);

$discord = new Discord([
    'token' => $_ENV['DISCORD_BOT_TOKEN'],
]);

$discord->on('ready', function (Discord $discord) use ($rollParser) {
    echo "Bot is ready!", PHP_EOL;

    // Listen for messages.
    $discord->on('message', function (Message $message, Discord $discord) use ($rollParser) {
        try {
            if (preg_match("/^!roll (.*)/", $message->content, $matches)) {
                $roll = $rollParser->parseRollExpression($matches[1]);
                $roll->roll();
                $message->reply(sprintf("%s rolled %s", $message->author->username, $roll->getResultDescription()));
            } elseif (preg_match("/^!dgroll (.*)/", $message->content, $matches)) {
                $roll = $rollParser->parseRollExpression($matches[1]);
                $roll->setDeltaGreenMode(true);
                $roll->roll();
                $message->reply(sprintf("%s rolled %s", $message->author->username, $roll->getResultDescription()));
            }
        } catch (\Exception $e) {
            $message->reply("I'm sorry, what now? ".get_class($e).': '.$e->getMessage());
        }
    });
});

$discord->run();
