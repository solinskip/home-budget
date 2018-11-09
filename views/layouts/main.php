<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\nav\NavX;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="position-fixed w-100" style="z-index: 1080">
        <div class="hover-nav sticky-top">
            <div class="container-fluid">
                <div class="align-items-stretch col-12 px-0">
                    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                        <a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>">
                            <i class="fas fa-hand-holding-usd"></i>&nbsp;Budżet domowy
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle"
                                aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-row-reverse" id="navbarToggle">
                            <?= Nav::widget([
                                'options' => ['class' => 'navbar-nav navbar-right'],
                                'items' => [
                                    ['label' => '<i class="fas fa-user-plus d-xl-none">&nbsp;</i>Rejestracja', 'url' => false, 'encode' => false,
                                        'linkOptions' => [
                                            'class' => 'text-light loadAjaxContent',
                                            'style' => 'cursor:pointer',
                                            'value' => Url::to(['/site/signup']),
                                            'icon' => '<i class="fas fa-sign"></i>',
                                            'modaltitle' => 'Signup',
                                            'data-toggle' => "collapse", 'data-target' => '.navbar-collapse.show',
                                            'id' => 'main-signup'
                                        ],
                                        'visible' => Yii::$app->user->isGuest ? true : false
                                    ],
                                    ['label' => '<i class="fas fa-sign-in-alt d-xl-none">&nbsp;</i>Login', 'url' => false, 'encode' => false,
                                        'linkOptions' => [
                                            'id' => 'btn-login',
                                            'class' => 'text-light loadAjaxContent',
                                            'style' => 'cursor:pointer',
                                            'value' => Url::to(['/site/login']),
                                            'icon' => '<i class="fas fa-lock"></i>',
                                            'modaltitle' => 'Login',
                                            'data-toggle' => "collapse", 'data-target' => '.navbar-collapse.show'
                                        ],
                                        'visible' => Yii::$app->user->isGuest ? true : false
                                    ],
                                ]
                            ]);
                            ?>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
