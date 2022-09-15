<?php
namespace App\Models;
use App\Models\Cuenta;
use App\Models\Permisos;
use Illuminate\Database\Eloquent\Model;
class Rango extends Model{
    protected $table="rango";
    public $timestamps=false;
    protected $fillable = ['ID', 'Nombre'];

    public function cuenta() {
        return $this->hasMany(Cuenta::class,'RangoID','ID');
    }
    public function permisos() {
        return $this->hasOne(Permisos::class,'RangoID','ID');
    }
}
?>