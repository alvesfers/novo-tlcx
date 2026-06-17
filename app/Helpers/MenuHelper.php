<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getSystemItems()
    {
        return [
            [
                'icon' => 'home',
                'name' => 'Dashboard',
                'path' => '/',
            ],
            [
                'icon' => 'building-office-2',
                'name' => 'Estrutura',
                'subItems' => [
                    ['name' => 'Dioceses', 'path' => '/dioceses'],
                    ['name' => 'Núcleos', 'path' => '/nucleos'],
                    ['name' => 'Secretarias', 'path' => '/secretarias'],
                    ['name' => 'Dirigentes', 'path' => '/dirigentes'],
                ],
            ],
            [
                'icon' => 'calendar-days',
                'name' => 'Eventos',
                'path' => '/eventos',
            ],
            [
                'icon' => 'tag',
                'name' => 'Tipos de Evento',
                'path' => '/tipo-eventos',
            ],
            [
                'icon' => 'user-plus',
                'name' => 'Participantes Externos',
                'path' => '/participante-externos',
            ],
            [
                'icon' => 'banknotes',
                'name' => 'Financeiro',
                'subItems' => [
                    ['name' => 'Resumo Financeiro', 'path' => '/financeiro/resumo'],
                    ['name' => 'Movimentações', 'path' => '/financeiro-movimentos'],
                    ['name' => 'Categorias', 'path' => '/financeiro-categorias'],
                ],
            ],
            [
                'icon' => 'chart-bar',
                'name' => 'Relatórios',
                'subItems' => [
                    ['name' => 'Financeiro', 'path' => '/relatorios/financeiro'],
                    ['name' => 'Eventos', 'path' => '/relatorios/eventos'],
                    ['name' => 'Dirigentes', 'path' => '/relatorios/dirigentes'],
                ],
            ],
            [
                'icon' => 'clipboard-document-check',
                'name' => 'Auditoria',
                'path' => '/auditoria',
            ],
            [
                'icon' => 'qr-code',
                'name' => 'Check-in',
                'path' => '#',
            ],
            [
                'icon' => 'code-bracket-square',
                'name' => 'API',
                'path' => '#',
            ],
        ];
    }

    public static function getTailAdminItems()
    {
        return [
            [
                'icon' => 'square-3-stack-3d',
                'name' => 'TailAdmin',
                'subItems' => [
                    ['name' => 'Calendar', 'path' => '/calendar'],
                    ['name' => 'User Profile', 'path' => '/profile'],
                    ['name' => 'Forms', 'path' => '/form-elements'],
                    ['name' => 'Tables', 'path' => '/basic-tables'],
                    ['name' => 'Pages', 'path' => '/blank'],
                    ['name' => 'Charts', 'path' => '/line-chart'],
                    ['name' => 'UI Elements', 'path' => '/alerts'],
                    ['name' => 'Authentication', 'path' => '/signin'],
                    ['name' => 'Alerts', 'path' => '/alerts'],
                    ['name' => 'Avatars', 'path' => '/avatars'],
                    ['name' => 'Badge', 'path' => '/badge'],
                    ['name' => 'Buttons', 'path' => '/buttons'],
                    ['name' => 'Images', 'path' => '/image'],
                    ['name' => 'Videos', 'path' => '/videos'],
                    ['name' => 'Blank', 'path' => '/blank'],
                    ['name' => 'Error 404', 'path' => '/error-404'],
                    ['name' => 'Line Chart', 'path' => '/line-chart'],
                    ['name' => 'Bar Chart', 'path' => '/bar-chart'],
                ],
            ],
        ];
    }

    public static function getMainNavItems()
    {
        return self::getSystemItems();
    }

    public static function getOthersItems()
    {
        return [];
    }

    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Sistema',
                'items' => self::getSystemItems()
            ],
            [
                'title' => 'Referências TailAdmin',
                'items' => self::getTailAdminItems()
            ]
        ];
    }

    public static function isActive($path)
    {
        return request()->is(ltrim($path, '/'));
    }

    public static function getIconSvg($iconName)
    {
        return $iconName;
    }

    public static function getHeroiconComponent($iconName)
    {
        $iconMap = [
            'home' => 'heroicon-o-home',
            'building-office-2' => 'heroicon-o-building-office-2',
            'users' => 'heroicon-o-users',
            'calendar-days' => 'heroicon-o-calendar-days',
            'tag' => 'heroicon-o-tag',
            'user-plus' => 'heroicon-o-user-plus',
            'banknotes' => 'heroicon-o-banknotes',
            'chart-bar' => 'heroicon-o-chart-bar',
            'clipboard-document-check' => 'heroicon-o-clipboard-document-check',
            'qr-code' => 'heroicon-o-qr-code',
            'code-bracket-square' => 'heroicon-o-code-bracket-square',
            'square-3-stack-3d' => 'heroicon-o-square-3-stack-3d',
        ];

        return $iconMap[$iconName] ?? 'heroicon-o-circle-stack';
    }
}
