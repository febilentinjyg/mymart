<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\base\Controller;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">
    <style type="text/css">
        .col-md-2{
            padding: 10px;
        }
        .cardView{
            background-color: #FFF;
            padding: 25px;
            box-shadow: 0px 0px 5px #5bc0de;
            border-radius: 1px;
        }
        .price{
            color: #FF0000;
            font-size: 12pt;
        }
        .circle{
            background-color: #CCFF00;
            border-radius: 100%;
            color: #000;
            border: none;
            box-shadow: 2px 2px #eee;
        }
        .category{
            color: #FFF;
        }
    </style>
    
    <h1 align="center"><?= Html::encode('List '.$this->title) ?></h1>
    
<!--    <hr style="background-color: #5bc0de; height: 2px;">-->
</div>

    
    
<!--    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'price',
            'category_id',
            'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?> -->

<!-- <?php //Yii::$app->myComponent->trigger(\common\components\MyComponent::EVENT_AFTER_SOMETHING); ?> -->

    <?php
        echo "<b>SORT BY : </b>".$sort->link('category') .'  '. $sort->link('name') .'  '. $sort->link('price');
    ?>

    <?php //$this->renderPartial('_search', ['models' => $models,]) ?>

    <div class="row" style="margin-top: 10px;">
        <?php foreach ($models as $model): ?>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="cardView">
                    <?php
                        $category_id = $model->category_id;
                        $query = new \yii\db\Query();
                        $category_name = $query->select(['name'])
                            ->from('item_category')
                            ->where(['id' => $category_id])
                            ->one()['name'];
                        echo '<div align="center"><span class="label label-success">'.$category_name.'</span></div>';
                    ?>
                    <p align="center" style="margin-top:10px;"><?= Html::img(Url::to('../../../backend/web/'.$model->photo), ['width' => '100px', 'height' => '100px']) ?></p>
                    <div align="center" class="price"><b><?php echo $model->price; ?></div>
                    <div align="center"><?php echo $model->name; ?></div>
                    <div align="center">
                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id, 'name' => $model->name], ['class' => 'circle btn btn-info']) ?>
                    <?php
                        $query = new \yii\db\Query();
                        $customer = $query->select(['id_customer'])
                            ->from('customer')
                            ->where(['user_id' => Yii::$app->user->id])
                            ->one()['id_customer'];
                    ?>
                    <?php if(!$customer==null) { ?>
                    <?= Html::a('<span class="glyphicon glyphicon-shopping-cart"></span>', 
                        ['order', 'customer_id' => $customer, 'item_id' => $model->id], ['class' => 'circle btn btn-danger']) 
                    ?>
                    <?php } ?>
                    <?= Html::a('<span class="glyphicon glyphicon-star"></span>', ['#', 'id' => $model->id], ['class' => 'circle btn btn-warning']) ?>   
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <center><?php echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]); ?>
        </center>
    </div>
</div>
