<html>

<body>

<div>
	Copy This:<br />
	Login: {"action":"user_login", "phone_number":"", "password":""} <br />
	Register: {"action":"user_register", "phone_number":"", "password":""} <br />
	Change Password: {"action":"password_change", "phone_number":"", "password":""} <br />
	Update Profile: {"action":"update_profile", "user_id":"", "fullname":"", "location":""}<br />
	Buy Ticket: {"action":"buy_ticket", "user_id":"", "start_bus_stop":"", "destination":"", "price":"", "ticket_admits":""}<br />
	Add card: {"action":"add_card", "user_id":"", "card_number":"", "card_cv2":"", "card_exp_date":"", "card_pin":"", "isgiftcard":""}<br />
	Top-up Balance: {"action":"balance_topup", "user_id":"", "card_id":"", "amount":""}<br />
	Complaint: {"action":"file_complaint", "user_id":"", "title":"", "urgency":"", "msg":""}<br />
	Delete Card: {"action":"delete_card", "user_id":"", "card_id":""}<br />
	Get Ticket History: {"action":"get_ticket_history", "user_id":""}<br />
	Get User CreditCard: {"action":"get_user_creditcard", "user_id":""}<br />

	MERCHANT<br />
	Verify Ticket: {"action":"verify_ticket", "ticket_id":""}<br />
	Set Ticket As Used: {"action":"set_used_ticket", "ticket_id":""}<br />
</div>

<form method="POST" action="index.php">
	<textarea name="server_post" placeholder="JSON Code" style="width: 300px;height: 200px;"></textarea>
	<input type="submit" name="Submit Query" />
</form> 

</body>

</html>