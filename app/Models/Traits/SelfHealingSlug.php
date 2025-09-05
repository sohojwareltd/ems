<?php
namespace App\Models\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;

trait SelfHealingSlug
{
    public function getRouteKey()
    {
        if(request()->is('admin/*')){
            return $this->id;
        }else{
            return $this->slug . '-' . $this->id;
        }
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if(request()->is('admin/*')){
            $id = $value;
        }else{
            $id = last(explode('-', $value));
        }
        $model = parent::resolveRouteBinding($id, $field);
        if (!$model) abort(404);
        if ($model->getRouteKey() == $value) {
            return $model;
        }
        throw new HttpResponseException(redirect()->to(route(request()->route()->getName(), $model)));
    }
}
