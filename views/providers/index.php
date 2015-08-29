<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\providersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Добавление прайсов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="providers-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить поставщика', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            [
                'attribute' => 'test',
                'label' => '',
                'format' => 'html',
                'value' => function($model) {
                    return Html::a('Загрузить прайс', ['priceload/index', 'providerId' => $model->id], ['class' => 'btn btn-success']);
                }
                    ],
                    /* 'meta:ntext', */
                    'name',
                    // 'id_script',
//                    'date_last_down',
                    [
                        'attribute' => 'date_last_down',
                        'label' => 'Статус загрузки прайса',
                        'format' => 'html',
                        'value' => function($model) {
                            $lastUpdate = ($model->date_last_down) ? (date('Y.m.d H:i', $model->date_last_down) . '&nbsp;&nbsp;&nbsp;') : 'Не загружено';
                            if(is_file(\Yii::$app->params['uploadFolder'] . $model->id . '.csv')){
                                $status = Html::a('Продолжить разбор', ['priceload/ok-collizion', 'providerId' => $model->id], ['class' => 'btn btn-primary']);
                            }else{
                                $status = '';
                            }
                            return $lastUpdate. $status;
                        }
                            ],
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}',],
                        ],
//        'view' => 'row'
                    ]);
                    ?>

</div>
