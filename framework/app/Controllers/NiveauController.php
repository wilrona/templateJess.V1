<?php
namespace App\Controllers;

use \App\Models\Niveau;
use \TypeRocket\Controllers\WPTermController;

class NiveauController extends WPTermController
{
    protected $modelClass = Niveau::class;
}