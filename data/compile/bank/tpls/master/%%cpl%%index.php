<?php $this->_compileInclude('header'); ?><body><?php $this->_compileInclude('nav'); ?><div class="container-fluid">	<div class="row-fluid">		<div class="main">			<div class="col-xs-2" style="padding-top:10px;margin-bottom:0px;">				<?php $this->_compileInclude('menu'); ?>			</div>			<div class="col-xs-10" id="datacontent">				<div class="box itembox" style="margin-bottom:0px;border-bottom:1px solid #CCCCCC;">					<div class="col-xs-12">						<ol class="breadcrumb">							<li><a href="index.php?<?php echo $this->tpl_var['_app']; ?>-master"><?php echo $this->tpl_var['apps'][$this->tpl_var['_app']]['appname']; ?></a></li>							<li class="active">首页</li>						</ol>					</div>				</div>				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">					<div class="col-xs-12">						<h5 class="title">							开发者信息						</h5>						<p>							QQ:418662027 官方站：<a href="http://mcykj.com">http://mcykj.com</a> 本版版本号：<?php echo PE_VERSION; ?>						</p>					</div>					<div class="col-xs-12">						<h5 class="title">							使用帮助						</h5>						<p>							帮助论坛：<a href="http://mcykj.com/bbs/">http://mcykj.com/bbs/</a>						</p>						<p>							微信公众号：						</p>					</div>				</div>			</div>		</div>	</div></div><?php $this->_compileInclude('footer'); ?></body></html>
