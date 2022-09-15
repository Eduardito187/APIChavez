<?php
namespace App\Models;
use App\Models\VacacionPersona;
use Illuminate\Database\Eloquent\Model;
class VacacionesDias extends Model{
    protected $table="vacacionesdias";
    public $timestamps=false;
    protected $fillable = ['ID', 'Dias'];

    public function vacaciones_personas() {
        return $this->hasMany(VacacionPersona::class,'Vacacion','ID');
    }
}
?>