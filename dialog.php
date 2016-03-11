<?php
require '../../../wp-admin/admin.php';

if (!is_admin())
	die("error");

$keyword = ($_POST["keyword"]) ? $_POST["keyword"] : "wordpress";

$url = sprintf("http://b.hatena.ne.jp/search/text?q=%s&mode=rss", $keyword);

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
		<script type="text/javascript" src="../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
		<script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery("body#hatebu button").click(function () {
                    elem = jQuery(this).parents(".well");
                    insert(elem);
                });

                function insert(elem) {
                    tinyMCEPopup.execCommand('mceInsertContent', false, $("h4", elem).text() + "<br /><br /><br />");
                    tinyMCEPopup.execCommand('mceInsertContent', false, $("button", elem).data("url"));

                    // Refocus in window
                    if (tinyMCEPopup.isWindow)
                        window.focus();

                    tinyMCEPopup.editor.focus();
                    tinyMCEPopup.close();
                }
            });
		</script>
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
					<div class="text-right"><button class="btn btn-primary" data-url="<?php echo $item->link; ?>">挿入</button></div>
				</div>
			<?php endforeach; ?>
		</div>
	</body>
</html>
