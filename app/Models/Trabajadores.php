<?php
namespace App\Models;
use App\Models\VacacionPersona;
use App\Models\TablaControl;
use App\Models\Control;
use Illuminate\Database\Eloquent\Model;
class Trabajadores extends Model{
    protected $table="trabajadores";
    public $timestamps=false;
    protected $fillable = ['ID','Nombre','Apellido','CI','Puesto','FechaContratacion','Supervisor'];
    public function supervisor() {
        return $this->hasOne(Trabajadores::class,'ID','Supervisor');
    }
    public function vacaciones_personas() {
        return $this->hasMany(VacacionPersona::class,'Trabajador','ID');
    }
    public function tablas_controles() {
        return $this->hasMany(TablaControl::class,'Trabajador','ID');
    }
    public function controles() {
        return $this->hasMany(Control::class,'Trabajador','ID');
    }
}
?>