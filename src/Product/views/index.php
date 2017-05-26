<?php

$data = General::getData();

foreach($data['products'] as $product){

    foreach ($product as $value) {
        echo $value . ' ';
    }

    echo '<br/>';
}