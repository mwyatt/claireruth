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
	 * @param  object $mold 
	 * @return bool       
	 */
	public function create($molds = array())
	{
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
                ?
                , ?
                , ?
                , ?
                , ?
                , ?
            )
        ");
        foreach ($molds as $mold) {
	        $sth->execute(array(
	            $mold->title
	            , $mold->html
	            , $mold->type
	            , time()
	            , $mold->status
	            , $mold->user_id
	        ));                
        }
        return $sth->rowCount();
	}	


	/**
	 * @param  array  $properties type, limit, ids
	 * @return bool             
	 */
	public function read($properties = array())
	{
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
			" . (array_key_exists('type', $properties) ? ' and content.type = :type ' : '') . "
			" . (array_key_exists('ids', $properties) ? ' and content.id = :id ' : '') . "
			group by content.id
			order by content.time_published desc
			" . (array_key_exists('limit', $properties) ? ' limit :limit_start, :limit_end ' : '') . "
		");
		if (array_key_exists('type', $properties)) {
			$sth->bindValue(':type', $properties['type'], PDO::PARAM_STR);
		}
		if (array_key_exists('limit', $properties)) {
			$sth->bindValue(':limit_start', (int) current($properties['limit']), PDO::PARAM_INT);
			$sth->bindValue(':limit_end', (int) next($properties['limit']), PDO::PARAM_INT);
		}
		$data = array();
		if (array_key_exists('ids', $properties)) {
			foreach ($properties['ids'] as $id) {
				$sth->bindValue(':id', $id, PDO::PARAM_STR);
				$this->tryExecute($sth, 'model_content->read');
				while ($row = $sth->fetch(PDO::FETCH_CLASS, 'Mold_Content')) {
					$data[] = $row;
				}
			}
			return $this->setData($data);
		}
		$this->tryExecute($sth);
		return $this->setData($sth->fetchAll(PDO::FETCH_CLASS, 'Mold_Content'));
	}


	/**
	 * @param  array  $properties (id => ?, array(key => value))
	 * @return bool             
	 */
	public function update($id, $mold)
	{
		$sth = $this->database->dbh->prepare("
			update content set
				title = ?
				, html = ?
				, type = ?
				, status = ?
				, user_id = ?
			where id = ?
		");                                
		$sth->execute(array(
			$mold->title
			, $mold->html
			, $mold->type
			, $mold->status
			, $mold->user_id
			, $id
		));                
        return $sth->rowCount();
	}


	/**
	 * @param  array  $ids 
	 * @return bool      
	 */
	public function delete($ids = array())
	{
		$sth = $this->database->dbh->prepare("	
			delete from content
			where id = ?
		");
		foreach ($ids as $id) {
			$this->bindValue($sth, 1, $id);
			$this->tryExecute($sth, 'model_content->delete');
		}
		return $sth->rowCount();
	}


	/**
	 * passed models to combine to the data within this model
	 * @param  array $models label => model
	 * @return bool         
	 */
	public function combine($models)
	{
		$data = array();

		// get the data in this model
		foreach ($this->getData() as $thisData) {
			$data[$thisData->id] = $thisData;

			// all models sent through and the desired label
			foreach ($models as $label => $model) {

				// all data within the model
				foreach ($model->getData() as $modelRow) {

					// find a matching contentid with this models row id
					if ($modelRow->content_id == $thisData->id) {
						$data[$thisData->id]->{$label}[] = $modelRow;
					}
				}
			}
		}
		return $this->setData($data);
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
	 * seems to be used only for /page/
	 * @todo  could be adapted to be used for posts too.. if all titles will be unique?
	 * @param  string $title 
	 * @return int        
	 */
	public function readByTitle($title)
	{
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
