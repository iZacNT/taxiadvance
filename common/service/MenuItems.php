<?php

namespace common\service;

use yii\base\Component;

class MenuItems extends Component
{

    public function getMenuItems()
    {
        $item = [
            ['label' => 'Dashboard', 'icon' => 'tachometer-alt', 'url' => '/admin/site/index'],
            ['label' => 'Автомобили', 'icon' => 'tachometer-alt', 'url' => '/admin/cars/index']
        ];

        $operator = [
            ['label' => 'Водители', 'icon' => 'far fa-user', 'url' => '/admin/driver/index'],
            ['label' => 'Табель', 'icon' => 'far fa-user', 'url' => '/admin/driver-tabel/index'],
            ['label' => 'Касса', 'icon' => 'far fa-user', 'url' => '/admin/cash-register/index'],
            ['label' => 'Штрафы ДПС', 'icon' => 'far fa-user', 'url' => '/admin/debt-fines/index'],
        ];

        $manager = [
            ['label' => 'Телефоны', 'icon' => 'far fa-user', 'url' => '/admin/phones/index'],
            ['label' => 'Биллинг', 'icon' => 'far fa-user', 'url' => '/admin/driver-billing/index'],
        ];

        $mechanic = [

                ['label' => 'Склад', 'icon' => 'far fa-user', 'url' => '/admin/stock/index'],
                ['label' => 'Детали', 'icon' => 'far fa-user', 'url' => '/admin/parts/index'],
                ['label' => 'Ремонты', 'icon' => 'far fa-user', 'url' => '/admin/car-repairs/index']
        ];

        $superUser = [
            [
                'label' => 'Настройки',
                'icon' => 'tachometer-alt',
                'items' => [
                    ['label' => 'Общие', 'icon' => 'far fa-user', 'url' => '/admin/settings/update'],
                    ['label' => 'Планы', 'icon' => 'far fa-user', 'url' => '/admin/day-plans/index'],
                    ['label' => 'Проценты', 'icon' => 'far fa-user', 'url' => '/admin/calculation/index'],
                    ['label' => 'Компенсации', 'icon' => 'far fa-user', 'url' => '/admin/compensation/index'],
                    ['label' => 'Менеджеры/Операторы', 'icon' => 'far fa-user', 'url' => '/admin/manager/index'],
                    ['label' => 'Технический осмотр', 'icon' => 'far fa-user', 'url' => ['/settings-to/update', 'id' => '1']],
                ]
            ]
        ];

        if (\common\models\User::isSuperUser()) {
            $item = array_merge($item, $operator, $manager, $mechanic, $superUser);
        }

        if (\common\models\User::isManager()) {
            $item = array_merge($item, $operator, $manager);
        }

        if (\common\models\User::isOperator()) {
            $item = array_merge($item, $operator);
        }

        if (\common\models\User::isMechanic()) {
            $item = array_merge($item, $mechanic);
        }

        \Yii::debug($item, __METHOD__);

        return $item;
    }
}