<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DiscountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Цена "' . (($typePriceName) ? $typePriceName : '---не задана---') . '" поставщика "' . $providerName . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discounts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>


    <p>
        <?php
        foreach ($arrPriceType as $k => $v) {
            print Html::a($v, ['index', 'typePrice' => $k], ['class' => ('btn btn-primary' . (($typePrice == $k) ? ' active' : ''))]) . '&nbsp;';
        }
        ?>
    </p>
    <?php if ($typePrice) { ?>

        <p>
            <?= Html::a('Добавить наценку', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
//            'id',
//            'providers_id',
                [
                    'attribute' => 'goods_type_type',
                    'label' => 'Тип товара',
                    'format' => 'html',
                    'value' => function($model) {
                        $oGT = \app\models\GoodsType::findOne($model->goods_type_type);
                        return '[' . $oGT->type . '] <b>' . $oGT->name . '</b>';
                    }
                ],
                [
                    'attribute' => 'test1',
                    'label' => 'Параметр',
                    'format' => 'html',
                    'value' => function($model) {
                        $arP = explode(';', $model->params);
                        $arV = explode(';', $model->values);
                        $arPP = [];
                        foreach ($arP as $key => $paramId) {
                            $oPar = \app\models\GoodsParamsName::findOne($paramId);
                            $oVal = app\models\GoodsParams::findOne($arV[$key]);
                            if ($oPar instanceof \app\models\GoodsParamsName && $oVal instanceof app\models\GoodsParams)
                                $arPP[] = $oPar->name . ': <b>' . $oVal->value . '</b>';
                        }
                        return implode('; ', $arPP);
                    }
                    ],
                    [
                        'attribute' => 'coef1',
                        'label' => 'Коэффициент',
                        'format' => 'html',
                        'value' => function($model) {
                            return $model->coef;
                        }
                    ],
//            'params',
//            'values',
//            'coef',
                    ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
                ],
            ]);
            ?>
        <?php } ?>

</div>
