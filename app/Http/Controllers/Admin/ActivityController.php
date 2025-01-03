<?php namespace App\Http\Controllers\Admin;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserActivityRequest;
use App\Models\UserEvent;
use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Eventos del usuarios del sistema
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
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

        $model = UserEvent::with('user:id,name,paternal,maternal,profile_photo_path,deleted_at');

        if(isset($filters['user']) && !empty($filters['user'])){
            $model->where('user_id', $filters['user']);
        }

        if(isset($filters['search']) && !empty($filters['search'])){
            $model->where('event', 'like', '%'.$filters['search'].'%');
        }

        if(isset($filters['start_date']) && !empty($filters['start_date'])){
            $model->where('created_at', '>=', "{$filters['start_date']} 00:00:00");
        }

        if(isset($filters['end_date']) && !empty($filters['end_date'])){
            $model->where('created_at', '<=', "{$filters['end_date']} 23:59:59");
        }

        return ApiResponse::OK->response([
            'models' => 
                $model->orderBy('created_at', 'desc')
                ->paginate(config('app.pagination'))
        ]);
    }
}
