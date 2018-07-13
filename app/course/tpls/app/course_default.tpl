{x2;if:!$userhash}
{x2;include:header}
<!-- <script type="text/javascript" src="app/core/styles/js/jquery.media.js"></script>   -->
<!-- <link href="https://cdn.bootcss.com/viewer.js/0.11.1/crocodoc.viewer.css" rel="stylesheet">
<script src="https://cdn.bootcss.com/viewer.js/0.11.1/crocodoc.viewer.js"></script>
<link href="https://cdn.bootcss.com/viewer.js/0.11.1/crocodoc.viewer.min.css" rel="stylesheet">
<script src="https://cdn.bootcss.com/viewer.js/0.11.1/crocodoc.viewer.min.js"></script>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> -->
<style>
/*.nums{margin-left: 2px;}*/
video::-internal-media-controls-download-button {
    display:none;
}

video::-webkit-media-controls-enclosure {
    overflow:hidden;
}

video::-webkit-media-controls-panel {
    width: calc(100% + 30px); 
}

#buttons{display: none;}
</style>
<body>
{x2;include:nav}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="box itembox" style="margin-bottom:0px;">
				<div class="col-xs-12">
					<ol class="breadcrumb">
						<li><a href="index.php">首页</a></li>
						<li><a href="index.php?course">课程</a></li>
						{x2;tree:$catbread,cb,cbid}
						<li><a href="index.php?course-app-category&catid={x2;v:cb['catid']}">{x2;v:cb['catname']}</a></li>
						{x2;endtree}
						<li class="active">{x2;$cat['catname']}</li>
					</ol>
				</div>
			</div>
		</div>
		<div class="main" id="datacontent">
{x2;endif}
			<div class="col-xs-7" style="padding-left:0px;position:relative;">
				<div class="box itembox" style="height:620px;width:680px;top:0px;" data-spy="affix" data-offset-top="245">
				{x2;if:$content['course_files']}
					<video style="margin-top:20px;" controls="true" width="100%" height="450">
						<source src="/{x2;$content['course_files']}" type='video/mp4' />
					</video>
				{x2;endif}

				{x2;if:$content['text_files']}
					<div class="media" style="background-color: rgb(255, 255, 255); width: 100%;height: 620px;">
						<iframe  id="iframeChild" isContentEditable ="true" style="height:100%;width:100%;top:0px;" src="{x2;$content[text_files]}"></iframe>
					</div>
				{x2;endif}

				{x2;if:$content['jpg_images']}
					<img src="{x2;$content['jpg_images']}" alt="">
				{x2;endif}
				</div>
			</div>
			<div class="col-xs-5 pull-right" style="padding-right:0px;">
				<div class="box itembox" style="padding-top:20px;">
					<h4 class="title">{x2;$course['cstitle']}</h4>
					{x2;realhtml:$course['csdescribe']}
				</div>
				{x2;tree:$contents['data'],content,cid}
				<div class="box itembox" style="padding-top:20px;">
					<div class="col-xs-6">
						<a target="datacontent" href="index.php?course-app-course&page={x2;$page}&csid={x2;$course['csid']}&contentid={x2;v:content['courseid']}" class="thumbnail pull-left ajax">
							<img src="{x2;v:content['coursethumb']}" alt="" width="100%">
						</a>
					</div>
					<div class="col-xs-6">
					<!-- {x2;v:content['coursemoduleid']} -->
						<a target="datacontent" href="index.php?course-app-course&page={x2;$page}&csid={x2;$course['csid']}&contentid={x2;v:content['courseid']}" class="ajax"><h4 class="title">{x2;v:content['coursetitle']}</h4></a>
						<p>{x2;realhtml:v:content['coursedescribe']}</p>
						<p>
							<span class="pull-right">
								{x2;if:$content['courseid'] == v:content['courseid']}
								{x2;if:$content['course_files']}
									<a href="javascript:;" style="color:green;"><em class="glyphicon glyphicon-play"></em> 播放中</a>
									<a href="/{x2;$content['course_files']}" download="{x2;$content['coursetitle']}">下载</a>
								{x2;endif}
								{x2;if:$content['text_files']}
									<a href="/{x2;$content[text_files]}" download="{x2;$content['coursetitle']}">下载</a>
								{x2;endif}
								{x2;if:$content['jpg_images']}
									<a href="/{x2;$content[jpg_images]}" download="{x2;$content['coursetitle']}">下载</a>
								{x2;endif}
								
								<a href="javascript:;" class="clicks_{x2;v:content['courseid']} iss" ids="{x2;v:content['courseid']}" type="1" >点赞: <span class="nums">{x2;$content[click]}</span></a>
								<a href="javascript:;" style="display: none;" class="clicks_{x2;v:content['courseid']} nos" ids="{x2;v:content['courseid']}" type="0" >取消<span class="nums">{x2;$content[click]}</span></a>

								<script>
									$(function(){ 
								        $(".clicks_{x2;v:content['courseid']}").click(function(){ 
								        	var id = $(this).attr('ids');
								        	var type = $(this).attr('type');
								            $.ajax({
								                url:"index.php?course-app-course-click",
								                type:'post',
								                // data:{'name名':val值}
								                data:{'id':id,'type':type},
								                dataType:'json',
								                success:function(data){
								                  if(data){
								                    alert(data.message);
								                    if(data.statusCode == '200'){
								                    	if(data.type == '1'){
										                    $(".iss").hide();
										                    $(".nos").show();
										                }else{
										                	$(".nos").hide();
										                    $(".iss").show();
										                }
								                    	$(".nums").text(data.click);
								                    }
								                    
								                  }else{
								                    alert('数据提交失败');
								                  }
								                }
								            })
								        }); 
								    }); 
								</script>
								{x2;endif}


							</span>
						</p>
					</div>
				</div>
				{x2;endtree}
				<ul class="pagination pagination-right">{x2;$contents['pages']}</ul>
			</div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
<script language="javascript">
window.onload=function(){
document.getElementById('iframeChild').contentWindow.document.getElementById('buttons').style.display="none";
}
</script>

{x2;include:footer}
</body>
</html>
{x2;endif}