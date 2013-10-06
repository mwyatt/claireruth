<?php

/**
 * Responsible for Various content types (Projects, Posts and Pages)
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Content extends Model
{	


	/**
	 * reads any and all content stored in this table
	 * a number of custom parameters can be used to
	 * bring in differing result sets
	 * @param  string $type  the type of content
	 * @param  string $limit the amount of content required
	 * @return null        data property will be set
	 */
	public function read($where = '', $limit = array(), $ids = array()) {	
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.html
				, content.type
				, content.date_published
				, content.status
				, content.user_id
				, concat(user.first_name, ' ', user.last_name) as user_name
			from content
			left join user on user.id = content.user_id
            where content.id != ''
			" . ($this->config->getUrl(0) == 'admin' ? '' : ' and content.status = \'visible\'') . "
			" . ($where ? ' and content.type = :type ' : '') . "
			" . ($ids ? ' and content.id = :id ' : '') . "
			group by content.id
			order by content.date_published desc
			" . ($limit ? ' limit :limit_start, :limit_end ' : '') . "
		");
		if ($where) {
			$sth->bindValue(':type', $where, PDO::PARAM_STR);
		}
		if ($limit) {
			$sth->bindValue(':limit_start', (int) current($limit), PDO::PARAM_INT);
			$sth->bindValue(':limit_end', (int) next($limit), PDO::PARAM_INT);
		}		
		if ($ids) {
			foreach ($ids as $id) {
				$sth->bindValue(':id', $id, PDO::PARAM_STR);
				$sth->execute();
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$contents[] = $row;
				}
			}
		} else {
			$sth->execute();				
			$contents = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		$contentIds = array();
		foreach ($contents as $content) {
			$contentIds[] = $content['id'];
		}
		$mainMedia = new model_media($this->database, $this->config);
		$mainContentTag = new model_content_tag($this->database, $this->config);
		$medias = $mainMedia->read($contentIds);
		$tags = $mainContentTag->read($contentIds);

		// generate guid, append media or tags where applicable
		foreach ($contents as $content) {
			$content['guid'] = $this->buildUrl(array($content['type'], $content['title'] . '-' . $content['id']));
			$this->data[$content['id']] = $content;
			if (array_key_exists($content['id'], $tags)) {
				$this->data[$content['id']]['tag'] = $tags[$content['id']];
			}
			if (array_key_exists($content['id'], $medias)) {
				$this->data[$content['id']]['media'] = $medias[$content['id']];
			}
		}
		return $sth->rowCount();		
	}	


	/**
	 * utilises read to get a single result
	 * (not contained in array)
	 * @param  string $type 
	 * @param  string|int $id   
	 * @return bool|array       signify success
	 */
	public function readSingle($type, $id)
	{
		$this->read($type, false, array($id));
		if ($this->getData()) {
			return $this->data = current($this->getData());
		}
		return false;
	}


	public function readByType($type, $limit = 0) {	
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.html
				, content.date_published
				, content.status
				, content.type
			from content
			left join user on user.id = content.user_id
			where content.type = :type and content.status = 'visible'
			order by content.date_published desc
			" . ($limit ? ' limit :limit ' : '') . "
		");
		$sth->bindValue(':type', $type, PDO::PARAM_STR);
		if ($limit) {
			$sth->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
		}
		$sth->execute();
		$this->data = $this->setMeta($sth->fetchAll(PDO::FETCH_ASSOC));
		return $this;
	}	


	/**
	 * seems to be used only for /page/
	 * @param  string $title 
	 * @return int        
	 */
	public function readByTitle($title) {
		$title = str_replace('-', ' ', $title);
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.html
				, content.date_published
				, content.status
				, content.type
			from content
			where
				content.title like ?
				and content.status = 'visible'
		");
		$sth->execute(array('%' . current($title) . '%'));
		$this->data = $sth->fetch(PDO::FETCH_ASSOC);
		return $sth->rowCount();
	}	


	/**
	 * sets the total rowcount in options table
	 * @return bool 
	 */
	public function createTotal()
	{
		$sth = $this->database->dbh->query("	
			select
				content.id
			from content
			where
				content.status = 'visible'
		");
		$model = new Model_options($this->database, $this->config, 'options');
		$model->delete(
			array('name' => 'model_content_rowcount')
		);
		return $model->create(array(
			'name' => 'model_content_rowcount'
			, 'value' => $sth->rowCount()
		));
	}


	public function addAttachment($contentId)
	{
		
		// tag
		$contentMany = new model_content_many($this->database, $this->config, 'content_tag');
		$contentMany->delete(
			array('content_id' => $contentId)
		);
		if (array_key_exists('tag', $_POST)) {
			foreach ($_POST['tag'] as $tag) {
				$contentMany->create(array(
					'content_id' => $contentId
					, 'tag_id' => $tag
				));
			}
		}

		// media
		$contentMany->setTableName('content_media');
		$contentMany->delete(
			array('content_id' => $contentId)
		);
		if (array_key_exists('media', $_POST)) {
			foreach ($_POST['media'] as $media) {
				$contentMany->create(array(
					'content_id' => $contentId
					, 'media_id' => $media
				));
			}
		}
	}



	/**
	 * gathers matching month-years from posts
	 * if no monthyears passed then it will gather all
	 * @param  array  $specificMonthYears month-year, month-year
	 * @return array             
	 */
	public function readByMonth($specificMonthYears = array())
	{
		$sth = $this->database->dbh->query("	
			select
				content.id
				, content.date_published
			from content
			where
				content.type = 'post'
			order by
				content.date_published desc
		");
		foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$keyedDate = strtolower(date('F-Y', $row['date_published']));

			// set of month-years
			if ($specificMonthYears) {
				foreach ($specificMonthYears as $monthYear) {
					if ($keyedDate == $monthYear) {
						$specificParsedData[$keyedDate][] = $row['id'];
					}
				}
			}

			// all month-years
			$parsedData[$keyedDate][] = $row['id'];
		}
		if ($specificMonthYears) {		
			$this->read('post', false, current($specificParsedData));
			return $this->getData();
		}

		// build usable array
		foreach ($parsedData as $monthYear => $row) {
			$rows[$monthYear] = array(
				'total' => count($row)
				, 'url' => $this->buildUrl(array('month', $monthYear))
			);
		}

		// return full monthdata
		return $this->setData($rows);
	}

}
