<?php
namespace App\Models;
use App\Models\Trabajadores;
use Illuminate\Database\Eloquent\Model;
class TablaControl extends Model{
    protected $table="tabla_control";
    public $timestamps=false;
    protected $fillable = ['ID','Trabajador','Anho','Libre','BajaMedica','Permisos','Faltas'];
    public function trabajador() {
        return $this->hasOne(Trabajadores::class,'ID','Trabajador');
    }
}
?>