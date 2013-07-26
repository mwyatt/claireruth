<?php

/**
 * Limiting Queries and production of pagination HTML
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Pagination extends Model
{

	public $SQL;
	public $pageLimit = 2;
	public $pageCurrent = 1;
	public $totalRows;
	
	
	/**
	 * Constructor
	 *
	 * @param object $DBH Database handle
	 * @return void
	 */	
	public function __construct($DBH) {
		$this->DBH = $DBH;
		
		// Check Page
		if (($this->sanitizePage())) {
			$this->pageCurrent = $_GET['page'];
		}
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
	 * 
	 *
	 * @param string $key The array key to return
	 * @return string|false The array value or false if it does not exist
	 */	
	public function select()
	{
		$STH = $DBH->query("
			SELECT
				COUNT(id)
			FROM
				content
			WHERE
				type = 'post'
			AND
				status = 'visible'
			ORDER BY
				date_published DESC
		");

		$value = $STH->fetch(PDO::FETCH_NUM);

		echo '<pre>';
		print_r ($value);
		echo '</pre>';
		exit;		
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

