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
	public function read($where = '', $limit = array(), $ids = array(), $compliance = array()) {
		$contents = array();
		$contentIds = array();
		$parsedData = array();
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.html
				, content.type
				, content.time_published
				, content.status
				, content.user_id
				, concat(user.first_name, ' ', user.last_name) as user_name
				, content_meta.value as content_meta_love
			from content
			left join user on user.id = content.user_id
			left join content_meta on content_meta.id = content.id and content_meta.name = 'love'
            where content.id != ''
			" . ($this->config->getUrl(0) == 'admin' ? ' and content.status != \'archive\'' : ' and content.status = \'visible\'') . "
			" . ($where ? ' and content.type = :type ' : '') . "
			" . ($ids ? ' and content.id = :id ' : '') . "
			group by content.id
			order by content.time_published desc
			" . ($limit ? ' limit :limit_start, :limit_end ' : '') . "
		");
		if ($where) {
			$sth->bindValue(':type', $where, PDO::PARAM_STR);
		}
		if ($limit) {
			$sth->bindValue(':limit_start', (int) current($limit), PDO::PARAM_INT);
			$sth->bindValue(':limit_end', (int) next($limit), PDO::PARAM_INT);
		}

		// @todo make this better?
		if ($ids) {
			foreach ($ids as $id) {
				$sth->bindValue(':id', $id, PDO::PARAM_STR);
				$this->tryExecute($sth);
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$contents[] = $row;
				}
			}
		} else {
			$this->tryExecute($sth);				
			$contents = $sth->fetchAll(PDO::FETCH_ASSOC);
		}

		// collect all ids queried
		foreach ($contents as $content) {
			$contentIds[] = $content['id'];
		}
		
		// read all needed media and tags
		$media = new model_media($this->database, $this->config);
		$tag = new model_tag($this->database, $this->config);
		$tags = $tag->readByContentId($contentIds);
		$medias = $media->readByContentId($contentIds);

		// generate url, append media or tags where applicable
		foreach ($contents as $content) {
			$content['url'] = $this->buildUrl(array($content['type'], $content['title'] . '-' . $content['id']));
			$parsedData[$content['id']] = $content;
			if ($tags && array_key_exists($content['id'], $tags)) {
				$parsedData[$content['id']]['tag'] = $tags[$content['id']];
			}
			if ($medias && array_key_exists($content['id'], $medias)) {
				$parsedData[$content['id']]['media'] = $medias[$content['id']];
			}
		}
		return $this->setData($parsedData);
	}	


	public function readByType($type, $limit = 0) {	
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.html
				, content.time_published
				, content.status
				, content.type
			from content
			left join user on user.id = content.user_id
			where content.type = :type and content.status = 'visible'
			order by content.time_published desc
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
	 * @todo  could be adapted to be used for posts too.. if all titles will be unique?
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
				, content.time_published
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
		$model = new model_options($this->database, $this->config, 'options');
		$model->lazyDelete(
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
				, content.time_published
			from content
			where
				content.type = 'post'
			order by
				content.time_published desc
		");
		foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$keyedDate = strtolower(date('F-Y', $row['time_published']));

			// set of month-years
			if ($specificMonthYears) {
				foreach ($specificMonthYears as $monthYear) {
					if ($keyedDate == $monthYear) {
						$specificParsedData[$keyedDate][] = $row['id'];
					}
				}
			}

			// all month-years
			$parsedData[$keyedDate][] = $row;
		}
		if ($specificMonthYears) {		
			$this->read('post', false, current($specificParsedData));
			return $this->getData();
		}

		// build usable array
		foreach ($parsedData as $monthYear => $row) {
			$currentRow = current($row);
			$rows[$monthYear] = array(
				'total' => count($row)
				, 'title' => date('F Y', $currentRow['time_published'])
				, 'url' => $this->buildUrl(array('month', $monthYear))
			);
		}

		// return full monthdata
		return $this->setData($rows);
	}

}
