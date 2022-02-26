<?php
namespace Engine;
use RK;


class Router extends Model {
	
	public function route($routes) {
		$uri = RK::self()->request->uri;
		foreach($routes as $route) {
			$rule = $route['rule'];
			$action = $route['action'];
			$data = array();
			
			// Check ajax queries
			if (array_key_exists('ajax', $route) && $route['ajax'] != RK::self()->request->is_ajax) continue;
			
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
				RK::self()->run($action, $data);
				return true;
			}
		}
		return false;
	}
	
	
	public function checkRequest($array, $var='request') {
		$var =& RK::self()->request->$var;
		$data = array();
		foreach ($array as $key => $regexp) {
			if (!array_key_exists($key, $var)) return false;
			if (preg_match('@^'.$regexp.'$@i', $var[$key]) !== 1) return false;
			$data[$key] = $var[$key];
		}
		return $data;
	}
	
	
	public function routeFile($file) {
		$config = RK::self()->getConfig($file);
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
		// print_r($routes);exit;
		return $this->route($routes);
	}
	
	
	public function routeDB() {
		$routes = $this->select('SELECT rule, action FROM rk_routes ORDER BY priority ASC');
		return $this->route($routes);
	}
	
}