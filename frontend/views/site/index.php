<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg custom_nav-container ">
                <a class="navbar-brand" href="index.html">
            <span>
              Автопарк Профи
            </span>
                </a>

            </nav>
        </div>
    </header>
    <!-- end header section -->
    <!-- slider section -->
    <section class=" slider_section ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7 ">
                    <div class="box">
                        <div class="detail-box">
                            <h4>
                                Добро пожаловать в
                            </h4>
                            <h1>
                                Автопарк Профи
                            </h1>
                        </div>
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">

                            <div class="carousel-inner">
                                <div class="carousel-item active">

                                    <div class="img-box">
                                        <img src="images/slider-img.png" alt="">
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="img-box">
                                        <img src="images/slider-img-2.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-lg-4 col-md-5 ">
                    <div class="slider_form" style="color: #FFFFFF">
                        <h4 id="hraderText">
                            Связаться с нами
                        </h4>
                        <?php

                        $form = ActiveForm::begin(); ?>

                        <?= $form->field($model, 'username')->input('text') ?>

                        <?= $form->field($model, 'phone')->input('text') ?>

                        <?= $form->field($model, 'message')->textarea(['rows' => 3]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- end slider section -->
</div>
<section class="about_section layout_padding">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-5 offset-lg-2 offset-md-1">
                <div class="detail-box">
                    <h2>
                        О Нас <br>
                    </h2>
                    <p>
                        Компания «Автопарк Профи» предоставляет весь спектр услуг для водителей,
                        мы помогаем жителям Нижнего Тагила получить достойную и высокооплачиваемую работу в такси!
                        Вы можете взять машину в аренду недорого, или, если у вас есть свой автомобиль, мы поможем подключить
                        к наиболее популярному сервису такси Яндекс. Также мы предлагаем работу в такси компании
                        на Volkswagen Polo, Лада Гранта. У нас наиболее гибкие и выгодные условия по работе в такси в Нижнем Тагиле.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="img-box">
                    <img src="images/about-img.png" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end about section -->

<!-- service section -->

<section class="service_section layout_padding">
    <div class="container">
        <div class="heading_container">
            <h2>
                Наши <br>
                Услуги
            </h2>
        </div>
        <div class="service_container">
            <div class="box">
                <div class="img-box">
                    <img src="images/taxi-logo.png" alt="">
                </div>
                <div class="detail-box">
                    <h5>
                        Аренда автомобиля
                    </h5>
                    <p>
                        для работы в Такси Яндекс
                    </p>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="images/arenda.png" alt="">
                </div>
                <div class="detail-box">
                    <h5>
                        Авто под такси с Выкупом
                    </h5>
                    <p>
                        Посуточная аренда авто с <br>дальнейшим выкупом
                    </p>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="images/delivery-man.png" alt="">
                </div>
                <div class="detail-box">
                    <h5>
                        Подключение к сервисам
                    </h5>
                    <p>
                        Подключение к Такси Яндекс <br>для работы на Личном Авто
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end service section -->

<!-- why section -->

<section class="why_section layout_padding">
    <div class="container">
        <div class="heading_container">
            <h2>
                Почему <br>
                Выбирают Нас
            </h2>
        </div>
        <div class="why_container">
            <div class="box">
                <div class="img-box">
                    <img src="images/delivery-man-white.png" alt="" class="img-1">
                    <img src="images/delivery-man-black.png" alt="" class="img-2">
                </div>
                <div class="detail-box">
                    <h5>
                        Подключаем к диспетчерским. Обеспечиваем заказами.
                    </h5>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="images/shield-white.png" alt="" class="img-1">
                    <img src="images/shield-black.png" alt="" class="img-2">
                </div>
                <div class="detail-box">
                    <h5>
                        Удобный график. Бонусы
                    </h5>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="images/repairing-service-white.png" alt="" class="img-1">
                    <img src="images/repairing-service-black.png" alt="" class="img-2">
                </div>
                <div class="detail-box">
                    <h5>
                        Предрейсовое обслуживание авто
                    </h5>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- end why section -->

<?php
$js = <<<JS
    $('form').on('beforeSubmit', function(){
       var data = $(this).serialize();
        $.ajax({
            url: '/site/send-message-from-site',
            type: 'POST',
            data: data,
            success: function(res){
                $("#hraderText").html("Сообщение отправлено!")
                $("#messagesfromsite-username").val('')
                $("#messagesfromsite-phone").val('')
                $("#messagesfromsite-message").val('')
            },
            error: function(){
                $("#hraderText").html("Что то пошло не так!")
            }
        });
        return false;
    });
JS;

$this->registerJs($js);
?>