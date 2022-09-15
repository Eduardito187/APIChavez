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

if (isset($_FILES["file"]) && isset($_GET["ID"])) {
    $cuenta=Cuenta::find($_GET["ID"]);
    if ($cuenta==null) {
        echo "Cuenta Invalida.";
    }else{
        $extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $n=$cuenta->usuario.".".$extension;
        $dir="./graphql/perfiles/".$n;
        if (file_exists($cuenta->DIR)) {
            unlink($cuenta->DIR);
        }
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $dir)) {
            Cuenta::where('ID', $_GET["ID"])->update([
                'foto' => "http://localhost:8888/APIChavez/graphql/perfiles/".$n,
                'DIR' => $dir
            ]);
            echo "Archivo Subido.";
        } else {
            echo "Error al subir la foto.";
        }
    }
}else{
    echo "Error en envio.";
}
?>
