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


	public $fields = array(
		'id'
		, 'title'
		, 'html'
		, 'type'
		, 'time_published'
		, 'status'
		, 'user_id'
	);


	/**
	 * possible status of a content item
	 * @var array
	 */
	public $status = array(
		'visible'
		, 'hidden'
		, 'draft'
		, 'archive'
	);




	/**
	 * @param  array  $properties type, limit, ids
	 * @return bool             
	 */
	public function read($properties = array())
	{

		// build
		$statement = array();
		$statement[] = $this->getSqlSelect();
		if (array_key_exists('where', $properties)) {
			$statement[] = $this->getSqlWhere($properties['where']);
		}
		$statement[] = 'order by time_published desc';
		if (array_key_exists('limit', $properties)) {
			$statement[] = $this->getSqlLimit($properties['limit']);
		}

		// prepare
		$sth = $this->database->dbh->prepare(implode(' ', $statement));

		// bind
		if (array_key_exists('where', $properties)) {
			foreach ($properties['where'] as $key => $value) {
				$this->bindValue($sth, $key, $value);
			}
		}
		if (array_key_exists('limit', $properties)) {
			foreach ($properties['limit'] as $key => $value) {
				$sth->bindValue(':' . $key, (int) $value, PDO::PARAM_INT);
			}
		}

		// execute
		$this->tryExecute(__METHOD__, $sth);
		return $this->setData($sth->fetchAll(PDO::FETCH_CLASS, $this->getMoldName()));
	}


	/**
	 * @param  array  $properties (id => ?, array(key => value))
	 * @return bool             
	 */
	public function update($id, $mold)
	{

		// statement
		$statement = array();
		$statement[] = 'update';
		$statement[] = $this->getIdentity();
		$statement[] = 'set';
		foreach ($mold as $key => $value) {
			
		}

		$statement[] = $this->getSqlFieldsWriteable(' = ?');
		$statement[] = 'where id = ?';

		// prepare
		$sth = $this->database->dbh->prepare(implode(' ', $statement));

		// execute
        foreach ($molds as $mold) {
			$this->tryExecute(__METHOD__, $sth, $this->getSthExecuteData($mold));
        }

		// return
        return $sth->rowCount();
	




		$sth = $this->database->dbh->prepare('
			update ' . $this->getIdentity() . ' set
				title = ?
				, html = ?
				, type = ?
				, status = ?
				, user_id = ?
			where id = ?
		'); 
		$this->tryExecute(__METHOD__, $sth, array(
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
