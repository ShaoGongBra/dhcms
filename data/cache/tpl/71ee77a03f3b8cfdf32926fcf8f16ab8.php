<?php exit;?>001536628243c11533a52fe1c2799266df07911f1f48s:1139:"a:2:{s:8:"template";s:1075:"<div class="g-hd">
	<div class="m-logo"></div>
	<div class="m-sch">
		<!---->
		<form action="<?php echo url('Article/Search/index');?>" method="post">
		<div class="select">
			<select name="model">
				<option value="0">标题</option>
				<option value="1">全文</option>
			</select>
		</div>
		<input name="keyword" type="text" maxlength="20">
		<button>搜索</button>
		</form>
	</div>
</div>
<div class="m-nav">
	<ul>
		<li><a <?php if (!$topCategoryInfo['class_id']){ ?> class="u-crt" <?php } ?>  href="/">网站首页</a></li>
        <?php $listList = service("dhcms","Label","categoryList",array( "app"=>"DhCms", "label"=>"categoryList", "limit"=>5, "parent_id"=>0));  if(is_array($listList)) foreach($listList as $list){ ?>
           <li> <a href="<?php echo $list["curl"];?>" <?php if ($list['class_id']==$topCategoryInfo['class_id']){ ?> class="u-crt" <?php } ?>  ><?php echo $list["name"];?></a></li>
        <?php } ?>
		<li><a href='<?php echo url("DhCms/Form/index",array("name"=>'guestbook'));?>'>留言板</a></li>
	</ul>
</div>";s:12:"compile_time";i:1505092243;}";