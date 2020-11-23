<?php
namespace zihaolinqianhuifan\hw3\controllers;
require_once('src/controllers/Controller.php');
require_once('src/models/ReadModel.php');
require_once('src/views/MainPage.php');


use zihaolinqianhuifan\hw3\models\ReadModel as RM;
use zihaolinqianhuifan\hw3\views\MainPageView as MPV;

class PresentController extends Controller{
  function __construct(){
		    parent::__construct();
		    $this->model["RM"]=new RM();
		    $this->view["MPV"]=new MPV("");
	}
   function display($reviewID){
    
    $data=[];
    #$this->model["RM"]->load($data);
    $this->view["MPV"]->render();
   }
}