<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        
        <?php
$data = json_decode($data, true);              
?>

        <p>
        	<span>user Registered</span>

        	<table>
  <?php
  
    $count = count($data);
    for($x=0; $x<$count; $x++) 
    {      
      echo "<tr><td id='".$data[$x]['username']."'>".$data[$x]['username']."</td></tr>";
    }
  ?>
</table>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
           
            
        </div>

    </div>
</div>
