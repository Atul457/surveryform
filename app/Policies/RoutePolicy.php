<?php

declare(strict_types = 1);
namespace App\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Log;
use App\Resources\RouteResource;
use Illuminate\Support\Facades\Auth;

class RoutePolicy{
	use HandlesAuthorization;
	public function access(User $user, RouteResource $route):bool{
		if($user->is_admin == "1") return true;
		$module_name = $this->resolveModuleName($route);
		$permission_required = $this->resolveModulePermission($route);
		if( $user->permission)
			$permissions_assigned = $user->permission->where('module_name',$module_name)->where("user_id", $user->id)->where("status", 1)->pluck('permissions')->first();
		$permissions_assigned = json_decode($permissions_assigned ?? '',true) ?? [];
		// if($module_name == "create_company")
		// dd([
		// 	"permission_required" => $permission_required,
		// 	"permissions_assigned" => $permissions_assigned,
		// 	'module_name' => $module_name,
		// 	'result' => $this->canAccess($permission_required,$permissions_assigned),
		// 	"travesable" => is_array($permissions_assigned)
		// ]);
        return $this->canAccess($permission_required,$permissions_assigned);
		return false;
	}
	private function resolveModulePermission(RouteResource $route):string{
		return $route->modulePermission();
	}
	private function resolveModuleName(RouteResource $route):string{
		return $route->moduleName();
	}
	private function resolveRouteParameters(RouteResource $route):array{
		return $route->routeParameters();
	}
	private function canAccess(string $permission_required,array $permissions_assigned):bool{
		switch($permission_required){
			case 'view':
				return in_array('view',$permissions_assigned);
			break;
			case 'edit':
				return (in_array('edit',$permissions_assigned));
			case 'create':
				return (in_array('create',$permissions_assigned));
			break;
            case 'update':
				return (in_array('update',$permissions_assigned));
			break;
            case 'delete':
				return (in_array('delete',$permissions_assigned));
			break;
		}
		return false;
	}
}