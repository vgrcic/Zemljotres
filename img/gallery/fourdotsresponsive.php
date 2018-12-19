<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		
		.gallery {
			
		}

		.image-holder {
			float: left;
		}
		
			.image-holder img {
				max-height: 100vh;
				height: 100%;
				width: 100%;
			}
		
		.thumbs {
			clear: right;
			max-height: 100vh;
			width: 170px;
			padding: 0px 10px;
			overflow-y: scroll;
		}

			.thumbs img {
				display: block;
				width: 150px;
			}

	</style>
</head>
<body>

	<div class="gallery">
		
		<div class="image-holder">
			<img src="1.jpg">
		</div>

		<div class="thumbs">
			<img src="1.jpg">
			<img src="2.jpg">
			<img src="3.jpg">
		</div>

	</div>

</body>
</html>