<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'О проекте';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Эта страница о проекте "Форма регистрации на олимпиаду".<br>
        Создатель проекта: Зорикто Балданов
    </p>

    <code><?= __FILE__ ?></code>
</div>
