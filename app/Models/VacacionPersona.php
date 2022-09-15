<?php
namespace App\Models;
use App\Models\Trabajadores;
use App\Models\VacacionesDias;
use Illuminate\Database\Eloquent\Model;
class VacacionPersona extends Model{
    protected $table="vacacion_persona";
    public $timestamps=false;
    protected $fillable = ['ID','Vacacion','Trabajador','Anho'];
    
    public function trabajador() {
        return $this->hasOne(Trabajadores::class,'ID','Trabajador');
    }
    public function vacaciones_dias() {
        return $this->hasOne(VacacionesDias::class,'ID','Vacacion');
    }
}
?>