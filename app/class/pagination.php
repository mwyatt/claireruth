<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Pagination extends Model
{


	public $tableName;


	public $pageCurrent = 1;


	public $maxPerPage = 3;


	public $totalRows;


	public $possiblePages;
	

	/**
	 * always initiates with the session, database and config
	 * @param object $database 
	 * @param object $config   
	 */
	public function __construct($database, $config, $tableName) {
		$this->session = new Session();
		$this->database = $database;
		$this->config = $config;
		$this->tableName = $tableName;
		$this->totalRows = $this->config->getOption('model_' . $this->tableName . '_rowcount');

		// check validity
		if (($this->sanitizePage())) {
			$this->pageCurrent = $_GET['page'];
		}

        // setup possible page count and set up the pagination array
        $this->setPossiblePages();
		$this->setPagination();
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
            , 'guid' => $this->getUrl($this->pageCurrent - 1)
        );
        for ($i = 1; $i <= $this->possiblePages; $i++) { 
            $this->data[] = array(
                'name' => 'page'
                , 'current' => ($this->pageCurrent == $i ? true : false)
                , 'guid' => $this->getUrl($i)
            );
        }
        $this->data[] = array(
            'name' => 'next'
            , 'current' => ($this->pageCurrent == $this->pageCurrent + 1 ? true : false)
            , 'guid' => $this->getUrl($this->pageCurrent + 1)
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
        return $this->config->getUrl('current_noquery') . ($type ? '?page=' . $type : '');
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
			
			//var_dump($_GET['page']);
			//exit(var_dump($valid));
			
			return $valid;					
		} else {
			//exit(var_dump($valid));
			return $valid;
		}
	}
}


/*

<?php

 * @link: http://www.Awcore.com/dev
 
   function pagination($query, $per_page = 10,$page = 1, $url = '?'){        
    	$query = "SELECT COUNT(*) as `num` FROM {$query}";
    	$row = mysql_fetch_array(mysql_query($query));
    	$total = $row['num'];
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details'>Page $page of $lastpage</li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a class='current'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li><a href='{$url}page=$next'>Next</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>Last</a></li>";
    		}else{
    			$pagination.= "<li><a class='current'>Next</a></li>";
                $pagination.= "<li><a class='current'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";		
    	}
    
    
        return $pagination;
    } 
?>

*/

