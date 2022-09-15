<?php
namespace App\Models;
use App\Models\Guardia;
use App\Models\EmpresaGuardiaSucursal;
use Illuminate\Database\Eloquent\Model;
class GuardiaSucursal extends Model{
    protected $table="guardias_sucursal";
    public $timestamps=false;
    protected $fillable = ['ID','Guardia','GS'];
    public function guardia() {
        return $this->hasOne(Guardia::class,'ID','Guardia');
    }
    public function empresa_guardia_sucursal() {
        return $this->hasOne(EmpresaGuardiaSucursal::class,'ID','GS');
    }
}
?>