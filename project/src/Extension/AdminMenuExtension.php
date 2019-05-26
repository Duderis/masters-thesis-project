<?php


namespace App\Extension;

use Twig\Extension\AbstractExtension;

class AdminMenuExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getAdminRoutes', [$this, 'getAdminRoutes'])
        ];
    }

    public function getAdminRoutes()
    {
        return $this->getRouteArray();
    }

    private function getRouteArray()
    {
        return [
            [
                'routeName' => 'neural_index',
                'label' => 'Analyses'
            ],
            [
                'routeName' => 'admin_teach',
                'label' => 'Teaching actions'
            ],
            [
                'routeName' => 'admin_teach_teachingData',
                'label' => 'Manage teaching data'
            ],
            [
                'routeName' => 'admin_teach_taughtModels',
                'label' => 'Configure taught models'
            ],
            [
                'routeName' => 'admin_run_learning',
                'label' => 'Launch learning'
            ],
            [
                'routeName' => 'admin_run_analyses',
                'label' => 'Launch analyses'
            ],
        ];
    }
}
