<?php $this->_compileInclude('header'); ?>
<body>
<?php $this->_compileInclude('nav'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="col-xs-2" style="padding-top:10px;margin-bottom:0px;">
				<?php $this->_compileInclude('menu'); ?>
			</div>
			<div class="col-xs-10" id="datacontent">
				<div class="box itembox" style="margin-bottom:0px;border-bottom:1px solid #CCCCCC;">
						<div class="col-xs-12">
							<ol class="breadcrumb">
								<li><a href="index.php?<?php echo $this->tpl_var['_app']; ?>-master"><?php echo $this->tpl_var['apps'][$this->tpl_var['_app']]['appname']; ?></a></li>
								<li><a href="index.php?user-master-module">模型管理</a></li>
								<li class="active">添加模型</li>
							</ol>
						</div>
					</div>
					<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
						<h4 class="title" style="padding:10px;">
							添加模型
							<a class="pull-right btn btn-primary" href="index.php?user-master-module">模型列表</a>
						</h4>
						<form action="index.php?user-master-module-add" method="post" class="form-horizontal">
							<fieldset>
								<div class="form-group">
								</div>
								<div class="form-group">
									<label for="modulename" class="control-label col-sm-2">模型名</label>
									<div class="col-sm-4">
										<input class="form-control" id="modulename" name="args[modulename]" type="text" value="" needle="needle" min="2" max="40" alt="请输入模型名称" msg="模型名称为不超过40字的中英文字符且不能为空"/>
										<span class="help-block">请输入模型名称</span>
									</div>
								</div>
								<div class="form-group">
									<label for="modulecode" class="control-label col-sm-2">模型代码</label>
									<div class="col-sm-4">
										<input class="form-control" id="modulecode" name="args[modulecode]" type="text" value="" needle="needle" datatype="english" max="12" alt="请输入模型代码" msg="模型代码为不超过12字的英文字符"/>
										<span class="help-block">请输入12字以内的英文模型代码</span>
									</div>
								</div>
								<div class="form-group">
									<label for="moduledescribe" class="control-label col-sm-2">模型描述</label>
									<div class="col-sm-9">
										<textarea class="form-control" rows="7" id="moduledescribe" name="args[moduledescribe]"></textarea>
										<span class="help-block">对这个模型进行描述</span>
									</div>
								</div>
								<div class="form-group">
									<label for="moduledescribe" class="control-label col-sm-2"></label>
									<div class="col-sm-9">
										<button class="btn btn-primary" type="submit">提交</button>
										<input type="hidden" name="insertmodule" value="1"/>
										<input type="hidden" name="page" value="<?php echo $this->tpl_var['page']; ?>"/>
										<?php $aid = 0;
 foreach($this->tpl_var['search'] as $key => $arg){ 
 $aid++; ?>
										<input type="hidden" name="search[<?php echo $key; ?>]" value="<?php echo $arg; ?>"/>
										<?php } ?>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->_compileInclude('footer'); ?>
</body>
</html>