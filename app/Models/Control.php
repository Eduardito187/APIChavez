<?php
namespace App\Models;
use App\Models\Trabajadores;
use Illuminate\Database\Eloquent\Model;
class Control extends Model{
    protected $table="control";
    public $timestamps=false;
    protected $fillable = ['ID','Trabajador','Fecha','Tipo','Motivo'];
    public function trabajador() {
        return $this->hasOne(Trabajadores::class,'ID','Trabajador');
    }
}
?>