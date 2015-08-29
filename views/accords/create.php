<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Accords */

$this->title = 'Create Accords';
$this->params['breadcrumbs'][] = ['label' => 'Accords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accords-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
