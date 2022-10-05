<?php
declare(strict_types = 1);

namespace App\Resources;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Str,Log;

class RouteResource{
	public string $moduleName = '';
	public string $modulePermission = '';
	public array $routeParameters = [];
	public function __construct(){
		if(empty(func_get_args())){
			$route = Route::current();
			$this->routeParameters = $route->parameters();
		}else{
			$arguments = func_get_args();
			$route_name = array_shift($arguments);
			$route_parameters = [];
			if(!empty($arguments[0])){
				$route_parameters = $arguments[0];
				if(gettype($route_parameters) == 'string'){
					$route_parameters = [$route_parameters];
				}
			}
			$route = Route::getRoutes()->getByName($route_name);
			$parameter_names = $route->parameterNames();
			$i = 0;
			foreach($parameter_names as $parameter_name){
				if(isset($route_parameters[$i])){
					$this->routeParameters[$parameter_name] = $route_parameters[$i];
				}
				$i++;
			}
		}
		// echo "<pre>";
		// print_r([
		// 	"moduleName" => $route->moduleName,
		// 	"modulePermission" => $route->modulePermission
		// ]);
		// echo "<br>";
		$this->moduleName = $route->moduleName ?? '';
		$this->modulePermission = $route->modulePermission ?? '';
	}
	public function modulePermission():string{
		return $this->modulePermission;
	}
	public function moduleName():string{
		return $this->moduleName;
	}
	public function routeParameters():array{
		return $this->routeParameters;
	}
}