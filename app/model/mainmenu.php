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
class Model_Mainmenu extends Model
{


	public $type;

	public $html = '';

	// public $adminSubMenu;

	
	// public function getAdminSubMenu()
	// {		
		
	// 	if ($this->adminSubMenu)

	// 		echo $this->adminSubMenu;

	// 	return false;

	// }


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
	 * @param string type
	 * @returns	true if successful, false otherwise
	 */	
	public function create($type, $parent = false)
	{
		
		$this->type = $type;
		$this->select($type, $parent);
		
		if ($this->getResult())
			return '<nav id="'.$this->type.'">'.$this->build($this->getResult()).'</nav>';
		else
			return false;
			
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
				$class .= ($this->config->getUrl(0) == $result['title'] ? ' current' : false);
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
	


	
	
	// public function getAdminSubMethods() {
	// 	foreach ($this->getAdminMethods() as $method) {
	// 		foreach (get_class_methods('Controller_Admin') as $method) {
	// 		if (($method !== 'initialise') && ($method !== 'index') && ($method !== 'load') && ($method !== '__construct')) {
	// 			$methods[] = array(
	// 				'name' => ucfirst($method)
	// 				, 'current' => ($this->config->getUrl(1) == $method ? true : false)
	// 				, 'guid' => $baseUrl . $method . '/'
	// 			);
	// 		}
	// 	}
	// 	return $methods;
	// }
	

	/**
	 * attempts to find a sub controller and builds a nav menu using its
	 * methods (?page=method)
	 * @return html the menu
	 */
	public function adminSub() {
		$user = new model_mainuser($this->database, $this->config);
		if ($user->get('email') == 'martin.wyatt@gmail.com') {
			$className = 'Controller_' . ucfirst($this->config->getUrl(0)) . '_' . ucfirst($this->config->getUrl(1));
			if (class_exists($className)) {
				foreach ($this->getClassMethods($className) as $key => $method) {
					if (($method !== 'initialise') && ($method !== 'index') && ($method !== 'load') && ($method !== '__construct')) {
						$this->data['admin_sub'][$key]['name'] = ucfirst($method);
						$this->data['admin_sub'][$key]['current'] = ($this->config->getUrl(2) == $method ? true : false);
						$this->data['admin_sub'][$key]['guid'] = $this->config->getUrl('base') . $this->config->getUrl(0) . '/' . $this->config->getUrl(1). '/' . $method . '/';
					}
				}
			}
		}
		if ($user->get('email') == 'realbluesman@tiscali.co.uk') {
			$className = 'Controller_' . ucfirst($this->config->getUrl(0)) . '_' . ucfirst($this->config->getUrl(1));
			if (class_exists($className)) {
				foreach ($this->getClassMethods($className) as $key => $method) {
					if (($method !== 'initialise') && ($method !== 'index') && ($method !== 'load') && ($method !== '__construct')) {
						$this->data['admin_sub'][$key]['name'] = ucfirst($method);
						$this->data['admin_sub'][$key]['current'] = ($this->config->getUrl(2) == $method ? true : false);
						$this->data['admin_sub'][$key]['guid'] = $this->config->getUrl('base') . $this->config->getUrl(0) . '/' . $this->config->getUrl(1). '/' . $method . '/';
					}
				}
			}
		}		
		
		return;
	}


	/**
	  *	Builds menu tree for admin area
	  *	@param		string type
	  *	@returns	assoc array if successful, empty array otherwise
	  */
	public function admin() {
		$this->data['admin'][] = array(
			'name' => 'Dashboard'
			, 'current' => ($this->config->getUrl(1) == '' ? true : false)
			, 'guid' => $this->config->getUrl('admin')
		);
		$user = new model_mainuser($this->database, $this->config);
		if ($accessTo = $user->getPermission($user->get('level'))) {
			foreach ($this->getClassMethods('controller_admin') as $adminMethod) {
				foreach ($this->getClassMethods('controller_admin_' . $adminMethod) as $method) {
					$methods[]=$method;
					if (! in_array($method, $accessTo)) {
						continue;
					}
					$this->data['admin'][] = array(
						'name' => ucfirst($method)
						, 'current' => ($this->config->getUrl(2) == $method ? true : false)
						, 'guid' => $this->config->getUrl('admin') . $adminMethod . '/' . $method . '/'
					);
				}
			}
			return;
		}
		foreach ($this->getClassMethods('controller_admin') as $key => $method) {
			$this->data['admin'][$key]['name'] = ucfirst($method);
			$this->data['admin'][$key]['current'] = ($this->config->getUrl(1) == $method ? true : false);
			$this->data['admin'][$key]['guid'] = $this->config->getUrl('admin') . $method . '/';
		}
		return;
    }	


    /**
     * frontend main menu
     */
    public function division() {
    	$ttDivision = new Model_Ttdivision($this->database, $this->config);
    	$ttDivision->read();
    	foreach ($ttDivision->getData() as $key => $division) {
    		$divisionDrop[] = array(
				'name' => ucfirst($division['name'])
				, 'current' => ($this->config->getUrl(1) == strtolower($division['name']) ? true : false)
				, 'guid' => $division['guid']
			);
		}
		$leagueDrop[] = array(
			'name' => 'Handbook'
			, 'current' => false
			, 'guid' => $this->config->getUrl('base') . 'media/handbook.pdf'
		);
		$leagueDrop[] = array(
			'name' => 'Player performance'
			, 'current' => ($this->config->getUrl(1) == 'performance' ? true : false)
			, 'guid' => $this->config->getUrl('base') . 'player/performance/'
		);
		$leagueDrop[] = array(
			'name' => 'Press releases'
			, 'current' => ($this->config->getUrl(0) == 'press' ? true : false)
			, 'guid' => $this->config->getUrl('base') . 'press/'
		);
		$leagueDrop[] = array(
			'name' => 'Competitions'
			, 'current' => ($this->config->getUrl(1) == 'competitions' ? true : false)
			, 'guid' => $this->config->getUrl('base') . 'page/competitions/'
		);
		$leagueDrop[] = array(
			'name' => 'Contact us'
			, 'current' => ($this->config->getUrl(1) == 'contact-us' ? true : false)
			, 'guid' => $this->config->getUrl('base') . 'page/contact-us/'
		);
    	$this->data['main'][] = array(
    		'name' => 'Home'
    		, 'current' => (! $this->config->getUrl(0) ? true : false)
    		, 'guid' => $this->config->getUrl('base')
		);
    	$this->data['main'][] = array(
    		'name' => 'Tables and Results'
    		, 'current' => ($this->config->getUrl(0) == 'division' || $this->config->getUrl(0) == 'tables-and-results' ? true : false)
    		, 'guid' => $this->config->getUrl('base') . 'tables-and-results/'
    		, 'drop' => array(
    			'name' => 'division'
    			, 'items' => $divisionDrop
			)
		);
    	$this->data['main'][] = array(
    		'name' => 'The League'
    		, 'current' => false
    		, 'guid' => '#'
    		, 'drop' => array(
    			'name' => 'league'
    			, 'items' => $leagueDrop
			)
		);
		return;
    }

	
}