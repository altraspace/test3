<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <!-- <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div> -->

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Total Devices</h2>

                <h4 id="total_devices"></h4>

                
            </div>
            <div class="col-lg-4">
                <h2>Total Ping Success</h2>

                <h4 id="ping_success"></h4>

                
            </div>
            <div class="col-lg-4">
                <h2>Total Ping Failed</h2>

                <h4 id="ping_failed"></h4>

                
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>
<script type="text/javascript">
    
    $.ajax({
        url: '/site/statistics',
        method: 'get',
        processData: false,
        contentType: false,
        cache: false,
        // data:data,
        success: function(response) {
            
            response = $.parseJSON(response);
           
            if (response.success) {
               
               document.getElementById('total_devices').innerHTML = response.data.total_devices;
               document.getElementById('ping_success').innerHTML = response.data.ping_success;
               document.getElementById('ping_failed').innerHTML = response.data.ping_failed;
            } else {
                 console.log('el');
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

</script>