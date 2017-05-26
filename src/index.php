<?php

include 'General.php';
include 'Homepage\Homepage.php';
include 'Product\Product.php';

if(checkRout('products/add')) {
    $product = new Product();
    $product->add();
}elseif(checkRout('products/index')) {
    $product = new Product();
    $product->index();
}else {
    $homePage = new Homepage();
    $homePage->index();
}

function checkRout(string $route) {
    return strpos($_SERVER['REQUEST_URI'], $route);
}