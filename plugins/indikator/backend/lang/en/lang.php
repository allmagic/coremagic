<?php

return [
    'plugin' => [
        'name' => 'Backend Plus',
        'description' => 'New useful features and widgets for backend.',
        'author' => 'Gergő Szabó'
    ],
    'settings' => [
        'search_label' => 'Focus the search field automatically.',
        'sidebar_label' => 'Hide the menu description in sidebar.',
        'avatar_label' => 'Rounded profile image instead of cube one.',
        'avatar_comment' => 'The avatar modifies only in top menu.',
        'themes_label' => 'Hide the "Find more themes" link.',
        'themes_comment' => 'Located on Front-end theme page.',
        'keyboard_label' => 'Show the virtual keyboard by forms.',
        'keyboard_comment' => 'It only works by simple text fields.'
    ],
    'widgets' => [
        'system' => [
            'label' => 'System status plus',
            'webpage' => 'Webpage',
            'updates' => 'Updates',
            'plugins' => 'Plugins',
            'themes' => 'Themes'
        ],
        'version' => [
            'label' => 'Versions',
            'cms' => 'CMS',
            'php' => 'PHP',
            'gd' => 'GD'
        ],
        'logs' => [
            'label' => 'Logs',
            'access' => 'Access',
            'event' => 'Event',
            'request' => 'Request',
            'total' => 'Total'
        ],
        'admins' => [
            'label' => 'Administrators',
            'users' => 'Users',
            'groups' => 'Groups',
            'login' => 'Last login'
        ],
        'logins' => [
            'label' => 'Last logins'
        ],
        'server' => [
            'label' => 'Server info',
            'host' => 'Host',
            'ip' => 'IP address',
            'os' => 'Operation system'
        ],
        'php' => [
            'label' => 'PHP settings',
            'upload_limit' => 'Upload limit',
            'memory_limit' => 'Memory limit',
            'timezone' => 'Timezone'
        ],
        'rss' => [
            'label' => 'RSS viewer',
            'error' => 'The URL address is incorrect or',
            'refresh' => 'refresh page'
        ],
        'images' => [
            'label' => 'Random images',
            'error' => 'The field can only contain numbers.',
            'simple' => 'Simple',
            'slideshow' => 'Slideshow'
        ]
    ],
    'properties' => [
        'webpage' => 'Show webpage',
        'updates' => 'Show updates',
        'plugins' => 'Show plugins',
        'themes' => 'Show themes',
        'cms' => 'Show CMS version',
        'php' => 'Show PHP version',
        'gd' => 'Show GD version',
        'access' => 'Show access',
        'event' => 'Show event',
        'request' => 'Show request',
        'total' => 'Show total',
        'users' => 'Show users',
        'groups' => 'Show groups',
        'login' => 'Show last login',
        'logins' => 'Show the number of logins',
        'url' => 'Show URL address',
        'ip' => 'Show IP address',
        'os' => 'Show operation system',
        'upload_limit' => 'Show upload limit',
        'memory_limit' => 'Show memory limit',
        'timezone' => 'Show timezone',
        'rss_title' => 'RSS url',
        'rss_url' => 'http://feeds.bbci.co.uk/news/rss.xml',
        'news' => 'Number of news',
        'date' => 'Show date',
        'description' => 'Show description',
        'category' => 'Category',
        'type' => 'Type',
        'number' => 'Images in slideshow',
        'width' => 'Width (in pixel)',
        'height' => 'Height (in pixel)',
        'text' => 'Text'
    ],
    'category' => [
        'all' => 'All',
        'abstract' => 'Abstract',
        'animals' => 'Animals',
        'business' => 'Business',
        'cats' => 'Cats',
        'city' => 'City',
        'food' => 'Food',
        'nightlife' => 'Nightlife',
        'fashion' => 'Fashion',
        'people' => 'People',
        'nature' => 'Nature',
        'sports' => 'Sports',
        'technics' => 'Technics',
        'transport' => 'Transport'
    ],
    'component' => [
        'image' => [
            'name' => 'Lorem ipsum image',
            'description' => ''
        ],
        'text' => [
            'name' => 'Lorem ipsum text',
            'description' => '',
            'length' => 'Length',
            'bit' => 'a bit',
            'some' => 'some',
            'lots' => 'lots'
        ]
    ]
];
