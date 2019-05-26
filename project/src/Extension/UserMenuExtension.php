<?php


namespace App\Extension;

use Twig\Extension\AbstractExtension;

class UserMenuExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getUserRoutes', [$this, 'getUserRoutes'])
        ];
    }

    public function getUserRoutes()
    {
        return $this->getRouteArray();
    }

    private function getRouteArray()
    {
        return [
            [
                'routeName' => 'neural_index',
                'label' => 'Analyses'
            ]
        ];
    }
}
