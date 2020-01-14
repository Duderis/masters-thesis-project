<?php


namespace App\Extension;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Extension\AbstractExtension;
use Twig_SimpleFunction;

class MenuExtension extends AbstractExtension
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * MenuExtension constructor.
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(AuthorizationCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('getRoutes', [$this, 'getRoutes'])
        ];
    }

    public function getRoutes()
    {
        return $this->getRouteArray();
    }

    private function getRouteArray()
    {
        if ($this->checker->isGranted('ROLE_ADMIN')) {
            return $this->getAdminRoutes();
        }
        if ($this->checker->isGranted('ROLE_USER')) {
            return $this->getUserRoutes();
        }
        return [];
    }

    private function getAdminRoutes()
    {
        return [
            [
                'routeName' => 'neural_index',
                'label' => 'Analyses'
            ],
            [
                'routeName' => 'admin_teach_teachingData',
                'label' => 'Manage teaching data',
                'subRoutes' => [
                    [
                        'routeName' => 'teachingData_segmentation',
                        'label' => 'Segmentation',
                    ],
                    [
                        'routeName' => 'teachingData_classification',
                        'label' => 'Classification'
                    ]
                ]
            ],
            [
                'routeName' => 'admin_teach',
                'label' => 'Teaching actions'
            ],
            [
                'routeName' => 'admin_taught_index',
                'label' => 'Manage taught models'
            ],
            [
                'routeName' => 'admin_analyses_index',
                'label' => 'Analyses actions'
            ],
        ];
    }

    private function getUserRoutes()
    {
        return [
            [
                'routeName' => 'neural_index',
                'label' => 'Analyses'
            ]
        ];
    }
}
