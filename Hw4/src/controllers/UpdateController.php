<?php
namespace zihaolinqianhuifan\hw3\controllers;
require_once('src/controllers/Controller.php');
require_once('src/models/UpdateModel.php');
use zihaolinqianhuifan\hw3\models\UpdateModel as UM;

class UpdateController extends Controller{
    function __construct(){
		parent::__construct();
		$this->model["UM"]=new UM();
	}
   function addGenre($dontcare){

     $this->model["UM"]->addGenre($_POST['genre']);
        
        
   }
  function addReview($dontcare){
      $content = trim($_POST['content']);
      $title = trim($_POST['title']);
      if(!empty($_POST['genre'])&&!empty($title)&&!empty($content)){
      $this->model["UM"]->addReview($_POST['genre'], $_POST['title'], $_POST['content']);
      }
      header("Location: index.php?c=PresentController&m=GenreAndReview&arg1=".$_POST['genre']);
	  exit;
   
   }
   function deletegenre($genreName){
       
       $this->model["UM"]->deleteGenre($genreName);
   }
   function deleteReview($reviewId){
       $this->model["UM"]->deleteReview($reviewId);
       header("Location: index.php?c=PresentController&m=GenreAndReview&arg1=".$_REQUEST['arg2']);
	   exit;
   }
}