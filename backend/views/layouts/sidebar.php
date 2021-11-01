<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= Yii::$app->user->identity->username;?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php

            $items = [
                ['label' => 'Dashboard', 'icon' => 'tachometer-alt', 'url' => '/admin/site/index'],
                ['label' => 'Автомобили', 'icon' => 'tachometer-alt', 'url' => '/admin/cars/index'],
                ['label' => 'Водители', 'icon' => 'far fa-user', 'url' => '/admin/driver/index'],
                ['label' => 'Табель', 'icon' => 'far fa-user', 'url' => '/admin/driver-tabel/index'],
                ['label' => 'Касса', 'icon' => 'far fa-user', 'url' => '/admin/cash-register/index'],
                ['label' => 'Штрафы ДПС', 'icon' => 'far fa-user', 'url' => '/admin/debt-fines/index'],
                ['label' => 'Телефоны', 'icon' => 'far fa-user', 'url' => '/admin/phones/index'],

            ];
            if (\common\models\User::isSuperUser()) {
                array_push($items, [
                    'label' => 'Настройки',
                    'icon' => 'tachometer-alt',
                    'items' => [
                        ['label' => 'Общие', 'icon' => 'far fa-user', 'url' => '/admin/settings/update'],
                        ['label' => 'Планы', 'icon' => 'far fa-user', 'url' => '/admin/day-plans/index'],
                        ['label' => 'Проценты', 'icon' => 'far fa-user', 'url' => '/admin/calculation/index'],
                        ['label' => 'Компенсации', 'icon' => 'far fa-user', 'url' => '/admin/compensation/index'],
                    ]
                ]);
            }
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => $items,
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>