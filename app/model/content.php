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
	 * possible status of a content item
	 * @var array
	 */
	var $status = array(
		'visible'
		, 'hidden'
		, 'draft'
		, 'archive'
	);


	/**
	 * seperate methods for each type of read, this will mean that the reads
	 * cannot be too dry, handle the resulting in a seperate function?
	 * @param  string  $type  
	 * @return array         
	 */
	public function readType($type) {	
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
		");
		$sth->bindValue(':type', $type, PDO::PARAM_STR);
		$this->tryExecute($sth);
		return $this->storeResult($sth);
	}	


	/**
	 * fetch all attachments
	 * sets fetch mode and fetches all results into class
	 * @param  object $sth
	 * @return bool
	 */
	public function storeResult($sth)
	{
		$results = $sth->fetchAll(PDO::FETCH_CLASS, 'view_content');

		// read all needed media and tags
		// build url
		foreach ($results as $result) {
			$ids[] = $result->id;
			$result->url = $this->buildUrl(array($result->type, $result->title . '-' . $result->id));
		}
		$media = new model_media($this->database, $this->config);
		$medias = $media->readContentId($ids);
		$tag = new model_tag($this->database, $this->config);
		$tags = $tag->readContentId($ids);
		foreach ($results as $key => $result) {
			if ($tags && array_key_exists($result->id, $tags)) {
				$results[$key]->tag = $tags[$result->id];
			}
			if ($medias && array_key_exists($result->id, $medias)) {
				$results[$key]->media = $medias[$result->id];
			}
		}
		return $this->setData($results);
	}


	/**
	 * check to see if a value is a valid status
	 * @param  string $value 
	 * @return bool        
	 */
	public function validateStatus($value = '')
	{
		if (in_array($value, $this->getStatus)) {
			return true;
		}
	}


	public function getStatus()
	{
		return $this->status;
	}


	/**
	 * reads any and all content stored in this table
	 * a number of custom parameters can be used to
	 * bring in differing result sets
	 * @todo  possibly send in a $config var array..
	 * @param  string $type  the type of content
	 * @param  string $limit the amount of content required
	 * @return null        data property will be set
	 */
	public function read($type = '', $limit = array(), $ids = array()) {
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
			" . ($this->config->getUrl(0) == 'admin' ? ' ' : ' and content.status = \'visible\'') . "
			" . ($type ? ' and content.type = :type ' : '') . "
			" . ($ids ? ' and content.id = :id ' : '') . "
			group by content.id
			order by content.time_published desc
			" . ($limit ? ' limit :limit_start, :limit_end ' : '') . "
		");
		if ($type) {
			$sth->bindValue(':type', $type, PDO::PARAM_STR);
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


		return $this->setData($parsedData);
	}


	/**
	 * key value values of the row which needs to be created
	 * are passed then the row is created
	 * session_admin_user used to tie in the id of the current user
	 * @param  array $values 
	 * @return int        
	 */
	public function create($values) {        
		$sessionAdminUser = new session_admin_user($this->database, $this->config);
        $sth = $this->database->dbh->prepare("
            insert into content (
                title
                , html
                , type
                , time_published
                , status
                , user_id
            )
            values (
                :title
                , :html
                , :type
                , :time_published
                , :status
                , :user_id
            )
        ");             
        $sth->execute(array(
            ':title' => $values['title']
            , ':html' => (array_key_exists('html', $values) ? $values['html'] : '')
            , ':type' => $values['type']
            , ':time_published' => time()
            , ':status' => (array_key_exists('status', $values) ? $values['status'] : 'hidden')
            , ':user_id' => $sessionAdminUser->getData('id')
        ));                
        return $sth->rowCount();
	}	


	public function update($id, $values) {
		$sth = $this->database->dbh->prepare("
		update content set
			title = ?
			, html = ?
			, status = ?
		where
			id = ?
		");                                
		$sth->execute(array(
			(array_key_exists('title', $values) ? $values['title'] : '')
			, (array_key_exists('html', $values) ? $values['html'] : '')
			, (array_key_exists('status', $values) ? $values['status'] : 'hidden')
			, $id
		));                
        return $sth->rowCount();
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
		$this->data = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $sth->rowCount();
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


	/**
	 * store a count for a tables rows
	 * @param  string $type content type
	 * @return null
	 */
	public function storeTotalRows($type)
	{
		$key = 'model_' . 'content_' . 'total';
		$sth = $this->database->dbh->prepare("	
			select
				content.id
			from content
            where
            	content.status = 'visible'
            	and content.type = :type
		");
		$sth->bindValue(':type', $type, PDO::PARAM_STR);
		$this->tryExecute($sth);
		;
		$modelOptions = new model_options($this->database, $this->config);
		$modelOptions->lazyDelete(array(
			'name' => $key
		));
		$modelOptions->lazyCreate(array(
			'name' => $key
			, 'value' => $sth->rowCount()
		));
	}
}
