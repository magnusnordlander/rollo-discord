<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Fervo\Rollo\DiceExpressionParser;
use Fervo\Rollo\DieInterface;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/.env');

$parser = new DiceExpressionParser();

$discord = new Discord([
    'token' => $_ENV['DISCORD_BOT_TOKEN'],
]);

$discord->on('ready', function (Discord $discord) use ($parser) {
    echo "Bot is ready!", PHP_EOL;

    // Listen for messages.
    $discord->on('message', function (Message $message, Discord $discord) use ($parser) {
        if (preg_match("/^!roll (.*)/", $message->content, $matches)) {
            try {
                /** @var DieInterface $die */
                $die = $parser->parseExpression($matches[1]);
                $die->roll();
                $message->reply(sprintf("%s rolled %s", $message->author->username, $die->getValueDescription()));
            } catch (\Exception $e) {
                $message->reply("I'm sorry, what now? ".get_class($e).': '.$e->getMessage());
            }
        }
    });
});

$discord->run();
