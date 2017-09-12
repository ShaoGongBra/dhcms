<?php exit;?>00153662824351ca66b4dd9df83eb43cb1df32e3d04as:1503:"a:2:{s:8:"template";s:1439:"<div class="u-sep10"></div>
<div class="m-sdc">
	<div class="tt">最新图文</div>
	<div class="pl">
		<ul>
        	<?php $listList = service("dhcms","Label","contentList",array( "app"=>"DhCms", "label"=>"contentList", "image"=>"true", "limit"=>"5"));  if(is_array($listList)) foreach($listList as $list){ ?>
			<li>
				<div class="pic"><img src="<?php echo $list["image"];?>" width="65" height="43"></div>
				<div class="info"><a href="<?php echo $list["aurl"];?>"><?php echo $list["title"];?></a></div>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
<div class="u-sep10"></div>
<div class="m-sdc">
	<div class="tt">最新TAG</div>
	<div class="tag"> 
    	<?php $listList = service("dhcms","Label","tagsList",array( "app"=>"DhCms", "label"=>"tagsList", "limit"=>"5"));  if(is_array($listList)) foreach($listList as $list){ ?>
		<a href="<?php echo $list["url"];?>"><?php echo $list["name"];?></a> 
        <?php } ?>
	</div>
</div>
<div class="u-sep10"></div>
<div class="m-sdc">
	<div class="tt">友情链接</div>
	<div class="link">
		<ul>
        	<?php $listList = service("dhcms","Label","formList",array( "app"=>"DhCms", "label"=>"formList", "table"=>"link", "limit"=>"5"));  if(is_array($listList)) foreach($listList as $list){ ?>
			<li><a href="<?php echo $list["url"];?>" target="_blank"><?php echo $list["name"];?></a></li>
			<?php } ?>
		</ul>
	</div>
	<div class="f-cb"></div>
</div>
";s:12:"compile_time";i:1505092243;}";