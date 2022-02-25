<?php
namespace Engine;


class Router extends Model {
	
	public function route($routes) {
		$uri = trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');
		if ($uri==='') $uri = '/'; // FIX start page url
		foreach($routes as $route) {
			$rule = $route['rule'];
			$action = $route['action'];
			$data = array();
			
			// Check ajax queries
			if (array_key_exists('ajax', $route) && $route['ajax'] != $this->rk->request->isAjax()) continue;
			
			// Check $_GET, $_POST, $_REQUEST
			$next = false;
			foreach (array('request', 'get', 'post') as $var) {
				if (array_key_exists($var, $route)) {
					$_ = $this->checkRequest($route[$var], $var);
					if ($_ === false) {
						$next = true;
						break;
					}
					$data = array_replace_recursive($data, $_);
				}
			}
			
			if ($next) continue;
			
			$result = preg_match('@^'.$rule.'$@i', $uri, $matches);
			if ($result===1) {
				list($action, $vars) = array_pad(explode('(', trim($action, ')')), 2, '');
				$vars = explode(',', 'uri,'.$vars);
				foreach($matches as $i => $match) {
					if(array_key_exists($i, $vars)) {
						$data[$vars[$i]] = $match;
					} else {
						$data[$i] = $match;
					}
				}
				echo $this->rk->run($action, $data);
				return true;
			}
		}
		return false;
	}
	
	
	public function checkRequest($array, $var='request') {
		$var =& $this->rk->request->$var;
		$data = array();
		foreach ($array as $key => $regexp) {
			if (!array_key_exists($key, $var)) return false;
			if (preg_match('@^'.$regexp.'$@i', $var[$key]) !== 1) return false;
			$data[$key] = $var[$key];
		}
		return $data;
	}
	
	
	public function routeFile($file) {
		$config = $this->rk->getConfig($file);
		$routes = array();
		foreach($config as $rule => $action) {
			if (is_string($action)) {
				$routes[] = compact('rule', 'action');
			}
			else {
				$routes = $config;
				break;
			}
		}
		return $this->route($routes);
	}
	
	
	public function routeDB() {
		$routes = $this->select('SELECT rule, action FROM rk_routes ORDER BY priority ASC');
		return $this->route($routes);
	}
	
}