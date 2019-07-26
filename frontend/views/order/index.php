<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Order\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?php //Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->


    <table class="table table-striped" style="padding: 10px;">
        <tr>
            <!-- <th>Nomor Order</th> -->
            <th>Item Image</th>
            <th>Item Name</th>
            <th>Item Harga</th>
            <th></th>
        </tr>
        <?php
            $query = new \yii\db\Query();
            // CARA 1

            $showId = $query->select(['customer_id', 'id_order'])
                ->from('order')
                //->orderBy('id_order')
                ->all();
                foreach ($showId as $key => $value) {
                    $showNama = $query->select(['nama'])
                        ->from('customer')
                        ->where(['id_customer' => ''.$value['customer_id'].''])
                        ->andWhere(['user_id' => Yii::$app->user->id])
                        ->all();

                        if(!$showNama==''){
                            foreach ($showNama as $key => $val) {
                            // echo '<td>'.$value['id_order'].'</td>';
                            //echo '<td>'.$val['nama'].'</td>';
                            }
                            $showOrderId = $query->select(['item_id'])
                                ->from('order_item')
                                ->where(['order_id' => ''.$value['id_order'].'' ])
                                ->all();
                                foreach ($showOrderId as $key => $value) {
                                    $showNameItem = $query->select(['*'])
                                        ->from('item')
                                        ->where(['id' => ''.$value['item_id'].'' ])
                                        ->all();
                                    foreach ($showNameItem as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>';
                                        echo "".
                                             Html::img(Url::to('../../../backend/web/'.$value['photo']), ['width' => '100px', 'height' => '100px']).
                                        "";
                                    echo '</td>';
                                    echo '<td>';
                                        echo "".
                                             $value['name'].
                                        "";
                                    echo '</td>';
                                    echo '<td>';
                                    echo "".
                                             $value['price'].
                                        "";
                                    echo '</td>';
                                    echo '<td>';
                                        echo "".
                                             Html::a('Check Out', ['index'], ['class' => 'btn btn-danger']).
                                        "";
                                    echo '</td>';
                                    echo '</tr>';
                                    }
                                } 
                        }
                    
                }
        ?>
    </table> 
    <br>
    <br>
</div>
