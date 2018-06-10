<?php
namespace App\Controllers;

use \TypeRocket\Controllers\Controller;
use WP_Query;

class CandidatureController extends Controller
{

    /**
     * The index page for admin
     *
     * @return mixed
     */
    public function index()
    {
        // TODO: Implement index() method.

	    $paged = $_GET['paged'];

	    $args = array(
		    'post_type' => 'emploi',
		    'posts_per_page' => 20,
		    'paged' => $paged
	    );

	    $data = [];

	    if($_GET['action'] == 'search'){
		    $data['application_name'] = $_GET['application_name'];
		    $args = array(
			    'post_type' => 'emploi',
			    'posts_per_page' => 20,
			    's' => $_GET['application_name'],
			    'paged' => $paged
		    );
	    }

	    $query = new WP_Query( $args );

	    return tr_view('candidature/index', ['datas' => $query, 'data' => $data]);
    }

    /**
     * The show page for admin
     *
     * @param $id
     *
     * @return mixed
     */
    public function show()
    {
        // TODO: Implement show() method.

	    $offer = get_post($_GET['offerid']);


	    return tr_view('candidature/show', ['offer' => $offer]);
    }

	public function edit()
	{
		// TODO: Implement show() method.

		$offer = get_post($_GET['offerid']);


		return tr_view('candidature/showRecommandation', ['offer' => $offer]);
	}


}