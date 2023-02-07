<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Device Create';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    

    <div class="body-content">
        <?php 
            $form = ActiveForm::begin();
        ?>
        <div class="row">
            <div class="form-group">
                <div class="col-lg-6">
                    <?= $form->field($device, 'ip_address');?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class="col-lg-6">
                    <?= $form->field($device, 'hostname');?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class="col-lg-6">
                    <?= $form->field($device, 'serial');?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <div class="col-lg-6">
                    <div class="col-lg-3">
                        <?= Html::submitButton('Add Device', ['class'=> 'btn btn-primary']);?>
                    </div>
                    <br>
                    <div class="col-lg-2">
                        <?= Html::a('Back', ['site/device'],['class'=>'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            $form = ActiveForm::end();
        ?>
    </div>

    
</div>
