<?php
namespace zihaolinqianhuifan\hw3\views;
require_once ('src/views/View.php');
class MainPageView extends View {
function __construct($arg) {
    parent::__construct($arg);
}

function body(){
    ?>
	
	<div id="gameArea">
		<h1>Community Jigsaw</h1>
    	<h2>NewImage: <input type="file" id="file"></h2>
		<div id="imgArea">
			<!--<div id="d0">0</div>-->
			<!--<div id="d1">1</div>-->
			<!--<div id="d2">2</div>-->
			<!--<div id="d3">3</div>-->
			<!--<div id="d4">4</div>-->
			<!--<div id="d5">5</div>-->
			<!--<div id="d6">6</div>-->
			<!--<div id="d7">7</div>-->
			<!--<div id="d8">8</div>-->
		</div>
	</div>
    <script type="text/javascript" src="src/views/puzzle.js"></script>
    <?php
}

}
