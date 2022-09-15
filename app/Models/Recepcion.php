<?php
namespace App\Models;
use App\Models\Cuenta;
use Illuminate\Database\Eloquent\Model;
class Recepcion extends Model{
    protected $table="recepcion";
    public $timestamps=false;
    protected $fillable = ['ID','Fecha','Responsable','Entregado','Descripcion'];
    public function cuenta() {
        return $this->hasOne(Cuenta::class,'ID','Responsable');
    }
}
?>