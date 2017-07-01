<!DOCTYPE html>
<html>
<head>
	<title>To Chat</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
  	<script src="/socket.io/socket.io.js"></script>

  	<style>
  		body{
  			margin-top: 30px;
  		}
  		#messageArea{
  			display: none;
  		}
  	</style>

</head>
<body>
	<div class="container">

		<div class="row" id="userFormArea" >
			<div class="col-md-12">
				<form id="userForm">
					<div class="form-group">
						<label>Enter Username</label>
						<textarea class="form-control" id="username"></textarea><br />
						<input type="submit" class="btn btn-primary" value="Login" />
					</div>
				</form>
			</div>
		</div>

		<div class="row" id="messageArea">
			<div class="col-md-4">
				<div class="well">
					<h3>Online Users</h3>
					<ul class="list-group" id="users"></ul>
				</div>
			</div>
			<div class="col-md-8">
				<div class="chat" id="chat"></div>
				
				<form id="messageForm">
					<div class="form-group">
						<label>Enter Message</label>
						<textarea class="form-control" id="message"></textarea><br />
						<input type="submit" class="btn btn-primary" value="Send Message" />
					</div>
				</form>
			</div>
		</div>

	</div>

	<script>
		$(function(){
			var socket = io.connect();
			var $messageForm = $('#messageForm');
			var $message = $('#message');
			var $chat = $('#chat');
			var $messageArea = $('#messageArea');
			var $userFormArea = $('#userFormArea');
			var $userForm = $('#userForm');
			var $users = $('#users');
			var $username = $('#username');



			$messageForm.submit(function(e){
				e.preventDefault();
				socket.emit('send message', $message.val());
				$message.val('');
			});

			socket.on('new message', function(data){
				$chat.append('<div class="well"><strong>'+data.user+'</strong>: '+data.msg+'</div>');
			});

			$userForm.submit(function(e){
				e.preventDefault();
				socket.emit('new user', $username.val(), function(data){
						if(data){
							$userFormArea.hide();
							$messageArea.show();
						}
				});
				$username.val('');
			});

			socket.on('get users', function(data){
					var html = '';
					for(i = 0; i<data.length; i++){
						html += '<li class="list-group-item">'+data[i]+'</li>';
					}
					$users.html(html);
			});
			
		});
	</script>

</body>
</html>