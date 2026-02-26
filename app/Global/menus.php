<?php

namespace App\Global;

use App\Models\Country;

class Menus
{
    public static $applications = [
        'title' => 'Applications',

        'options' => [

            [
                'label' => 'Application Types',
                'route' => 'applicationTypes.index',
            ],

            [
                'label' => 'Applications',
                'route' => 'applications.index',
            ],

            [
                'label' => 'New local driving licence',
                'route' => 'LocalLicence.create',
            ],

            [
                'label' => 'Show licence info',
                'route' => 'LocalLicence.show',
            ],

            [
                'label' => 'Local driving licences',
                'route' => 'LocalLicence.index',
            ],

            [
                'label' => 'Replacement for damaged licence',
                'route' => 'LocalLicence.create',
            ],

            [
                'label' => 'Replacement for lost licence',
                'route' => 'LocalLicence.create',
            ],
        ],
    ];


    public static $people = [
        'title' => 'People',

        'options' => [

            [
                'label' => 'People info',
                'route' => 'person.index',
            ],

            [
                'label' => 'New person',
                'route' => 'person.create',
            ],

            [
                'label' => 'Show Person info',
                'route' => 'person.show',
            ],

            [
                'label' => 'Edit Person info',
                'route' => 'person.edit',
            ],
        ],
    ];


    public static $users = [
        'title' => 'Users',

        'options' => [

            [
                'label' => 'Users info',
                'route' => 'user.index',
            ],

            [
                'label' => 'New user',
                'route' => 'user.create',
            ],

            [
                'label' => 'Edit user info',
                'route' => 'profile.edit',
            ],
        ],
    ];


    public static $tests = [
        'title' => 'Schedule tests',

        'options' => [

            [
                'label' => 'Vision test',
                'route' => 'appointments.create',
            ],

            // If later you want nested:
            /*
            [
                'label' => 'Written test',
                'route' => 'appointments.create',
            ],
            [
                'label' => 'Street test',
                'route' => 'appointments.create',
            ],
            */
        ],
    ];
}