<div class="container">
	<div class="blog-header">
		<h1 class="blog-title">Actualités</h1>
		<p class="lead blog-description">Fil d'actualité blabla.</p>
	</div>

	<div class="row">

		<div class="col-sm-8 blog-main">
			<?php foreach ($posts as $post): ?>
				<div class="blog-post">
					<?php 
						if(isset($post['Post']['thumb'])) {
							echo $this->Image->resize($post['Post']['thumb'],500,200);
						} else {
							echo $this->Html->image('http://placehold.it/500x200');	
						}
					?>
					<h2 class="blog-post-title"><?= $post['Post']['title']; ?></h2>
					<p class="blog-post-meta"><?= $post['Post']['created']; ?>  by <a href="#">Mark</a></p>
					<?= $post['Post']['body']; ?>
				</div><!-- /.blog-post -->
			<?php endforeach; ?>



		</div><!-- /.blog-main -->

		<div class="col-sm-3 col-sm-offset-1 blog-sidebar">
			<div class="sidebar-module sidebar-module-inset">
				<h4>About</h4>
				<p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
			</div>
			<div class="sidebar-module">
				<h4>Archives</h4>
				<ol class="list-unstyled">
					<li><a href="#">January 2014</a></li>
				</ol>
			</div>
		</div><!-- /.blog-sidebar -->

	</div><!-- /.row -->

</div><!-- /.container -->