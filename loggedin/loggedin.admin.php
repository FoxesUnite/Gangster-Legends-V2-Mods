<?php

	class adminModule {

		private function getNews($newsID = "all") {
			if ($newsID == "all") {
				$add = "";
			} else {
				$add = " WHERE GN_id = :id";
			}
			
			$news = $this->db->prepare("
				SELECT
					GN_id as 'id',  
					GN_author as 'gnauthor',  
					GN_title as 'gntitle',  
					GN_text as 'gntext',  
					GN_date as 'gndate'
				FROM gamenews" . $add . "
				ORDER BY GN_id"
			);

			if ($newsID == "all") {
				$news->execute();
				return $news->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$news->bindParam(":id", $newsID);
				$news->execute();
				return $news->fetch(PDO::FETCH_ASSOC);
			}
		}

		private function validatenews($news) {
			$errors = array();
			if (strlen($news["gnauthor"]) < 6) {
				$errors[] = "News name is to short, this must be atleast 5 characters.";
			}
			if (strlen($news["gntitle"]) < 2) {
				$errors[] = "News title is to short, this must be atleast 5 characters.";
			} 
			if (strlen($news["gntext"]) < 10) {
				$errors[] = "News text is to short, this must be atleast 10 characters.";
			} 
			if (strlen($news["gndate"]) < 5) {
				$errors[] = "Date is to short, this must be atleast 5 characters.";
			} 

			return $errors;
			
		}

		public function method_new () {

			$news = array();

			if (isset($this->methodData->submit)) {
				$news = (array) $this->methodData;
				$errors = $this->validatenews($news);
				
				if (count($errors)) {
					foreach ($errors as $error) {
						$this->html .= $this->page->buildElement("error", array("text" => $error));
					}
				} else {
					$insert = $this->db->prepare("
						INSERT INTO gamenews (GN_author, GN_title, GN_text, GN_date)  VALUES (:gnauthor, :gntitle, :gntext, :gndate);
					");
					$insert->bindParam(":gnauthor", $this->methodData->gnauthor);
					$insert->bindParam(":gntitle", $this->methodData->gntitle);
					$insert->bindParam(":gntext", $this->methodData->gntext);
					$insert->bindParam(":gndate", $this->methodData->gndate);
					$insert->execute();

					$this->html .= $this->page->buildElement("success", array("text" => "This news post has been created"));

				}

			}

			$news["editType"] = "new";
			$this->html .= $this->page->buildElement("loggedinNewForm", $news);
		}

		public function method_edit () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No news ID specified"));
			}

			$news = $this->getNews($this->methodData->id);

			if (isset($this->methodData->submit)) {
				$news = (array) $this->methodData;
				$errors = $this->validatenews($news);

				if (count($errors)) {
					foreach ($errors as $error) {
						$this->html .= $this->page->buildElement("error", array("text" => $error));
					}
				} else {
					$update = $this->db->prepare("
						UPDATE gamenews SET GN_author = :gnauthor, GN_title = :gntitle, GN_text = :gntext, GN_date = :gndate WHERE GN_id = :id
					");
					$update->bindParam(":gnauthor", $this->methodData->gnauthor);
					$update->bindParam(":gntitle", $this->methodData->gntitle);
					$update->bindParam(":gntext", $this->methodData->gntext);
					$update->bindParam(":gndate", $this->methodData->gndate);
					$update->bindParam(":id", $this->methodData->id);
					$update->execute();

					$this->html .= $this->page->buildElement("success", array("text" => "News post has been updated"));

				}

			}

			$news["editType"] = "edit";
			$this->html .= $this->page->buildElement("loggedinNewForm", $news);
		}

		public function method_delete () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No news ID specified"));
			}

			$news = $this->getNews($this->methodData->id);

			if (!isset($news["id"])) {
				return $this->html = $this->page->buildElement("error", array("text" => "This news post does not exist"));
			}

			if (isset($this->methodData->commit)) {
				$delete = $this->db->prepare("
					DELETE FROM gamenews WHERE GN_id = :id;
				");
				$delete->bindParam(":id", $this->methodData->id);
				$delete->execute();

				header("Location: ?page=admin&module=loggedin");

			}


			$this->html .= $this->page->buildElement("loggedinDelete", $news);
		}

		public function method_view () {
			
			$this->html .= $this->page->buildElement("loggedinList", array(
				"loggedin" => $this->getNews()
			));

		}

	}