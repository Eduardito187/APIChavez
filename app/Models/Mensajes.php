<?php
namespace App\Models;
use App\Models\Cuenta;
use Illuminate\Database\Eloquent\Model;
class Mensajes extends Model{
    protected $table="mensajes";
    public $timestamps=false;
    protected $fillable = ['ID','De','Para','Texto','Fecha','Leido','F_Leido'];
    public function De_msg() {
        return $this->hasOne(Cuenta::class,'ID','De');
    }
    public function Para_msg() {
        return $this->hasOne(Cuenta::class,'ID','Para');
    }
}
?>