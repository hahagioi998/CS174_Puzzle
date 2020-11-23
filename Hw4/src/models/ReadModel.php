<?php
namespace zihaolinqianhuifan\hw3\models;
require_once('src/models/Model.php');
class ReadModel extends Model{
     function __construct(){
		parent::__construct();
	}
    function genreAndReview($genreName){
        $data=array();
        $data["currentGenre"]=$genreName;
        $data["genres"]=parent::fetch("select genre.name,sum(IF(review.review_id is NULL,0,1)) from genre left join review on genre.name=review.genre_name group by genre.name;");
        $data["review"]=parent::fetch("call getreview(\"".$genreName."\")");
        return $data;
    }
    function getReview($reviewId){
        return parent::fetch("call getcontent(".$reviewId.")")[0];
    }
}