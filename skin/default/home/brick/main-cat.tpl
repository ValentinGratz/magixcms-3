{if #id_mainCat#}<section id="main-cat" class="section-block">	<div class="container">		<div class="row row-center">			{if $mainCat}				{include file="home/loop/category.tpl" data=$mainCat classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4'}			{/if}		</div>	</div></section>{/if}