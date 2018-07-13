<?php $this->_compileInclude('header'); ?>
<body>
<?php $this->_compileInclude('nav'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="box itembox" style="margin-bottom:0px;">
				<div class="col-xs-12">
					<ol class="breadcrumb">
					  <li><a href="index.php">首页</a></li>
					  <li><a href="index.php?exam-app">考试</a></li>
					  <li><a href="index.php?exam-app-basics"><?php echo $this->tpl_var['data']['currentbasic']['basic']; ?></a></li>
					  <li><a href="index.php?exam-app-exercise">强化训练</a></li>
					  <li class="active">成绩单</li>
					</ol>
				</div>
			</div>
			<div class="box itembox">
				<legend class="text-center"><h3><?php echo $this->tpl_var['sessionvars']['examsession']; ?></h3></legend>
				<?php if($this->tpl_var['data']['currentbasic']['basicexam']['notviewscore']){ ?>
				<div class="col-xs-12 alert alert-info">
					<p>您已经成功交卷，请等待教师统计并公布分数。</p>
				</div>
				<?php } else { ?>
            	<div class="col-xs-12">
            		<div><b class="text-info">考试详情：</b>答卷耗时：<b class="text-warning"><?php if($this->tpl_var['sessionvars']['examsessiontime'] >= 60){ ?><?php if($this->tpl_var['sessionvars']['examsessiontime']%60){ ?><?php echo intval($this->tpl_var['sessionvars']['examsessiontime']/60)+1; ?><?php } else { ?><?php echo intval($this->tpl_var['sessionvars']['examsessiontime']/60); ?><?php } ?>分钟<?php } else { ?><?php echo $this->tpl_var['sessionvars']['examsessiontime']; ?>秒<?php } ?></b></div>
              		<table class="table table-hover table-bordered">
                      <tr class="success">
                        <th style="text-align :center">题型</th>
                        <th style="text-align :center">总题数</th>
                        <th style="text-align :center">答对题数</th>
                      </tr>
                      <?php $nid = 0;
 foreach($this->tpl_var['number'] as $key => $num){ 
 $nid++; ?>
                      <?php if($num){ ?>
                      <tr>
                        <td style="text-align :center"><?php echo $this->tpl_var['questype'][$key]['questype']; ?></td>
                        <td style="text-align :center"><?php echo $num; ?></td>
                        <td style="text-align :center"><?php echo $this->tpl_var['right'][$key]; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } ?>
                      <tr>
                        <td colspan="5" align="left">本次考试共<b class="text-warning"><?php echo $this->tpl_var['allnumber']; ?></b>道题，您做对<b class="text-warning"><?php echo $this->tpl_var['allright']; ?></b>道题</td>
                      </tr>
                   </table>
                   <?php if($this->tpl_var['data']['currentbasic']['basicexam']['model'] != 2){ ?>
                   <div class="text-center"><a href="index.php?exam-app-history-view&ehid=<?php echo $this->tpl_var['ehid']; ?>" class="btn btn-info">查看答案和解析</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?exam-app-history" class="btn btn-info">进入我的考试记录</a></div>
            	   <?php } ?>
            	</div>
            	<?php } ?>
            </div>
		</div>
	</div>
</div>
<?php $this->_compileInclude('footer'); ?>
</body>
</html>