<?php
use Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection(require __DIR__ . '/src/config/database.php');
$capsule->setAsGlobal();
$capsule->bootEloquent();

Capsule::schema()->create('weather', function ($table) {
    $table->increments('id');
    $table->datetime('time');
    $table->integer('snowfraction');
    $table->float('windspeed');
    $table->float('temperature');
    $table->integer('precipitation_probability');
    $table->float('convective_precipitation');
    $table->string('rainspot');
    $table->integer('pictocode');
    $table->float('felttemperature');
    $table->float('precipitation');
    $table->integer('isdaylight');
    $table->integer('uvindex');
    $table->integer('relativehumidity');
    $table->float('sealevelpressure');
    $table->integer('winddirection');
    $table->timestamps();
});