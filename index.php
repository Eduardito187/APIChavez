<?php
require('vendor/autoload.php');
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Helper\DB;
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
require('graphql/boot.php');

?>
