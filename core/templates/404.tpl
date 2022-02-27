<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset=utf-8>
	<meta name=viewport content="initial-scale=1, minimum-scale=1, width=device-width">
	<title>Error 404 (Not Found)!!!</title>
	<style>
		*{margin:0;padding:0}
		html,code{font:15px/22px arial,sans-serif}
		html{background:#242b31;color:#fffbef;padding:15px}
		body{margin:7% auto 0;max-width:390px;min-height:180px;padding:30px 0 30px}
		* > body{background:url(/images/rk/logo_rk_v2.png) 100% 15px no-repeat;padding-right:205px;background-size:198px;}
		p{margin:11px 0 22px;overflow:hidden}ins{color:#777;text-decoration:none}
		h1 {font-size: 2em; margin-bottom: 1em; font-family: Courier New}
		a img{border:0}@media screen and (max-width:772px){body{background:none;margin-top:0;max-width:none;padding-right:0}}
		#logo{background:url(/images/rk/logo_rk_v2.png) no-repeat;margin-left:-5px}
		@media only screen and (min-resolution:192dpi){
			#logo{
				background:url(/images/rk/logo_rk_v2.png) no-repeat 0% 0%/100% 100%;
				-moz-border-image:url(/images/rk/logo_rk_v2.png) 0
			}
		}
		@media only screen and (-webkit-min-device-pixel-ratio:2){
			#logo{
				background:url(/images/rk/logo_rk_v2.png) no-repeat;
				-webkit-background-size:100% 100%
			}
		}
		#logo{display:inline-block;height:54px;width:150px}
	</style>
</head>

<body>
	<!-- <a href=//[[+request.domain]]><span id=logo aria-label="RK Light"></span></a> -->
	<h1>RK Light v[[+info.version]]</h1>
	<p><b>404.</b> <ins>That’s an error.</ins></p>
	<p>The requested URL <code><b>[[+request.url]]</b></code> was not found on this server.  <ins>That’s all we know.</ins></p>
</body>
</html>