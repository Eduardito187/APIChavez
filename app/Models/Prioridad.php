<?php
namespace App\Models;
use App\Models\Seguimiento;
use App\Models\Tareas;
use Illuminate\Database\Eloquent\Model;
class Prioridad extends Model{
    protected $table="prioridad";
    public $timestamps=false;
    protected $fillable = ['ID', 'Nombre','Descripcion'];
    public function seguimiento() {
        return $this->hasMany(Seguimiento::class,'Prioridad','ID');
    }
    public function tareas() {
        return $this->hasMany(Tareas::class,'Prioridad','ID');
    }
}
?>