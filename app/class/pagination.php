<?php


/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Pagination extends Model
{


	public $pageCurrent = 1;


	public $maxPerPage = 5;


	public $totalRows;


	public $possiblePages;
	

	public function initialise()
	{
		if (! $this->getTotalRows()) {
			return;
		}

		// check validity
		if (($this->sanitizePage())) {
			$this->pageCurrent = $_GET['page'];
		}

        // setup possible page count and set up the pagination array
        $this->setPossiblePages();
		$this->setPagination();
	}


	public function getTotalRows()
	{
		return $this->totalRows;
	}


	public function setTotalRows($value)
	{
		$this->totalRows = $value;
	}


	public function setPossiblePages()
	{
		$this->possiblePages = ceil($this->totalRows / $this->maxPerPage);
	}


	public function getLimit()
	{
		$bottom = ($this->maxPerPage * ($this->pageCurrent - 1));
		$top = $this->maxPerPage;
		return array($bottom, $top);
	}


	/**
	 * constructs an array to allow the user to paginate
	 * possibly have 2 options, one with full pagination
	 * another with just next and previous
	 * @return array 
	 */
	public function setPagination()
	{
        $this->data[] = array(
            'name' => 'previous'
            , 'current' => ($this->pageCurrent == $this->pageCurrent - 1 ? true : false)
            , 'url' => $this->getUrl($this->pageCurrent - 1)
        );
        for ($i = 1; $i <= $this->possiblePages; $i++) { 
            $this->data[] = array(
                'name' => 'page'
                , 'current' => ($this->pageCurrent == $i ? true : false)
                , 'url' => $this->getUrl($i)
            );
        }
        $this->data[] = array(
            'name' => 'next'
            , 'current' => ($this->pageCurrent == $this->pageCurrent + 1 ? true : false)
            , 'url' => $this->getUrl($this->pageCurrent + 1)
        );
        return $this->data;
	}
	

    /**
     * returns a url without any queries except the page
     * number
     * @param  int $pageNumber 
     * @return string             url
     */
    public function getUrl($type = false, $name = false, $id = false)
    {

		// needs to also strip out any previous instances of the page get variable.............

    	$url = $this->url->getCache('current');
		$prepend = (strpos($url, '?') !== false ? '&' : '?');
		return $url . ($type ? $prepend . 'page=' . $type : '');
    }

	
	/**
	 * Next Page
	 *
	 * @return string|false The array value or false if it does not exist
	 */	
	public function nextPage()
	{
		return $this->pageCurrent++;
	}


	public function getCurrentPage()
	{
		return $this->pageCurrent;
	}

	
	/**
	 * Check Page GET Variable
	 *
	 * @todo remove ability to add '-100' perhaps using regex match on '-'?
	 * @return true|false The page GET value
	 */	
	public function sanitizePage($valid = false)
	{
		if (array_key_exists('page', $_GET)) {
		
			// Convert to int
			$_GET['page'] = (int)$_GET['page'];
			
			// Check for Null Value
			$valid = $_GET['page'] == 0 ? false : true;
			return $valid;					
		} else {
			return $valid;
		}
	}


	public function setMaxPerPage($number)
	{
		return $this->maxPerPage = $number;
	}
}
