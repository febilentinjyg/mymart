<?php

namespace common\components;
use yii\base\component;
use frontend\models\Statistic;
use Yii;

/**
* Author : Febilentin Jayuning
*/
class MyComponent extends Component
{
    const EVENT_AFTER_SOMETHING = 'after-something';
    
    public function myHandler(){
        $statistic = new Statistic;
        $statistic->access_time = date("Y-m-d H:i:s");
        $statistic->user_ip = Yii::$app->getRequest()->getUserIP();
        $statistic->user_host = Yii::$app->getRequest()->getUserHost();
        $statistic->path_info = Yii::$app->getRequest()->getPathInfo();
        $statistic->query_string = Yii::$app->getRequest()->getQueryString();
        $statistic->save();
    }
    public function welcome(){
        echo "Hello.. Welcome to MyComponent";
    }
}
?>