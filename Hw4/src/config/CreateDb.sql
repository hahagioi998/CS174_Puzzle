create database if not exists hw3;
use hw3;
drop procedure if exists deletereview;
drop procedure if exists getcontent;
drop procedure if exists getreview;
drop procedure if exists deletegenre;
drop procedure if exists uploadReview;
drop procedure if exists nextid;
drop table if exists review;
drop table if exists genre;
drop table if exists content;
drop table if exists genID;


CREATE TABLE IF NOT EXISTS `genre` (

  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `content` (
  `id` BIGINT NOT NULL,
  `title` VARCHAR(45) NULL,
  `content` VARCHAR(10000) NULL,
  `time` TIMESTAMP NULL DEFAULT now(),
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `review` (
  `genre_name` VARCHAR(45) NOT NULL,
  `review_id` BIGINT NOT NULL,
  PRIMARY KEY (`genre_name`, `review_id`),
  INDEX `fk_review_review_content1_idx` (`review_id` ASC) VISIBLE,
  INDEX `fk_review_genre1_idx` (`genre_name` ASC) VISIBLE,
  CONSTRAINT `fk_review_review_content1`
    FOREIGN KEY (`review_id`)
    REFERENCES `content` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_genere1`
    FOREIGN KEY (`genre_name`)
    REFERENCES `genre` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS `genID` (
  `TableName` VARCHAR(45) NOT NULL,
  `count` VARCHAR(45) NULL DEFAULT 0,
  PRIMARY KEY (`TableName`))
ENGINE = InnoDB;

insert into genID(TableName) value("content");
insert into genre(name) value("Horror");


DELIMITER //
create procedure nextid(in paraTableName varchar(45),out paraid bigint)
begin
update genID set count=count+1  where TableName=paraTableName;
select count into paraid from genID where TableName=paraTableName;
END//

DELIMITER //
create procedure uploadReview(in paraGenreName varchar(45),in paraTitle varchar(45),in paraContent varchar(10000))
begin
declare varid bigint;
declare haveName int;
select count(name) into haveName from genre where name=paraGenreName;
if haveName=0 then
insert into genre value(paraGenreName);
end if;
call nextid("content",varid);
insert into content(id,title,content) value(varid,paraTitle,paraContent);
insert into review value(paraGenreName,varid);
END//

DELIMITER //
create procedure deletegenre(in paraGenreName varchar(45))
begin
declare varid bigint;
declare haveName int;
select count(genre_name) into haveName from review where genre_name=paraGenreName;
if haveName=0 then
delete from genre where name=paraGenreName;
end if;
END//

DELIMITER //
create procedure deletereview(in paraid bigint)
begin
delete from review where review_id=paraid;
delete from content where id=paraid;
END//

DELIMITER //
create procedure getreview(in paraGenreName varchar(45))
begin
declare varid bigint;
declare haveName int;
select count(name) into haveName from genre where name=paraGenreName;
if haveName=0 then
select id,title,time from content order by time desc;
else
select id,title,time from content where id in(select review_id from review where genre_name=paraGenreName) order by time desc;
end if;
END//

create procedure getcontent(in paraid bigint)
begin
select review.genre_name,content.title,content.content from content inner join review on content.id=review.review_id where content.id=paraid;
END//


call uploadReview("Action","ActionTitle","Action is good ");
call uploadReview("Action","ActionTitle1","Action is good1 ");
call uploadReview("Comedy","ComedyTitle","Comedy is good ");
call uploadReview("Comedy","ComedyTitle1","Comedy is good1 ");
call uploadReview("Drama","DramaTitle","Drama is good ");


