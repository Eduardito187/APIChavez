<?php
namespace App\Models;
use App\Models\ControlDiscos;
use Illuminate\Database\Eloquent\Model;
class SalidaDiscos extends Model{
    protected $table="salida_discos";
    public $timestamps=false;
    protected $fillable = ['ID','FechaEntrega','Nombre','Detalle'];
    public function control() {
        return $this->hasOne(ControlDiscos::class,'Salida','ID');
    }
}
?>