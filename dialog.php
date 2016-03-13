<?php
require '../../../wp-admin/admin.php';

if (!is_admin())
	die("error");

$keyword = ($_POST["keyword"]) ? $_POST["keyword"] : "wordpress";

$url = sprintf("http://b.hatena.ne.jp/search/text?q=%s&mode=rss", urlencode($keyword));

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
			</form>
			<?php foreach ($feed->item as $item): ?>
				<div class="well">
					<h4><a href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->title; ?></a></h4>
					<p><?php echo $item->description; ?></p>
					<div class="text-right"><button class="btn btn-primary" data-url="<?php echo $item->link; ?>" onClick="insert(this)">挿入</button></div>
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
