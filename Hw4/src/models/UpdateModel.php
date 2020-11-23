<?php
namespace zihaolinqianhuifan\hw3\models;
require_once('src/models/Model.php');
class UpdateModel extends Model{
     function __construct(){
		parent::__construct();
	}
	function addGenre($genreName){
	    parent::exe("insert into genre value(\"".$genreName."\");");
	}
    function deleteGenre($genreName){
        parent::exe("call deletegenre(\"".$genreName."\");");
    }
    function addReview($genreName,$title,$content){
        parent::exe("call uploadReview(\"".$genreName."\",\"".$title."\",\"".$content."\");");
    }
    function deleteReview($reviewId){
        parent::exe("call deletereview(".$reviewId.");");
    }
    
}