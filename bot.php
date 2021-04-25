<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Discord;

$discord = new Discord([
    'token' => getenv('DISCORD_BOT_TOKEN'),
]);

$discord->on('ready', function ($discord) {
    echo "Bot is ready!", PHP_EOL;

    // Listen for messages.
    $discord->on('message', function ($message, $discord) {
        echo "{$message->author->username}: {$message->content}",PHP_EOL;
    });
});

$discord->run();
