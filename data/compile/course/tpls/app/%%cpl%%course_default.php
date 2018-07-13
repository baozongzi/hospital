<?php if(!$this->tpl_var['userhash']){ ?>
<?php $this->_compileInclude('header'); ?>
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
<?php $this->_compileInclude('nav'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="box itembox" style="margin-bottom:0px;">
				<div class="col-xs-12">
					<ol class="breadcrumb">
						<li><a href="index.php">首页</a></li>
						<li><a href="index.php?course">课程</a></li>
						<?php $cbid = 0;
 foreach($this->tpl_var['catbread'] as $key => $cb){ 
 $cbid++; ?>
						<li><a href="index.php?course-app-category&catid=<?php echo $cb['catid']; ?>"><?php echo $cb['catname']; ?></a></li>
						<?php } ?>
						<li class="active"><?php echo $this->tpl_var['cat']['catname']; ?></li>
					</ol>
				</div>
			</div>
		</div>
		<div class="main" id="datacontent">
<?php } ?>
			<div class="col-xs-7" style="padding-left:0px;position:relative;">
				<div class="box itembox" style="height:620px;width:680px;top:0px;" data-spy="affix" data-offset-top="245">
				<?php if($this->tpl_var['content']['course_files']){ ?>
					<video style="margin-top:20px;" controls="true" width="100%" height="450">
						<source src="/<?php echo $this->tpl_var['content']['course_files']; ?>" type='video/mp4' />
					</video>
				<?php } ?>

				<?php if($this->tpl_var['content']['text_files']){ ?>
					<div class="media" style="background-color: rgb(255, 255, 255); width: 100%;height: 620px;">
						<iframe  id="iframeChild" isContentEditable ="true" style="height:100%;width:100%;top:0px;" src="<?php echo $this->tpl_var['content'][text_files]; ?>"></iframe>
					</div>
				<?php } ?>

				<?php if($this->tpl_var['content']['jpg_images']){ ?>
					<img src="<?php echo $this->tpl_var['content']['jpg_images']; ?>" alt="">
				<?php } ?>
				</div>
			</div>
			<div class="col-xs-5 pull-right" style="padding-right:0px;">
				<div class="box itembox" style="padding-top:20px;">
					<h4 class="title"><?php echo $this->tpl_var['course']['cstitle']; ?></h4>
					<?php echo html_entity_decode($this->ev->stripSlashes($this->tpl_var['course']['csdescribe'])); ?>
				</div>
				<?php $cid = 0;
 foreach($this->tpl_var['contents']['data'] as $key => $content){ 
 $cid++; ?>
				<div class="box itembox" style="padding-top:20px;">
					<div class="col-xs-6">
						<a target="datacontent" href="index.php?course-app-course&page=<?php echo $this->tpl_var['page']; ?>&csid=<?php echo $this->tpl_var['course']['csid']; ?>&contentid=<?php echo $content['courseid']; ?>" class="thumbnail pull-left ajax">
							<img src="<?php echo $content['coursethumb']; ?>" alt="" width="100%">
						</a>
					</div>
					<div class="col-xs-6">
					<!-- <?php echo $content['coursemoduleid']; ?> -->
						<a target="datacontent" href="index.php?course-app-course&page=<?php echo $this->tpl_var['page']; ?>&csid=<?php echo $this->tpl_var['course']['csid']; ?>&contentid=<?php echo $content['courseid']; ?>" class="ajax"><h4 class="title"><?php echo $content['coursetitle']; ?></h4></a>
						<p><?php echo html_entity_decode($this->ev->stripSlashes($content['coursedescribe'])); ?></p>
						<p>
							<span class="pull-right">
								<?php if($this->tpl_var['content']['courseid'] == $content['courseid']){ ?>
								<?php if($this->tpl_var['content']['course_files']){ ?>
									<a href="javascript:;" style="color:green;"><em class="glyphicon glyphicon-play"></em> 播放中</a>
									<a href="/<?php echo $this->tpl_var['content']['course_files']; ?>" download="<?php echo $this->tpl_var['content']['coursetitle']; ?>">下载</a>
								<?php } ?>
								<?php if($this->tpl_var['content']['text_files']){ ?>
									<a href="/<?php echo $this->tpl_var['content'][text_files]; ?>" download="<?php echo $this->tpl_var['content']['coursetitle']; ?>">下载</a>
								<?php } ?>
								<?php if($this->tpl_var['content']['jpg_images']){ ?>
									<a href="/<?php echo $this->tpl_var['content'][jpg_images]; ?>" download="<?php echo $this->tpl_var['content']['coursetitle']; ?>">下载</a>
								<?php } ?>
								
								<a href="javascript:;" class="clicks_<?php echo $content['courseid']; ?> iss" ids="<?php echo $content['courseid']; ?>" type="1" >点赞: <span class="nums"><?php echo $this->tpl_var['content'][click]; ?></span></a>
								<a href="javascript:;" style="display: none;" class="clicks_<?php echo $content['courseid']; ?> nos" ids="<?php echo $content['courseid']; ?>" type="0" >取消<span class="nums"><?php echo $this->tpl_var['content'][click]; ?></span></a>

								<script>
									$(function(){ 
								        $(".clicks_<?php echo $content['courseid']; ?>").click(function(){ 
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
								<?php } ?>


							</span>
						</p>
					</div>
				</div>
				<?php } ?>
				<ul class="pagination pagination-right"><?php echo $this->tpl_var['contents']['pages']; ?></ul>
			</div>
<?php if(!$this->tpl_var['userhash']){ ?>
		</div>
	</div>
</div>
<script language="javascript">
window.onload=function(){
document.getElementById('iframeChild').contentWindow.document.getElementById('buttons').style.display="none";
}
</script>

<?php $this->_compileInclude('footer'); ?>
</body>
</html>
<?php } ?>