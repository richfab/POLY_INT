<div class="container">
	<div class="blog-header">
		<h1 class="blog-title">Actualités</h1>
		<p class="lead blog-description">Fil d'actualité blabla.</p>
	</div>

	<div class="row">

		<div class="col-sm-8 blog-main">
			<?php foreach ($articles as $article): ?>
				<div class="blog-post">
					<h2 class="blog-post-title"><?= $article['Post']['title']; ?></h2>
					<p class="blog-post-meta"><?= $article['Post']['created']; ?>  by <a href="#">Mark</a></p>
					<?= $article['Post']['body']; ?>
				</div><!-- /.blog-post -->
			<?php endforeach; ?>


			<ul class="pager">
				<li>

<?php echo $this->Paginator->prev(
  ' << ' . __('previous'),
  array(),
  null,
  array('class' => 'prev disabled')
);?>
</li>
				<li><?php echo $this->Paginator->next(
  ' >> ' . __('next'),
  array(),
  null,
  array('class' => 'next disabled')
);?></li>
			</ul>

		</div><!-- /.blog-main -->

		<div class="col-sm-3 col-sm-offset-1 blog-sidebar">
			<div class="sidebar-module sidebar-module-inset">
				<h4>Blog</h4>
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