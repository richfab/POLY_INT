<div style="margin-top:30px;">
	<?php foreach ($articles as $article): ?>
		<div class="well">
	        <div class="row">
	            <div class="col-sm-12">
	            		<?= $article['Post']['title']; ?>
	                <p class="body-text">
						<?= $article['Post']['body']; ?>
					</p>                
					<p>
						<small><?= $article['Post']['created']; ?> 
						par <a href="/POLY_INT/users/profile/202">Roman Borisov</a></small></p>
	            </div>
	        </div>
	    </div>
	<?php endforeach; ?>
</div>