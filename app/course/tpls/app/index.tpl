{x2;include:header}
<body>
{x2;include:nav}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main box itembox" >
			<h4 class="title">课程分类</h4>
			<div class="box itembox" >
				{x2;tree:$coursecats,cat,cid}
				<h4 class="title" style="text-align: center;">{x2;v:cat['catname']} : </h4>
				{x2;if:$topcourse[v:cat['catid']]}
				<ul class="list-unstyled list-inline" style="min-height: 20px;">
					{x2;tree:$topcourse[v:cat['catid']]['data'],course,crid}
					<li  style="margin-left: 16px;">
					<a href="index.php?course-app-course&csid={x2;v:course['csid']}" class="thumbnail"  style="border: 0px; ">
						{x2;if:v:course['csthumb'] !== ''}
							<img src="{x2;v:course['csthumb']}" alt="" width="160" style="height: 130px;">
						{x2;else}
							<img src="app/core/styles/images/noimage.gif" alt="" width="160" style="height: 130px;">
						{x2;endif}
					</a>
					<a href="index.php?course-app-course&csid={x2;v:course['csid']}"  style="text-align: center;display: inherit; width: 100%;">{x2;v:course['cstitle']}</a></li>
					{x2;endtree}
				</ul>
				{x2;endif}
				{x2;endtree}
			</div>
			<div class="col-xs-10 box split" style="padding:0px;width:75%;">
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators" style="left:90%;bottom:0px;">
						{x2;tree:$contents[2]['data'],content,cid}
						<li data-target="#carousel-example-generic" data-slide-to="{x2;v:key}"{x2;if:v:cid == 1} class="active"{x2;endif}></li>
						{x2;endtree}
					</ol>
					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						{x2;tree:$contents[2]['data'],content,cid}
						<div class="item{x2;if:v:cid == 1} active{x2;endif}">
							<a href="index.php?content-app-content&contentid={x2;v:content['contentid']}">
								<img src="{x2;v:content['contentthumb']}" alt="" style="width:100%;height: 130px;" >
							</a>
							<div class="carousel-caption">
								<!--{x2;v:content['contenttitle']}-->
							</div>
						</div>
						{x2;endtree}
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


{x2;include:footer}

<style>
.title::after{height: 0px;background: #ffffff none repeat scroll 0 0;}
</style>
</body>
</html>