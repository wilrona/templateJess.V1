<?php
namespace App\Controllers;

use \App\Models\Competence;
use \TypeRocket\Controllers\WPTermController;

class CompetenceController extends WPTermController
{
    protected $modelClass = Competence::class;
}