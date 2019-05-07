<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MyModel extends Authenticatable {

    use Notifiable;

    protected $table;
    protected $dependency;

    public function deleteValidate($id) {
        $msg = array();
        if (!empty($this->dependency) && !empty($id)) {
            foreach ($this->dependency as $k => $row) {
                $row = (object) $row;
                $model = app()->make($row->model);
                if ($model->where($row->field, $id)->count()) {
                    $msg[] = $k;
                }
            }
            if (!empty($msg)) {
                $msg = implode(", ", $msg);
            }
        }
        return $msg;
    }

}
