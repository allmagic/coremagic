<?php
/**
 * Created by PhpStorm.
 * User: Leonardo Shinagawa
 * Date: 15/03/15
 * Time: 18:10
 */

return [
    'plugin' => [
        'description' => 'RainLab.User extension to login and register with Facebook'
    ],

    'facebook_session' => [
        'description' => 'Use this session instead of Rainlab.User\'s Session.'
    ],

    'permissions' => [
        'settings' => [
            'facebook' => 'Manager facebook token'
        ]
    ]
];