<?php
$myValues = ['user_id', 'username', 'skills', 'portfolio', 'review', 'languages',
  'pricePerHour', 'rating', 'Points', 'balance', 'education'];
foreach($myValues as $value){
//generate getter
    echo "public function get".ucfirst($value). "(){<br>";
    echo 'return $this->'.$value. ';<br>}<br>';
//generate setter
     echo "public function set".ucfirst($value). "($" . "$value){<br>";
    echo '$this->'.$value. " = $$value;<br>}<br>";
}

?>
