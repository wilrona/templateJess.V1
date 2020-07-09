<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 02/04/2018
 * Time: 01:34
 */
?>

<ul class="subsubsub">
	<li class="all"><a href="" class="current">Total résultat <span class="count">(<?= $datas->post_count; ?>)</span></a> |</li>
</ul>

<div class="tablenav top">
	<form method="get" action="">
		<input type="hidden" name="page" value="cv_index">
		<input type="hidden" name="action" value="search">
		<div class="alignleft actions ">
			<label for="bulk-action-selector-top" class="screen-reader-text">Sélectionnez l’action groupée</label>
			<input type="text" name="application_name" placeholder="Nom" value="<?php echo $data['application_name']; ?>">
		</div>

		<div class="alignleft actions">
			<button type="submit" class="button">Filter</button>
		</div>
	</form>

</div>

<table class="wp-list-table widefat fixed striped pages">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all-1">Tout sélectionner</label>
			<input id="cb-select-all-1" type="checkbox">
		</td>
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc ui-sortable">
			<a href="" class="ui-sortable-handle">
				<span>Titre de l'annonce</span><span class="sorting-indicator"></span>
			</a>
		</th>
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc ui-sortable">
			<a href="" class="ui-sortable-handle">
				<span>Consulter les candidatures</span><span class="sorting-indicator"></span>
			</a>
		</th>
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc ui-sortable">
			<a href="" class="ui-sortable-handle">
				<span>Candidatures recommandées</span><span class="sorting-indicator"></span>
			</a>
		</th>
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc ui-sortable">
			<a href="" class="ui-sortable-handle">
				<span>Date de publication</span><span class="sorting-indicator"></span>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if($datas->have_posts()):
		while ( $datas->have_posts() ) : $datas->the_post(); ?>

			<tr id="post-<?php the_ID() ?>" class="iedit author-self level-0 post-<?php the_ID() ?> type-page status-publish hentry">
				<th scope="row" class="check-column">
					<input id="cb-select-<?php the_ID() ?>" type="checkbox" name="post[]" value="<?php the_ID() ?>">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Titre">
					<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
					<strong><a class="row-title" target="_blank" href="/wp-admin/post.php?post=<?= get_the_ID(); ?>&action=edit"><?= get_the_title(); ?></a></strong>

				</td>
				<td>
					<a href="<?= admin_url('admin.php?page=candidature_show&offerid='.get_the_ID()) ?>"><strong><?= count(tr_posts_field('candidatureEmploi', get_the_ID())); ?></strong> candidature(s)</a>
				</td>
				<td>

                    <a href="<?= admin_url('admin.php?page=candidature_edit&offerid='.get_the_ID()) ?>"><strong><?= count_recommandee(get_the_ID()); ?></strong> recommandation(s)</a>



				</td>
				<td>
					<?php
					$date = get_the_date('Y-m-d H:i:s');
					if(!ctype_digit($date))
						$date = strtotime($date);
					if(date('Ymd', $date) == date('Ymd')){
						$diff = time() - $date;
						if($diff < 60) /* moins de 60 secondes */
							echo 'Il y a quelque instant';
						else if($diff < 3600) /* moins d'une heure */
							echo 'Il y a '.round($diff/60, 0).' min';
						else if($diff < 10800) /* moins de 3 heures */
							echo 'Il y a '.round($diff/3600, 0).' heures';
						else /*  plus de 3 heures ont affiche ajourd'hui à HH:MM:SS */
							echo 'Aujourd\'hui à '.date('H:i:s', $date);
					}
					else if(date('Ymd', $date) == date('Ymd', strtotime('- 1 DAY')))
						echo 'Hier à '.date('H:i:s', $date);
					else if(date('Ymd', $date) == date('Ymd', strtotime('- 2 DAY')))
						echo 'Il y a 2 jours à '.date('H:i:s', $date);
					else
						echo 'Le '.date('d/m/Y à H:i:s', $date);
					?>
				</td>
			<tr>
		<?php endwhile;?>

	<?php else: ?>
		<tr>
			<td colspan="6">
				<h1 style="text-align: center;">Aucune Annonce trouvée</h1>
			</td>
		</tr>

	<?php endif; ?>
	</tbody>

</table>
<?php
$paged = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;

$page_links = paginate_links( array(
	'base' => add_query_arg( 'paged', '%#%' ),
	'format' => '',
	'prev_text' => __( '&laquo;', 'text-domain' ),
	'next_text' => __( '&raquo;', 'text-domain' ),
	'total' => $datas->max_num_pages,
	'current' => $paged
) );

if ( $page_links ) {
	echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}

?>
