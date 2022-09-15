<?php
require('vendor/autoload.php');
use App\Models\Cuenta;
use App\Helper\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => DB::DRIVER_DB,
    'host' => DB::HOST_DB,
    'database' => DB::DATABASE_NAME,
    'username' => DB::USER_DB,
    'password' => DB::PASSWORD_DB,
    'charset' => DB::UTF_8_DB,
    'collation' => DB::COLLATION_UTF,
    'prefix' => DB::PREFIX,
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();


header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");



function validar_login($usuraio,$contra){
    $pwd=md5($contra);
    $cuenta=Cuenta::where('usuario',$usuraio)->where('contra',$pwd)->first();
    $v=false;
    $id_cuenta=0;
    if ($cuenta!=null) {
        $v=true;
        $id_cuenta=$cuenta->ID;
    }
    echo json_encode(array("estado"=>$v,"id_cuenta"=>$id_cuenta));
}
$data = json_decode(file_get_contents("php://input"), true);
if ($data!=null) {
    switch ($data["case"]) {
        case 0:
            validar_login($data["usuraio"],$data["contra"]);
            break;
    }
}else{
    echo json_encode(array("respuesta"=>"Sin Acceso"));
}
?>
