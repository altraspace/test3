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

class InfoController extends Controller
{
    /**
     * Displays device info page.
     *
     * @return string
     */
    public function actionCreate()
    {
        $device = new Device;
        return $this->render('device_info',['device'=>$device]);
    }

    public function actionFetchData()
    {
        $formData = Yii::$app->request->post();

        $device_data = $this->fetchDeviceData($formData['ip_address']);
        // https://api.incolumitas.com/?q=107.174.138.172

        $response = array();

        if($device_data && $device_data['success'] == true)
        {
            $fetched_data = $device_data['data'];

            $device = Device::findOne(['ip_address'=>$fetched_data->ip]);
            if(!$device)
            {
                $device = new Device;
                $device->ip_address = $fetched_data->ip;
            }
            
            $device->dc_network = $fetched_data->datacenter->network;
            $device->asn_network = isset($fetched_data->asn->network) ? $fetched_data->asn->network : null;
            $device->asn_route = $fetched_data->asn->route;
            $device->location_latitude = $fetched_data->location->latitude;
            $device->location_longitude = $fetched_data->location->longitude;
            $device->save();

            $msg_data = "\n dc_network : $device->dc_network
                \n asn_network : $device->asn_network
                \n asn_route : $device->asn_route
                \n location_latitude : $device->location_latitude
                \n location_longitude : $device->location_longitude";

            $response['success'] = true;
            $response['code'] = 200;
            $response['message'] = 'Device data fetched successfully.'.$msg_data;
            $response['data'] = $device;

        }else{
            
            $response['success'] = false;
            $response['code'] = 500;
            $response['message'] = 'Something went wrong.';           
        }

         return json_encode($response);
    }

    function fetchDeviceData($ip_address)
    {   
        $response = array();

        if($ip_address)
        {            
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.incolumitas.com/?q='.$ip_address,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              // CURLOPT_POSTFIELDS  => $postData,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
              // CURLOPT_USERAGENT => ''
            ));

            $curl_response = curl_exec($curl);
            curl_close($curl);
            
            $curl_response=json_decode($curl_response);

            if(isset($curl_response)){
                $response['success'] = true;
                $response['data'] = $curl_response;

            }else{
                ////-- Error --//
                $response['success'] = false;
            }

            return $response;

        }else{
            //-- something went wrong
            $response['success'] = false;
            return $response;
        }
    }
    
}
