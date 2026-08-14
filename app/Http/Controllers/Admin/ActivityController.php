<?php namespace App\Http\Controllers\Admin;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All Rights Reserved
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserActivityRequest;
use App\Models\UserEvent;
use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Eventos del usuarios del sistema
 * 
 * Muestra las actividades que se han realizado dentro del sistema. (Se require observadores)
 *  
 * @author Moisés Cortés C. <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
class ActivityController extends Controller
{
    /**
     * Actividades del usuario
     */
    public function index(UserActivityRequest $request)
    {
        $filters = $request->all();

        $model = UserEvent::with('user:id,name,paternal,maternal,profile_photo_path,deleted_at')
            ->when(isset($filters['user']) && !empty($filters['user']), function($query) use ($filters){
                $query->where('user_id', $filters['user']);
            })
            ->when(isset($filters['search']) && !empty($filters['search']), function($query) use ($filters){
                $query->where('event', 'like', '%'.$filters['search'].'%');
            })
            ->when(isset($filters['start_date']) && !empty($filters['start_date']), function($query) use ($filters){
                $query->where('created_at', '>=', "{$filters['start_date']} 00:00:00");
            })
            ->when(isset($filters['end_date']) && !empty($filters['end_date']), function($query) use ($filters){
                $query->where('created_at', '<=', "{$filters['end_date']} 23:59:59");
            });

        return ApiResponse::OK->response([
            'models' => 
                $model->orderBy('created_at', 'desc')
                ->paginate(config('app.pagination'))
        ]);
    }
}
