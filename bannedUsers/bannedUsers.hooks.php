<?php
	$info = array(
		"name" => "Banned Users", 
		"version" => "1.0.0", 
		"description" => "This module allows a user to view who users that are banned",
		"author" => array(
			"name" => "Connor Smith", 
			"url" => ""
		), 
		"pageName" => "Banned Users",
		"accessInJail" => true, 
		"requireLogin" => true
	);

	new hook("accountMenu", function () {
		return array(
			"url" => "?page=bannedUsers", 
			"text" => "Banned Users"
		);
	});
?>