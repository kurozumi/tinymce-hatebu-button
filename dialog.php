<?php
require '../../../wp-admin/admin.php';

if (!is_admin())
	die("error");

$keyword = "wordpress";
$users = 3;

extract($_POST);

$url = sprintf("http://b.hatena.ne.jp/search/text?q=%s&users=%s&mode=rss", urlencode($keyword), $users);

$options = array(
	"http" => array(
		"method" => "GET",
		"header" => "User-Agent: wp"
	)
);

$context = stream_context_create($options);

$feed = file_get_contents($url, false, $context);

$feed = simplexml_load_string($feed);
?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	</head>
	<body id="hatebu">
		<div class="container-fluid" style="padding:15px;">
			<form class="form-inline" method="post" action="dialog.php">
				<div class="form-group">
					<input class="form-control" type="text" name="keyword" value="<?php echo $keyword; ?>" />				
				</div>
				<div class="form-group">
					<select class="form-control" name="users">
						<?php foreach(array(1,3,50,100,500,1000) as $value):?>
						<?php if($users == $value):?>
						<option valeu="<?php echo $value;?>" selected><?php echo $value;?></option>
						<?php else:?>
						<option valeu="<?php echo $value;?>"><?php echo $value;?></option>
						<?php endif;?>
						<?php endforeach;?>
					</select>
				</div>
			</form>
			<?php
				foreach ($feed->item as $item): 
					$dc = $item->children('http://purl.org/dc/elements/1.1/');
					$content = $item->children('http://purl.org/rss/1.0/modules/content/');
					$hatena = $item->children('http://www.hatena.ne.jp/info/xmlns#');
				?>
				<div class="well">
					<h4><a href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->title; ?></a></h4>
					<p><?php echo strip_tags($item->description); ?></p>
					<p><?php echo date("Y-m-d H:i:s", strtotime($dc->date));?></p>
					<div class="clearfix">
						<p class="pull-left"><button class="btn btn-primary" type="button">Hatena Bookmark <span class="badge"><?php echo $hatena->bookmarkcount;?></span></button></p>
						<div class="pull-right"><button class="btn btn-success" data-url="<?php echo $item->link; ?>" onClick="insert(this)">挿入</button></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<script type="text/javascript">
            function insert(button) {
				var args = top.tinymce.activeEditor.windowManager.getParams();
				var $ = args['jquery'];

				var elem = $(button).parents(".well");
				
				var text = $("h4", elem).text();
				var url = $("button", elem).data("url");
				top.tinymce.activeEditor.execCommand('mceInsertContent', false,  '<a href="' + url + '">' + text + '</a>');
            }
		</script>
	</body>
</html>
