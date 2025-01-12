<?php

use PioneerDynamics\AddressAutocomplete\Services\AddressAutoCompletion\Radar;
use PioneerDynamics\AddressAutocomplete\Services\AddressAutoCompletion\Google;
use PioneerDynamics\AddressAutocomplete\Services\AddressAutoCompletion\Mapbox;

return [
    'default' => env('ADDRESS_AUTOCOMPLETION_PROVIDER', 'radar'),

    'providers' => [

        'radar' => [
            'class' => Radar::class,
            'config' => [
                'api_key' => env('ADDRESS_AUTOCOMPLETION_RADAR_API_KEY'),
                'endpoint' => env('ADDRESS_AUTOCOMPLETION_RADAR_ENDPOINT', 'https://api.radar.io/v1/search/autocomplete'),
            ]
        ],

        'mapbox' => [
            'class' => Mapbox::class,
            'config' => [
                'api_key' => env('ADDRESS_AUTOCOMPLETION_MAPBOX_API_KEY'),
                'endpoint' => env('ADDRESS_AUTOCOMPLETION_MAPBOX_ENDPOINT', 'https://api.mapbox.com/search/geocode/v6'),
            ]
        ],
        
        'google' => [
            'class' => Google::class,
            'config' => [
                'api_key' => env('ADDRESS_AUTOCOMPLETION_GOOGLE_API_KEY'),
                'endpoint' => env('ADDRESS_AUTOCOMPLETION_GOOGLE_ENDPOINT', 'https://maps.googleapis.com/maps/api/place'),
            ]
        ],
    ],
];