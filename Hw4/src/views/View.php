<?php
namespace zihaolinqianhuifan\hw3\views;


abstract class View{
    protected $data;

function __construct($data){
	$this->data =$data;
}

function head(){
	?>
		<!doctype html>
		<html lang="en">
		<head>
			<meta charset="utf-8">
			<title>Moview Review</title>
			<link rel="stylesheet" type="text/css" href="src/styles/styles.css" />
		</head>
		<body>
		<?php
}
function foot(){
	?>
	    </body>
    	</html>
	<?php
	
}

function load($data){
	$this->data=$data;
}
function render(){
    $this->head();
    $this->body();
    $this->foot();
}

abstract function body();
}
