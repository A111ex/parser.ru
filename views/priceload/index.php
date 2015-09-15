use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'fileName')->fileInput() ?>

    <button class="btn btn-success">Загрузить</button>

<?php ActiveForm::end() ?>