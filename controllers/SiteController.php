<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Device;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays device page.
     *
     * @return string
     */
    public function actionDevice()
    {
        $devices = Device::find()->all();
        // echo '<pre>';
        // print_r($devices);
        // echo '</pre>';
        // print_r($devices[0]['hostname']);
        // exit();
        return $this->render('device_list', ['devices'=>$devices]);
    }

    /**
     * Displays device create page.
     *
     * @return string
     */
    public function actionCreate()
    {
        $device = new Device;

        $formData = Yii::$app->request->post();
        // print_r(Yii::$app->request->post());
        // exit;
        if ($device->load(Yii::$app->request->post())) {
            if($device->save())
            {
                Yii::$app->session->setFlash('message', 'Device details added successfully.');
                return $this->redirect('device');
            }else{
                Yii::$app->session->setFlash('message', 'Failed to add Device details.');
            }
            

            return $this->refresh();
        }

        return $this->render('device_create',['device'=>$device]);
    }

    /**
     * Edit device details.
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $device = Device::findOne($id);

        $formData = Yii::$app->request->post();
        // print_r(Yii::$app->request->post());
        // exit;
        if ($device->load(Yii::$app->request->post()) && $device->save()) {
            
            Yii::$app->getSession()->setFlash('message', 'Device details updated successfully.');
            return $this->redirect('device');
        }else{
            Yii::$app->session->setFlash('message', 'Failed to update Device details.');
            
        }

        return $this->render('device_edit',['device'=>$device]);
    }

    /**
     * Delete device details.
     *
     * @return string
     */
    public function actionDelete($id)
    {
        $device = Device::findOne($id)->delete();

        if($device)
        {
            Yii::$app->getSession()->setFlash('message', 'Device details deleted successfully.');
            return $this->redirect('device');
        }
    }

    /**
     * Dashboard
     *
     * @return string
     */
    public function actionStatistics()
    {
        $data = [];

        $total_devices = Device::find()->all();
        
        $query = new \yii\db\Query();
        $ping_success = $query->from('device')
        ->where(['ping_status' => 'success']) 
        ->all();

        $query = new \yii\db\Query();
        $ping_failed = $query->from('device')
        ->where(['ping_status' => 'failed']) 
        ->all();

        $data['total_devices'] = count($total_devices);
        $data['ping_success'] = count($ping_success);
        $data['ping_failed'] = count($ping_failed);

        $response = array();
        $response['success'] = true;
        $response['code'] = 200;
        
        $response['message'] = 'Statistics data';
        
        $response['data'] = $data;
        
        return json_encode($response);
       
    }

    public function actionPing()
    {
        $ip_address = Yii::$app->request->post('ip_address');

        $host = $ip_address;

        exec("ping -c 4 " . $host, $output, $result);
        
        if ($result == 0){
            $ping_status = 'success';
        }else{
            $ping_status = 'failed';   
        }

        $output = json_encode($output);

        $response = array();

        $device = Device::findOne(['ip_address'=>$ip_address]);
        if(!$device)
        {
            $device = new Device;
            $device->ip_address = $fetched_data->ip;
        }
        
        $device->ping_status = $ping_status;
        $device->ping_output = $output;
        $device->save();

        if($ping_status == 'success')
        {
             $output = json_decode($output, TRUE);
            $html = '';
            foreach($output AS $row)
            {
                $html = $html . '<p>'.$row.'</p><br>';
            }
            $response['success'] = true;
            $response['code'] = 200;
            $response['message'] = 'Ping success.';
            $response['data'] = $html;

        }else{
            
            $response['success'] = false;
            $response['code'] = 500;
            $response['message'] = 'Ping failed.';           
        }

         return json_encode($response);
    }
}
