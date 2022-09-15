<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\GuardiaSucursal;
use App\Models\ControlDiario;
class Guardia extends Model{
    protected $table="guardias";
    public $timestamps=false;
    protected $fillable = ['ID','Nombre','Telefono','Precio'];
    public function guardia_sucursal() {
        return $this->hasOne(GuardiaSucursal::class,'Guardia','ID');
    }
    public function control_diario() {
        return $this->hasMany(ControlDiario::class,'Guardia','ID');
    }
}
?>