<?php

namespace app\models;

use yii\db\ActiveRecord;
// use Yii;
// use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Device extends ActiveRecord
{
    private $id;
    private $ip_address;
    private $hostname;
    private $serial;
    private $ping_status;
    private $ping_output;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // ip_address required
            [['ip_address'], 'required'],
            [['ip_address'], 'unique'],
            [['hostname'], 'safe'],
            [['serial'], 'safe'],
        ];
    }

    // /**
    //  * @return array customized attribute labels
    //  */
    // public function attributeLabels()
    // {
    //     return [
    //         'verifyCode' => 'Verification Code',
    //     ];
    // }

    // /**
    //  * Sends an email to the specified email address using the information collected by this model.
    //  * @param string $email the target email address
    //  * @return bool whether the model passes validation
    //  */
    // public function contact($email)
    // {
    //     if ($this->validate()) {
    //         Yii::$app->mailer->compose()
    //             ->setTo($email)
    //             ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
    //             ->setReplyTo([$this->email => $this->name])
    //             ->setSubject($this->subject)
    //             ->setTextBody($this->body)
    //             ->send();

    //         return true;
    //     }
    //     return false;
    // }
}
