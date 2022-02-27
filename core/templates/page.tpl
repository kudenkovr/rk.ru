<!DOCTYPE html>

<html lang="ru">
<head>
	<title>[[+title]]</title>
</head>

<body>
	<div>
[[foreach (list as page)]]
	#[[+page.id]]: [[+page.title]]
[[endforeach]]
[[+content]]
	</div>
</body>
</html>