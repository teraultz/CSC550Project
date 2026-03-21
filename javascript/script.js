function handleLogin(event) {
	event.preventDefault();
	
	const username = document.getElementById("username").value;
	const password = document.getElementById("password").value;
	const role = document.querySelector('input[name="role"]:checked');
	
	if (role.value === "admin") {
		window.location.href = "admin.html";
	} else {
		window.location.href = "data_entry.html";
	}
}
