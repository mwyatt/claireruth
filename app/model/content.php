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
		, 'slug'
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
	 * needs to get all 'tag' or 'media' and assign to relevant content rows
	 * @param  string $metaName 
	 * @return bool           
	 * 
	 */
	public function bindMeta($metaName)
	{
		$modelMeta = new model_content_meta($this);

		// get data based on ids found in data
		$modelMeta->read(array(
			'where' => array(
				'content_id' => $this->getDataProperty('id'),
				'name' => $metaName
			)
		));

		// validate classname
		if (! class_exists($className = 'model_' . $metaName) || ! class_exists($moldName = 'mold_' . $metaName)) {
			return;
		}

		// instansiate and read based on meta values
		$model = new $className($this);
		$model->read(array(
			'where' => array('id' => $modelMeta->getDataProperty('value'))
		));	
		$model->arrangeByProperty('id');

		// bind meta to the new table using content id as main key
		$boundMeta = array();
		foreach ($modelMeta->getData() as $modelMetaMold) {
			$boundMeta[$modelMetaMold->content_id][] = $model->getData($modelMetaMold->value);
		}

		// bind this->data to the new structure
		$dataReference = $this->getData();
		foreach ($dataReference as $key => &$modelData) {
			if (array_key_exists($modelData->id, $boundMeta) && property_exists($this->getMoldName(), $metaName)) {
				$dataReference[$key]->$metaName = $boundMeta[$modelData->id];
			}
		}
		return $this->setData($dataReference);
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
				, 'url' => $this->url->build(array('month', $monthYear))
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
		$modelOptions = new model_options($this);
		$modelOptions->lazyDelete(array(
			'name' => $key
		));
		$modelOptions->lazyCreate(array(
			'name' => $key
			, 'value' => $sth->rowCount()
		));
	}


	public function readSearch($query = '') {	

		// build
		$statement = array();
		$statement[] = $this->getSqlSelect();
		$statement[] = 'where';
		foreach (explode(' ', $query) as $word) {
			$word = trim($word);
			$statement[] = 'title like \'%' . $word . '%\'';
			$statement[] = 'or';
			$statement[] = 'html like \'%' . $word . '%\'';
			$statement[] = 'or';
		}
		array_pop($statement);
		$statement[] = 'order by title desc';
		$statement = implode(' ', $statement);

		// prepare
		$sth = $this->database->dbh->prepare($statement);

		// execute
		$this->tryExecute(__METHOD__, $sth);
		return $this->setData($sth->fetchAll(PDO::FETCH_CLASS, $this->getMoldName()));
	}
}
