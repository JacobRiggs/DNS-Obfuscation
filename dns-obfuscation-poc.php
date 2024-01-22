<div class="poc-form">												
	<p id="code-notice" class="heading-tescription lead" style="text-align:center;"><strong>To access your user instance, please enter the code we emailed to you here:</strong></p>									
	<?php
	$correctCode = "133337";
	$isAuthenticated = false;
	$successMessage = "";
	$error_message = "";

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
	  $inputCode = implode('', $_POST["code"]);

	  if ($inputCode === $correctCode) {
		$isAuthenticated = true;
		$successMessage = "<div class='message' style='display: block;'>
					<div class='errors'>
					  <p>Please note that clicking the button below will open your user instance in a new tab, but also send 9 background requests to Deadswitch honeypots. All requests are sent in a random order. We do this in effort to help obfuscate your web traffic when visiting your switch.</p>
					</div>
				  </div>";
		echo '<script>document.getElementById("code-notice").style.display = "none";</script>';
	  } else {
		$error_message = "<div class='message' style='display: block;'>
					<div class='errors'>
					  <p>The code you entered is not valid.</p>
					</div>
				  </div>";
	  }
	}
	echo $error_message;
	echo $successMessage;
	?>

	<div id="redirect-button" class="visit-switch" style="<?php echo $isAuthenticated ? 'display: grid;' : 'display: none;'; ?>">
	  <?php
	  if ($isAuthenticated) {
		echo '<button class="clipboard-button" onclick="openAndCloseUrls()">VISIT YOUR USER INSTANCE</button>
		<div id="table-container" style="display: none;"><br/><br/>
		<p style="text-align:center;">The table below presents a randomised list of URLs that have were fetched by your browser in the background.</p>
		  <table id="request-table">
			<thead>
			  <tr>
				<th>#</th>
				<th>STATUS</th>
				<th>URL</th>
				<th>TYPE</th>
				<th>TIMESTAMP</th>
			  </tr>
			</thead>
			<tbody>
			  <!-- Rows added here dynamically -->
			</tbody>
		  </table>
		</div>';
	  }
	  ?>
	</div>
	<div class="form-container">
		<form action="" method="POST" style="<?php echo $isAuthenticated ? 'display: none;' : 'display: block;'; ?>">
			<input class="code-input" type="text" name="code[]" maxlength="1">
			<input class="code-input" type="text" name="code[]" maxlength="1">
			<input class="code-input" type="text" name="code[]" maxlength="1">
			<input class="code-input" type="text" name="code[]" maxlength="1">
			<input class="code-input" type="text" name="code[]" maxlength="1">
			<input class="code-input" type="text" name="code[]" maxlength="1">
			<button class="clipboard-button" type="button">SUBMIT</button>
		</form>
	</div>

</div>

<script>
$(document).ready(function() {
	$('input[name="code[]"]').on('input', function(e) {
		if ($(this).val()) {
			$(this).next('input').focus();
		}
	});

	$('input[name="code[]"]').on('keydown', function(e) {
		if (e.which == 8 || e.key == 'Backspace') {
			if (!$(this).val()) {
				$(this).prev('input').focus();
			}
		}
	});

	$('input[name="code[]"]:last').on('input', function(e) {
		if ($(this).val()) {
			$(this).closest('form').submit();
		}
	});
});
</script>

<script>
  function shuffle(array) {
	let currentIndex = array.length, temporaryValue, randomIndex;
	while (0 !== currentIndex) {
	  randomIndex = Math.floor(Math.random() * currentIndex);
	  currentIndex -= 1;

	  temporaryValue = array[currentIndex];
	  array[currentIndex] = array[randomIndex];
	  array[randomIndex] = temporaryValue;
	}

	return array;
  }

  async function openAndCloseUrls() {
	document.getElementById('code-notice').style.display = 'none';	  
	let urls = [
	  'https://d6a605571111908791d11635671e996ad9cf64e7.deadswitch.com',
	  'https://de6c65b415cde1e01bb0b050fac3baaa2139a8fa.deadswitch.com',
	  'https://1c697f2c821b85f444fafe58b2ee6ede09d6765f.deadswitch.com',
	  'https://1b514865f67accb4e576d3c1d9f1908904a9e9c2.deadswitch.com',
	  'https://630b16ed25a57fb6da08d9f69dd7a41062b1f8b7.deadswitch.com',
	  'https://3ee36864b07ec28b12f0853df6e07f84558cb011.deadswitch.com',
	  'https://2e6e77cad9fa7fff68e114209d618cd3cf497bb3.deadswitch.com',
	  'https://d67c48b58a79ba130c5339e4d3b3a6af28afe39c.deadswitch.com',
	  'https://4ca0c934434c322a9c4623d496f112d803e32bfc.deadswitch.com',
	  'https://cc22f15439717efc0494589d639015a7417c0ef4.deadswitch.com'
	];

	urls = shuffle(urls);

	let urlToKeepOpen = 'https://3ee36864b07ec28b12f0853df6e07f84558cb011.deadswitch.com';
	let counter = 1;
	let tableBody = document.querySelector('#request-table tbody');
	tableBody.innerHTML = "";

	for (let i = 0; i < urls.length; i++) {
	  let currentTime = new Date();
	  let timestamp = currentTime.toLocaleString();
	  
	  let row = document.createElement('tr');
	  row.className = 'row-link';
	  row.onclick = function() {
		window.location.href = urls[i];
	  };

	  let indexCell = document.createElement('td');
	  indexCell.textContent = counter++;
	  row.appendChild(indexCell);

	  let statusCell = document.createElement('td');
	  statusCell.textContent = "REQUEST SENT";
	  row.appendChild(statusCell);
	  
	  let urlCell = document.createElement('td');
	  let urlLink = document.createElement('a');
	  urlLink.href = urls[i];
	  urlLink.textContent = urls[i];
	  urlLink.className = 'no-style';
	  urlCell.appendChild(urlLink);
	  row.appendChild(urlCell);
	  
	  let typeCell = document.createElement('td');
	  typeCell.style.textAlign = 'center';

	  if(urls[i] === urlToKeepOpen) {
		typeCell.textContent = 'USER INSTANCE';
		row.classList.add('special-row');
	  } else {
		typeCell.textContent = 'HONEYPOT';
		typeCell.style.color = '#bf2526';
		typeCell.style.fontWeight = 'bold';
		await fetch(urls[i], {mode: 'no-cors'});
	  }
	  row.appendChild(typeCell);

	  let timeCell = document.createElement('td');
	  timeCell.textContent = timestamp;
	  row.appendChild(timeCell);

	  tableBody.appendChild(row);
	}

	window.open(urlToKeepOpen, '_blank');

	document.getElementById('table-container').style.display = 'block';
  }
</script>