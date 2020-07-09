<?php
namespace App\Controllers;

use \TypeRocket\Controllers\Controller;

class AnnonceurController extends Controller
{

	public function test(){

		return tr_view('annonceur.test');
	}
}