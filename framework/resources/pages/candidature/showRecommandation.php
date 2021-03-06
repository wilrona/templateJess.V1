<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 04/04/2018
 * Time: 03:52
 */
?>

<h1 class="tr-admin-page-title">Recommandation(s) de l'annonce : <?= tr_posts_field('numero_annonce', $offer->ID); ?> - <?= $offer->post_title ?> <a href="<?= admin_url('admin.php?page=candidature_index') ?>" class="page-title-action">Retour à la liste</a></h1>


<ul class="subsubsub">
	<li class="all"><a href="" class="current">Total résultat <span class="count">(<?= count_recommandee($offer->ID); ?>)</span></a> |</li>
</ul>

<table class="wp-list-table widefat fixed striped pages">
	<thead>
	<tr>
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc ui-sortable">
			<a href="" class="ui-sortable-handle">
				<span>Nom du candidat</span><span class="sorting-indicator"></span>
			</a>
		</th>
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc ui-sortable">
			<a href="" class="ui-sortable-handle">
				<span>CV du candidat</span><span class="sorting-indicator"></span>
			</a>
		</th>
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc ui-sortable">
			<a href="" class="ui-sortable-handle">
				<span>Date de creation</span><span class="sorting-indicator"></span>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php

	$Data = count_recommandee($offer->ID, false);

	if($Data->have_posts()):
		while ( $Data->have_posts() ) : $Data->the_post()?>

			<tr id="post" class="iedit author-self level-0 post type-page status-publish hentry">
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Titre">
					<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>

					<strong><?= get_user_by('id', tr_posts_field('user_cv', get_the_ID()))->display_name ?></strong>

				</td>
				<td>
					<a href="<?= admin_url('post.php?post='.get_the_ID().'&action=edit') ?>" target="_blank"><strong><?= get_the_title(); ?></strong></a>
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
				<h1 style="text-align: center;">Aucune Candidature a recommander trouvée</h1>
			</td>
		</tr>

	<?php endif; ?>
	</tbody>

</table>