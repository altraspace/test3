<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Fetch Device Data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>


    <div class="body-content">
       <form class="needs-validation" novalidate accept-charset="utf-8" method="post" id="frm_fetch_data" >
            <div class="row">
                <div class="form-group">
                    <div class="col-lg-6">
                        <input type="text" name="ip_address" id="ip_address" required>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group">
                    <div class="col-lg-6">
                        <div class="col-lg-3">
                            
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Fetch</button>
                        </div>
                        
                    </div>
                </div>
            </div>
       </form>
    </div>

    
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>

<script type="text/javascript">
    var frm = $('#frm_fetch_data');

    frm.submit(function (e) {

        e.preventDefault();

        let ip_address = $('input[name="ip_address"]').val();
        if(ip_address == '' || ip_address == undefined || ip_address == null){
            alert('IP address is required.');
            return;
        }

        let form = $('#frm_fetch_data')[0];
        let data = new FormData(form);
        $.ajax({
            url: '/info/fetch-data',
            method: 'post',
            processData: false,
            contentType: false,
            cache: false,
            data:data,
            success: function(response) {
                 
                 response = $.parseJSON(response);
                if (response.success) {
                    alert(response.message);
                    
                } else {
                    alert('Something went wrong.');
                }
            },
            error: function(data) {
                 
                let errors = $.parseJSON(data.responseText);
                let message = '';
                $.each(errors.errors, function(index, value) {
                    message = message + message;
                });
                 alert(message);
            }
        });
    });

</script>