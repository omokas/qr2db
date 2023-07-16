<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>HMU ID Scanner Login</title>
		<link href="css/login.css" rel="stylesheet" type="text/css">
		<link href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" rel="stylesheet"/>
		<link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
		<link rel="manifest" href="/images/site.webmanifest">
		<link rel="mask-icon" href="/images/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">

	</head>
	<body>
		<div class="login">
			<h1>ID Scanner Login</h1>
			<form action="authenticate.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<label for="lesson_id">Lesson:</label>
				<select name="lesson_id" id="lesson_id">
					<option value="101">101 - Δομημένος Προγραμματισμός</option>
					<option value="102">102 - Γραμμική Αλγεβρα & Διαφορικός-Ολοκληρωτικός Λογισμός</option>
					<option value="103">103 - Φυσική για Μηχανικούς</option>
					<option value="104">104 - Θεωρία Κυκλωμάτων</option>
				</select>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>