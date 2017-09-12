<?php exit;?>00153663049816fcfdbf712dd63a8846c044f22c5d92s:5525:"a:2:{s:8:"template";s:5461:"<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $media["title"];?> </title>
<meta name="keywords" content="<?php echo $media["keywords"];?>" />
<meta name="description" content="<?php echo $media["description"];?>" />
<link href="/themes/default/css/base.css" rel="stylesheet" type="text/css">
<link href="/themes/default/css/style.css" rel="stylesheet" type="text/css">
<script src="/public/js/jquery.js"></script>
<script src="/public/js/switch/jquery.SuperSlide.js"></script>
</head>
<body>
<?php $__Template->display("themes/default/head"); ?>
<div class="g-bd">
	<div class="g-mn">
		<div class="m-hds">
        
            <div id="m-sld" class="m-sld s-box">
                    <div class="hd">
                        <ul>
                        <?php $listList = service("dhcms","Label","contentList",array( "app"=>"DhCms", "label"=>"contentList", "sub"=>"true", "image"=>"true", "pos_id"=>"1", "order"=>"class_id desc,time asc", "dhname"=>"幻灯片的循环序号"));  if(is_array($listList)) foreach($listList as $list){ ?>
                        <li><?php echo $list["i"];?></li>
                        <?php } ?>
                        </ul>
                    </div>
                    <div class="bd">
                        <ul>
                        	<?php $listList = service("dhcms","Label","contentList",array( "app"=>"DhCms", "label"=>"contentList", "limit"=>"5", "order"=>"class_id desc", "dhname"=>"幻灯片的循环内容"));  if(is_array($listList)) foreach($listList as $list){ ?>
                            <li><a href="<?php echo $list["aurl"];?>" target="_blank"><img src="<?php echo cut_image($list["image"],270,270);?>" width="270" height="270"   /></a></li>
                            <?php } ?>
                        </ul>
                        
                    </div>
                </div>
                
			<div class="m-top-new s-box">
            	<?php $listList = service("dhcms","Label","contentList",array( "app"=>"DhCms", "label"=>"contentList", "sub"=>"true", "pos_id"=>"1", "limit"=>"1", "order"=>"class_id desc", "dhname"=>"幻灯片右侧文章第一条"));  if(is_array($listList)) foreach($listList as $list){ ?>
				<div class="m-hot">
					<div class="tt"><a href="<?php echo $list["aurl"];?>"><?php echo $list["title"];?></a> <span>推荐置顶</span></div>
					<div class="dsc"><?php echo $list["description"];?></div>
				</div>	
                <?php } ?>
				<div class="m-hot-lst">
					<ul>
                        <?php $listList = service("dhcms","Label","contentList",array( "app"=>"DhCms", "label"=>"contentList", "sub"=>"true", "pos_id"=>"1", "limit"=>"1,5", "order"=>"class_id desc", "dhname"=>"幻灯片右侧文章第二条"));  if(is_array($listList)) foreach($listList as $list){ ?>
						<li><a href="<?php echo $list["aurl"];?>"><?php echo $list["title"];?></a><span><?php echo date('Y-m-d',$list['time']);?></span></li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="f-cb"></div>
		<div class="u-sep10"></div>
		<div class="m-ad"><img src="/themes/default/images/ad-1.jpg" width="670" height="90"></div>
		<div class="g-md">
            <?php $classList = service("article","Label","categoryList",array( "app"=>"Article", "label"=>"categoryList", "type"=>"1", "limit"=>"4", "dhname"=>"中部分类文章调用"));  if(is_array($classList)) foreach($classList as $class){ ?>
			<div class="m-news">
				<div class="tt"><a href="<?php echo $class["curl"];?>"><?php echo $class["name"];?> </a><span><a href="<?php echo $class["curl"];?>">more</a></span></div>                
                <?php $listList = service("dhcms","Label","contentList",array( "app"=>"DhCms", "label"=>"contentList", "class_id"=>$categoryInfo['class_id'], "image"=>"false", "limit"=>"1", "order"=>"class_id desc", "dhname"=>"循环的第一篇带图片的文章"));  if(is_array($listList)) foreach($listList as $list){ ?>
				<div class="hot"><a href="<?php echo $list["aurl"];?>"><img src="<?php echo $list["image"];?>" width="70" height="48" alt="<?php echo $list["aurl"];?>"></a><a href="<?php echo $list["aurl"];?>"><?php echo $list["title"];?></a>
					<div class="f-cb"></div>
				</div>
				<?php } ?>
				<div class="lst">
					<ul>
                        <?php $listList = service("dhcms","Label","contentList",array( "app"=>"DhCms", "label"=>"contentList", "class_id"=>$class['class_id'], "limit"=>"5", "order"=>"class_id desc", "dhname"=>"循环的后五篇文章"));  if(is_array($listList)) foreach($listList as $list){ ?>
						<li><a href="<?php echo $list["aurl"];?>"><?php echo $list["title"];?></a><span><?php echo date('Y-m-d',$list['time']);?></span></li>
						<?php } ?>
					</ul>
				</div>
			</div>
            <?php } ?>
			<div class="f-cb"></div>
		</div>
	</div>
	<div class="g-sd">
		<div class="m-sdc">
			<div class="tt">模板说明</div>
			<div class="ct"><?php $echoList = service("dhcms","Label","fragment",array( "app"=>"DhCms", "label"=>"fragment", "dhname"=>"右侧模板说明"));  echo $echoList; ?></div>
		</div>
		<?php $__Template->display("themes/default/sidebar"); ?>
	</div>
	<div class="f-cb"></div>
</div>
<?php $__Template->display("themes/default/foot"); ?>
</body>
<script type="text/javascript">
	$(function(){
		$("#m-sld").slide({mainCell:".bd ul",autoPlay:true});
	});
</script>
</html>
";s:12:"compile_time";i:1505094498;}";