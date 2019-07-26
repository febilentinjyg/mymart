<?php

namespace frontend\controllers;

use Yii;
use common\models\Statistic\Statistic;
use common\models\item\Item;
use common\models\itemCategory\ItemCategory;
use common\models\ItemSearch;
use common\models\Order\Order;
use common\models\OrderItem\OrderItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\data\ActiveDataProvider;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actionJson()
    {
        $something = true; // or you can set for test -> false;
        $return_json = ['status' => 'error'];
        if ($something == true)
        {
            $data = Item::find()->asArray()->all(); //attribute=>where(['userID' => 32])
                //return $this->render('index', array('data' => $data ));
            //$return_json = ['status' => 'success', 'message' => ' is successfully saved'];
            $return_json = $data;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $return_json;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['signup'],
                'rules' => [
                    [
                        //see captcha and error added here, this fixes the issue
                        'actions' => ['contact', 'about', 'captcha', 'error', 'json'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'logout', 'view', 'order', 'favorite'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        //PAGINATION
        $query = Item::find();
        $pages = new \yii\data\Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 12
        ]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();
        
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

       
        // SORTING
        $query->joinWith(['category']);
        $sort = new \yii\data\Sort([
            'attributes' => [
                'category' =>[
                    'asc' => ['item_category.name' => SORT_ASC],
                    'desc' => ['item_category.name' => SORT_DESC],
                    'label' => 'Category',
                ],
                'name' =>[
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'label' => 'Name',
                ],
                'price' =>[
                    'asc' => ['price' => SORT_ASC],
                    'desc' => ['price' => SORT_DESC],
                    'label' => 'Price',
                ]
            ]
        ]);
        
        // QUERY
        $models = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy($sort->orders)
            ->all();

        $customer = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy($sort->orders)
            ->all();
        // $searchModel = new ItemSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'models' => $models,
            'pages' => $pages,
            'sort' => $sort,
        ]);
    }

    /**
     * Displays a single Item model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        Yii::$app->myComponent->trigger(\common\components\MyComponent::EVENT_AFTER_SOMETHING);
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionOrder($customer_id, $item_id)
    {
        $order = new Order;
        $order->date = date("Y-m-d H:i:s");
        $order->customer_id = $customer_id;
        $order->save();

        $query = new \yii\db\Query();
        $order_id = $query->select(['id_order'])
            ->from('order')
            ->where(['customer_id' => $customer_id])
            ->one()['id_order'];

        $orderItem = new OrderItem;
        $orderItem->order_id = $order_id;
        $orderItem->item_id = $item_id;
        $orderItem->save();

        return $this->redirect('index');
        //input otomatis order_id dan item_id ke tabel order_item
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        return $this->redirect(['index']);
        
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
