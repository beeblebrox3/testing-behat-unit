<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class User extends \Illuminate\Database\Migrations\Migration
{
    /**
     * @var string
     */
    protected $table = 'user';

    public function up()
    {
        Capsule::schema()->dropIfExists($this->table);

        Capsule::schema()->create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->string('email', 160);
            $table->unique('email');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists($this->table);
    }
}