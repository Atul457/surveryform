<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\TraitsFolder\BladeDirectives;
use App\Resources\RouteResource;

class BladeServiceProvider extends ServiceProvider{
	public function register(){}
	public function boot(){
		Blade::if('accessible',function(){
			return auth()->user()->can('access',new RouteResource(...func_get_args()));
		});

		Blade::if('notaccessible',function(){
			return !auth()->user()->can('access',new RouteResource(...func_get_args()));
		});

		Blade::if('allnotaccessible',function(){
			$inaccessible_route_count = 0;
			$args = func_get_args();
			foreach($args as $route){
				if(gettype($route) == 'string'){
					$route = [$route];
				}
				if(auth()->user()->cannot('access',new RouteResource(...$route))){
					$inaccessible_route_count++;
				}
			}

			if($inaccessible_route_count == count($args)) return false;
			return true;
		});
	}
}