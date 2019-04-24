<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Cart;

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
});

$app->get("/cart", function() {

	$cart = Cart::getFromSession();

	$page = new Page();

	$page->setTpl("cart", [
		'cart' => $cart->getValues(),
		'products' => $cart->getProducts()
	]);
	
});

$app->get("/cart/:idproduct/add", function($idproduct){

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$qtde = (isset($_GET['qtde'])) ? (int)$_GET['qtde'] : 1;

	for ($i=0; $i < $qtde; $i++) { 
		
		$cart->addProduct($product);
	}

	

	header("Location: /cart");
	exit;
});

$app->get("/cart/:idproduct/minus", function($idproduct){

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product);

	header("Location: /cart");
	exit;
});

$app->get("/cart/:idproduct/remove", function($idproduct){

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product, true);

	header("Location: /cart");
	exit;
});

?>