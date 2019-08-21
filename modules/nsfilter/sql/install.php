<?php

$sql = array();

$sql[] ='CREATE TABLE IF NOT EXISTS `'. _DB_PREFIX_ .'nsfilter_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';




$sql[] ='CREATE TABLE IF NOT EXISTS  `'. _DB_PREFIX_ .'nsfilter_questions` (
  `id_question` int(11) NOT NULL AUTO_INCREMENT,
  `question_name` text NOT NULL,  
  `category_id` int(11) NOT NULL,
   `more_infos` text NULL, 
  PRIMARY KEY (`id_question`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

$sql[] ='CREATE TABLE IF NOT EXISTS  `'. _DB_PREFIX_ .'nsfilter_answers` (
  `id_answer` int(11) NOT NULL AUTO_INCREMENT,
  `answer_name` text NOT NULL,  
  `question_id` int(11) NOT NULL,
  `answer_point` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_answer`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';




$sql[] ='CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'nsfilter_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

