<?php

$properties = collect([
    'available' => [
        'title_icon' => 'green',
        'title' => 'Already confirmed',
        'body_icon' => 'smile-o',
        'body' => 'Place reserved',
        'orderList' => 1, // numbering the player on the game
        'order' => 1, // show first row
    ],
    'waiting' => [
        'title_icon' => 'yellow',
        'title' => 'Waiting',
        'body_icon' => 'smile-o',
        'body' => 'Place reserved',
        'orderList' => 1, // numbering the player on the game
        'order' => 2, // show second row
    ],
    'unavailable' => [
        'title_icon' => 'red',
        'title' => 'Unavailable',
        'body_icon' => 'smile-o',
        'body' => 'Place reserved',
        'orderList' => 1, // numbering the player on the game
        'order' => 3, // show third row
    ]
]);