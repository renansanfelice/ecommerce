<?php

use \Hcode\Page;
use \Hcode\Model\Product;

$app->get('/', function() {

		$products = Product::listAll();

		$page = new Page();
		$page->setTpl("index", [
			'products'=>Product::checkList($products)
		]);

});

$app->get('/products/:desurl', function($desurl){

	$product = new Product();

	$product->getFromUrl($desurl);

	$page = new Page();

	$page->setTpl("product-detail", [
		'product'=>$product->getValues(),
		'categories'=>$product->getCategories()
	]);

})
?>