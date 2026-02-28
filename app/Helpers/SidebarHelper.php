<?php

namespace App\Helpers;

class SidebarHelper
{
    /**
     * Set active status for sidebar links.
     */
    public static function setActive(array $routes): string
    {
        foreach ($routes as $route) {
            if (request()->routeIs($route) || request()->fullUrl() === url($route)) {
                return 'menu-item-active';
            }
        }

        return 'menu-item-inactive';
    }
}
