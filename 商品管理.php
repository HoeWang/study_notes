<?php 

	require './Model/Model.php';
	require './Model/Page.php';

	$goods = new Model('goods');

	$count = $goods->count();
	$page = new Page($count,10);
	$list = $goods->limit($page->limitPage)->select();

 ?>
 <!DOCTYPE html>
 <html lang="zh">
 <head>
 	<meta charset="UTF-8">
 	<title>Document</title>
 	<script src="./public/js/jquery-2.1.4/jquery.min.js"></script>
 	<script src="./public/bootstrap-3.3.7/js/bootstrap.min.js"></script>
 	<link rel="stylesheet" href="./public/bootstrap-3.3.7/css/bootstrap.min.css">
 	<style type="text/css">
 		.btns{
 			position:absolute;
 			left:40%;
 		}
 	</style>
 </head>
 <body>
 		<div class="container">
 			<h1><?=date('Y-m-d H:i:s',time())?></h1>
 			<table class="table table-hover zz-table">
 				<tr>
 					<td>
 						<button class="btn btn-default btn-xs zz-all">全选</button>
 						<button class="btn btn-danger btn-xs zz-del-all" style="display:none">删除所有</button>
 					</td>
 					<td>id</td>
 					<td>商品名</td>
 					<td>价格</td>
 					<td>库存</td>
 					<td>购买量</td>
 					<td>操作</td>

 				</tr>
 				<?php foreach($list as $key => $val):?>
 					<tr>
 						<td><input type="checkbox" ></td>
 						<td><?=$val['id'];?></td>
 						<td><?=$val['name'];?></td>
 						<td><?=$val['price'];?></td>
 						<td><?=$val['store'];?></td>
 						<td><?=$val['buy'];?></td>
 						<td gid="<?=$val['id']?>">
 							<button type="button" class="btn btn-primary btn-xs zz-edit">编辑</button>
 							<button type="button" class="btn btn-danger btn-xs zz-del" >删除</button>
 						</td>
 					</tr>
 				<?php endforeach;?>
 			</table>
 			<div class="btns">
 				<?=$page->show();?>
 			</div>
 		</div>
 </body>
 <script type="text/javascript">
 	
 	$('.zz-table tr td input[type="checkbox"]').click(function(){
 		console.log($('.zz-table tr td input[type="checkbox"]:checked').length);
 		if($('.zz-table tr td input[type="checkbox"]:checked').length > 0){
 			$('.zz-del-all').show(500);
 			
 		}else{
 			$('.zz-del-all').hide(500);
 			
 		}
 	});
 	//分页
 	$('.btns').children().wrap('<li></li>').parent().parent().wrapInner('<ul class="pagination"></ul>').attr('title','你是谁');

 	//全选
 	var a = 1;
 	$('.zz-all').click(function(){
 		if(a == 1){
 			$('.zz-table tr td input[type="checkbox"]').prop('checked',true);
 			a = -a;
 			$('.zz-all').html('取消全选');
			$('.zz-del-all').show(500);
 		}else{
 			$('.zz-table tr td input[type="checkbox"]').prop('checked',false);
 			a = -a;
 			$('.zz-all').html('全选');
 			$('.zz-del-all').hide(500);
 		}
 	});

 	//绑定ajax删除
 	$('.zz-table tr td .zz-del').click(function(){
 		var re = confirm('确定删除吗？');
 		if(re){
 			var gid = $(this).parent().attr('gid');
 			var that = $(this).parent().parent();
 			$.get('./action.php',{'a':'delete','gid':gid},function(data){
 				console.log(data);

 				if(data > 0){
 					that.remove();
 				}
 			},'text');
 		}
 	});

 	//删除所有选中
 	$('.zz-del-all').click(function(){

 		var re = confirm('确定删除所有吗？');
 		if(re){
 			var checkId = $('.zz-table tr td input[type="checkbox"]:checked').map(function(){
 				return $(this).parent().next().html();
 			}).get().join(',');

 			$.get('./action.php',{'a':'del_all','check_id':checkId},function(data){
 				console.log(data);
 				if(data > 0){
 					$('.zz-table tr td input[type="checkbox"]:checked').parent().parent().remove();
 				}
 			},'text');
 		}
 	});
 </script>
 </html>