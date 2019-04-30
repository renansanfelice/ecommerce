<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Cart;
use \Hcode\Model\Address;
use \Hcode\Model\User;

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
		'products' => $cart->getProducts(),
		'error' => Cart::getMsgError()
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

$app->post('/cart/freight', function() {

	$cart = Cart::getFromSession();

	$cart->setFreight($_POST['zipcode']);

	header("Location: /cart");
	exit;
});

$app->get("/checkout", function() {

	User::verifyLogin(false);

	$cart = Cart::getFromSession();

	$address = new Address();

	$page = new Page();

	$page->setTpl("checkout", [
		'cart' => $cart->getValues(),
		'address' => $address->getValues()

	]);
});

$app->get("/login", function() {

	$page = new Page();

	$page->setTpl("login", [
		'error'=>User::getError()
	]);
});

$app->post("/login", function() {
	try {

		User::Login($_POST['login'], $_POST['password']);

	} catch(Exception $e) {

		User::setError($e->getMessage());
	}

	header("Location: /checkout");
	exit;
});

$app->get("/logout", function() {

	User::logout();

	header("Location: /login");
	exit;
});

?>