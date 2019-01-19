<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use app\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\growl\Growl;

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
    <link rel='shortcut icon' href='<?= Yii::$app->request->baseUrl ?>/favicon.ico' type='image/x-icon' />
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
                            <i class="fas fa-home"></i>&nbsp<span class="font-weight-normal">Budżet domowy</span>
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle"
                                aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-row-reverse" id="navbarToggle">
                            <?= Nav::widget([
                                'options' => ['class' => 'navbar-nav navbar-right'],
                                'items' => [
                                    ['label' => '<i class="fas fa-user-plus">&nbsp;</i>Rejestracja', 'url' => false, 'encode' => false,
                                        'linkOptions' => [
                                            'class' => 'text-light loadAjaxContent',
                                            'style' => 'cursor:pointer',
                                            'value' => Url::to(['/site/signup']),
                                            'icon' => '<i class="fas fa-sign"></i>',
                                            'modaltitle' => 'Rejestracja',
                                            'data-toggle' => "collapse", 'data-target' => '.navbar-collapse.show',
                                            'id' => 'main-signup'
                                        ],
                                        'visible' => Yii::$app->user->isGuest ? true : false
                                    ],
                                    ['label' => '<i class="fas fa-sign-in-alt">&nbsp;</i>Login', 'url' => false, 'encode' => false,
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
                                    ['label' => '<i class="fas fa-file-contract"></i>&nbsp;</i>Wgraj plik', 'url' => ['transactions/upload'], 'encode' => false,
                                        'linkOptions' => [
                                            'class' => 'text-light',
                                        ],
                                        'visible' => Yii::$app->user->isGuest ? false : true
                                    ],
                                    ['label' => '<i class="fas fa-chart-line"></i>&nbsp;</i>Transakcje', 'url' => ['transactions/index'], 'encode' => false,
                                        'linkOptions' => [
                                            'class' => 'text-light',
                                        ],
                                        'visible' => Yii::$app->user->isGuest ? false : true
                                    ],
                                    ['label' => '<i class="fas fa-sitemap"></i>&nbsp;</i>Kategorie', 'url' => ['category/index'], 'encode' => false,
                                        'linkOptions' => [
                                            'class' => 'text-light',
                                        ],
                                        'visible' => Yii::$app->user->isGuest ? false : true
                                    ],
                                    ['label' => '<i class="fas fa-sign-out-alt">&nbsp;</i>Wyloguj się', 'url' => ['/site/logout'], 'encode' => false,
                                        'linkOptions' => [
                                            'class' => 'text-light',
                                            'data-method' => 'post'
                                        ],
                                        'visible' => Yii::$app->user->isGuest ? false : true
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
        <!--Alerts Container-->
        <div id="alerts" class="text-center"
             style="position: absolute; top: 225px; left: 50%; transform: translate(-50%, -50%); z-index: 999; word-wrap:break-word">
            <?= Alert::widget() ?>
        </div>

        <section class="content">
            <?php foreach (Yii::$app->session->getAllFlashes() as $message): ?>
                <?= Growl::widget([
                    'id' => 'growl',
                    'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
                    'title' => (!empty($message['title'])) ? Html::encode($message['title']) : null,
                    'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
                    'body' => (!empty($message['message'])) ? Html::encode($message['message']) : null,
                    'showSeparator' => true,
                    'delay' => 1, //This delay is how long before the message shows
                    'pluginOptions' => [
                        'delay' => (!empty($message['duration'])) ? $message['duration'] : 5000, //This delay is how long the message shows for
                        'placement' => [
                            'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                            'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                        ]
                    ]
                ]);
                ?>
            <?php endforeach; ?>
        </section>
        <!--Alerts Container end-->

        <!--Main content render-->
        <?= $content ?>
        <!--Main content render end-->

        <!-- Utilities -->
        <?php Modal::begin([
            'options' => [
                'tabindex' => false,
                'class' => 'py-5 my-5',
                'style' => ''
            ],
            'header' => '<h4 id="modalHeaderTitle" class="text-light"></h4>',
            'closeButton' => ['label' => '<i class="fas fa-window-close text-light"></i>', 'tag' => 'i'],
            'headerOptions' => ['id' => 'modalHeader', 'class' => 'bg-dark', 'style' => 'display:block;'],
            'id' => 'modalAjax',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
        ]) ?>

        <div class="modalAjaxContent"></div>

        <?php Modal::end(); ?>
        <!-- Utilities END-->
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
