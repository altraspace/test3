<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Device List';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(yii::$app->session->hasFlash('message')):?>
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo yii::$app->session->getFlash('message');?>
        </div>
    <?php endif;?>
    <div class="row">
        <span style="margin-bottom: 20px;"><?= Html::a('Create', ['site/create'],['class'=>'btn btn-primary']) ?></span>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <td>Id</td>
            <td>IP Address</td>
            <td>Host Name</td>
            <td>Serial</td>
            <td>Action</td>
            <td>Ping</td>
        </thead>
        <tbody>
            <?php if(count($devices) > 0) : ?>
                <?php foreach($devices AS $device): ?>
            <tr>
                <td><?php echo $device->id;?></td>
                <td><?php echo $device->ip_address;?></td>
                <td><?php echo $device->hostname;?></td>
                <td><?php echo $device->serial;?></td>
                <td>
                   
                    <span><?= Html::a('Edit', ['edit', 'id'=>$device->id] ,['class'=>'label label-primary']);?></span>
                    <span><?= Html::a('Delete', ['delete', 'id'=>$device->id] ,['class'=>'label label-primary']);?></span>
                </td>
                <td>
                    <span><button type="button" class="btn btn-primary" onclick="ping('<?php echo $device->ip_address;?>')">Ping</button></span>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No record found.</td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>

    
    <div id="pingDataModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <span class="close" id="closeModel">&times;</span>
        <p><b>Ping Output :- </b></p>
        <div id="ping_text">
            
        </div>
      </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>
<script type="text/javascript">
    
    var modal = document.getElementById("pingDataModal");
    // var span = document.getElementsByClassName("close")[0];
    var span = document.getElementById("closeModel");
    span.onclick = function() {
      modal.style.display = "none";
    }


    function ping(ip_address)
    {
        $.ajax({
            url: '/site/ping',
            method: 'post',
            cache: false,
            data:{'ip_address':ip_address},
            success: function(response) {
                response = $.parseJSON(response);
               
                if (response.success) {
                   
                   document.getElementById('ping_text').innerHTML = response.data;

                   modal.style.display = "block";

                } else {
                    alert(response.message);
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
    }


    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

</script>

</div>
