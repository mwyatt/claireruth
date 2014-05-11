<?php

/**
 * Menu Crafter
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Admin_Menu extends Model
{


    /**
     * exclusions from the class
     * @var array
     */
    public $methodExclusions = array('initialise', 'index');


	/**
     * builds an array for the administration area, 2 levels deep
     * @return bool
     */
	public function read($select = '', $where = array(), $ids = array(), $limit = array()) {
        $controllerMethods = get_class_methods('controller');
		$baseClassMethods = array_diff(get_class_methods('controller_admin'), $controllerMethods, $this->methodExclusions);
		$finalList[] = array(
			'title' => 'Dashboard'
            , 'current' => ($this->url->getPathPart(1) == '' ? true : false)
            , 'children' => array()
            , 'url' => $this->url->getCache('admin')
        );
        foreach ($baseClassMethods as $classMethod) {
            $subClassMethods = array();
            $refinedSubClassMethods = array();
            if (class_exists($subClassName = 'controller_admin_' . $classMethod)) {
                $subClassMethods = array_diff(get_class_methods($subClassName), $controllerMethods, $this->methodExclusions);
            }

            // rarrrrrrw
            $subClassMethods[] = 'page';
            foreach ($subClassMethods as $subClassMethod) {
                $refinedSubClassMethods[] = array(
                    'title' => ucfirst($subClassMethod)
                    , 'url' => $this->url->build(array('admin', $classMethod, $subClassMethod))
                    , 'current' => ($this->url->getPathPart(2) == strtolower($subClassMethod) ? true : false)
                    , 'children' => array()
                );
            }
            $finalList[] = array(
                'title' => ucfirst($classMethod)
                , 'url' => $this->url->build(array('admin', $classMethod))
    			, 'current' => ($this->url->getPathPart(1) == strtolower($classMethod) ? true : false)
                , 'children' => $refinedSubClassMethods
            );
        }
		return $this->setData($finalList);
    }	



    public function getMenuItem($value='')
    {
        # code...
    }


	/**
	 * attempts to find a sub controller and builds a nav menu using its
	 * methods (?page=method)
	 * @return html the menu
	 */
	public function adminSub() {
		$user = new model_user($this);
		$className = 'Controller_' . ucfirst($this->url->getPathPart(0)) . '_' . ucfirst($this->url->getPathPart(1));
		if (class_exists($className)) {
			foreach ($this->getClassMethods($className) as $key => $method) {
				if (($method !== 'initialise') && ($method !== 'index') && ($method !== 'load') && ($method !== '__construct')) {
					$this->data['admin_sub'][$key]['name'] = ucfirst($method);
					$this->data['admin_sub'][$key]['current'] = ($this->url->getPathPart(2) == $method ? true : false);
					$this->data['admin_sub'][$key]['guid'] = $this->url->getCache('base') . $this->url->getPathPart(0) . '/' . $this->url->getPathPart(1). '/' . $method . '/';
				}
			}
		}
		return;
	}













    /**
      *	Gets a full menu tree
      *	@method		get
      *	@param		string type
      *	@returns	assoc array if successful, empty array otherwise
      */
    private function select($type, $parent)
    {		
    	if ($parent)
    		$parent = " AND parent_id = '{$parent}' ";
    
    	$SQL = "
    		SELECT
    			id
    			, title
    			, guid
    			, parent_id
    			, position
    			, type
    		FROM
    			menu
    		WHERE
    			type = '{$type}'
    		{$parent}
    		ORDER BY
    			position ASC
    	";
    	$sth = $this->database->dbh->query($SQL); // execute	

    	return $this->setResult($sth->fetchAll(PDO::FETCH_ASSOC));
    	
    }	
    
    
    /**
     * Works with methods to return a full tree of type
     * @method		build
     * @param		array results
     * @returns	html output if successful, false otherwise
     */	
    public function build($results, $parent = 0)
    {		
    	$html = '<ol class="depth_'.$parent.'">';
    	
    	foreach ($results as $result) {
    		if ($result['parent_id'] == $parent) {
    			
    			// Construct Class Attribute
    			$class = '';
    			$class .= 'class="';
    			$class .= 'id_'.$result['id'].' ';
    			$class .= ($this->url->getPathPart(0) == $result['title'] ? ' current' : false);
    			$class .= '"';
    			
    			// Append List Item
    			$html .= '<li '.$class.'><div><a href="'.$result['guid'].'">'.$result['title'].'</a></div>';
    			
    			if ($this->hasChild($results, $result['id'])) {
    				$html .= $this->build($results, $result['id']);
    				$html .= '</li>';
    			}
    			
    		}
    	}
    	
    	$html .= '</ol>';		
    	
    	// Return
    	return $this->data = $html;
    }	
    
    
    /**
      *	Works with method build
      *	@method		hasChild
      *	@param		array results
      *	@id			string id
      *	@returns	children if successful, false otherwise
      */	
    public function hasChild($results, $id)
    {
    	foreach ($results as $result) {
    		if ($result['parent_id'] == $id)
    		return true;
    	}
    	return false;
    }	
	
}