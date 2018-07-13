<?php $this->_compileInclude('header'); ?>
<body>
<?php $this->_compileInclude('nav'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main box itembox" >
			<h4 class="title">课程分类</h4>
			<div class="box itembox" >
				<?php $cid = 0;
 foreach($this->tpl_var['coursecats'] as $key => $cat){ 
 $cid++; ?>
				<h4 class="title" style="text-align: center;"><?php echo $cat['catname']; ?> : </h4>
				<?php if($this->tpl_var['topcourse'][$cat['catid']]){ ?>
				<ul class="list-unstyled list-inline" style="min-height: 20px;">
					<?php $crid = 0;
 foreach($this->tpl_var['topcourse'][$cat['catid']]['data'] as $key => $course){ 
 $crid++; ?>
					<li  style="margin-left: 16px;">
					<a href="index.php?course-app-course&csid=<?php echo $course['csid']; ?>" class="thumbnail"  style="border: 0px; ">
						<?php if($course['csthumb'] !== ''){ ?>
							<img src="<?php echo $course['csthumb']; ?>" alt="" width="160" style="height: 130px;">
						<?php } else { ?>
							<img src="app/core/styles/images/noimage.gif" alt="" width="160" style="height: 130px;">
						<?php } ?>
					</a>
					<a href="index.php?course-app-course&csid=<?php echo $course['csid']; ?>"  style="text-align: center;display: inherit; width: 100%;"><?php echo $course['cstitle']; ?></a></li>
					<?php } ?>
				</ul>
				<?php } ?>
				<?php } ?>
			</div>
			<div class="col-xs-10 box split" style="padding:0px;width:75%;">
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators" style="left:90%;bottom:0px;">
						<?php $cid = 0;
 foreach($this->tpl_var['contents'][2]['data'] as $key => $content){ 
 $cid++; ?>
						<li data-target="#carousel-example-generic" data-slide-to="<?php echo $key; ?>"<?php if($cid == 1){ ?> class="active"<?php } ?>></li>
						<?php } ?>
					</ol>
					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<?php $cid = 0;
 foreach($this->tpl_var['contents'][2]['data'] as $key => $content){ 
 $cid++; ?>
						<div class="item<?php if($cid == 1){ ?> active<?php } ?>">
							<a href="index.php?content-app-content&contentid=<?php echo $content['contentid']; ?>">
								<img src="<?php echo $content['contentthumb']; ?>" alt="" style="width:100%;height: 130px;" >
							</a>
							<div class="carousel-caption">
								<!--<?php echo $content['contenttitle']; ?>-->
							</div>
						</div>
						<?php } ?>
					</div>
					<!-- Controls
					<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
					</a>
					-->
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->_compileInclude('footer'); ?>

<style>
.title::after{height: 0px;background: #ffffff none repeat scroll 0 0;}
</style>
</body>
</html>