<?php

namespace App\Global;

use App\Enums\ApplicationTypes;
use App\Models\Country;

class Menus
{
    public static $applications = [
        'title' => 'Applications',

        'options' => [

            [
                'label' => 'Applications',
                'children' => 
                [ 
                  ['label' => 'Application List', 'route' => 'applications.index' ],
                  ['label' => 'Application Types', 'route' => 'applicationTypes.index' ],
                ],
            ],
            [
              'label' => 'Local driving licence (LDL)',
              'children' => 
              [ 
                ['label' => 'Applications List',   'route' => 'localLicence.index'],
                ['label' => 'New LDL application', 'route' => 'localLicence.create'],
                ['label' => 'Application details', 'route' => 'localLicence.show' ],
              ],
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

    public static function licenceOperations(int $licenceId) {
      $release = ApplicationTypes::ReleaseDetained->value;
      $renew = ApplicationTypes::RenewLicence->value;
      $lost = ApplicationTypes::LostReplacement->value;
      $damaged = ApplicationTypes::DamagedReplacement->value;
      
      $releaseRoute = "/licenceOperationApplication/{$licenceId}/{$release}";
      $lostRoute    = "/licenceOperationApplication/{$licenceId}/{$lost}";
      $renewRoute   = "/licenceOperationApplication/{$licenceId}/{$renew}";
      $damagedRoute = "/licenceOperationApplication/{$licenceId}/{$damaged}";

      return 
      [
        'title' => 'Licence Services Application',
        'options' => [
          [
            'label' => 'Release detained licence',
            'route' => $releaseRoute,
          ],
          [
            'label' => 'Renew licence',
            'route' => $renewRoute,
          ],
          [
            'label' => 'Rplacement for damaged',
            'route' => $damagedRoute,
          ],
          [
            'label' => 'Replacement for lost',
            'route' => $lostRoute,
          ],
        ]
      ];
    }
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