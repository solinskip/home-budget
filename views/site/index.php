<?php

$this->title = '';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Congratulations!</h1>
        <h2><?= Yii::$app->user->isGuest ? 'Nie zalogowany' : 'Zalogowany ' . Yii::$app->user->identity->username ?></h2>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

    </div>
</div>
