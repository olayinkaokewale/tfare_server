<?php
include "includes/engine.class.php";
//Create new Engine object
$Engine = new Engine();
//Test if server_post is set.

/********************************************************
PROGRAMMER: OLAYINKA OKEWALE
FB: http://www.facebook.com/okjool
IG: @olayinkaokewale
PHONE NO.: 08165707173
COMMENT: This is the tfare app server engine.
********************************************************/ 

if (isset($_POST["server_post"])) {
	
	$server_post = $_POST["server_post"];
	$post_arr = json_decode($server_post, true);
	$action = $post_arr["action"];
	//From here... check the different actions passed.
	///USER LOGIN
	if ($action == "user_login") {
		if (isset($post_arr['phone_number'], $post_arr['password'])) {
			$feedback = $Engine->userLogin($post_arr['phone_number'], $post_arr['password']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//USER REGISTRATION
	else if ($action == "user_register") {
		if (isset($post_arr['phone_number'], $post_arr['password'])) {
			$feedback = $Engine->userRegister($post_arr['phone_number'], $post_arr['password']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//CHANGE PASSWORD
	else if ($action == "password_change") {
		if (isset($post_arr['phone_number'], $post_arr['password'])) {
			$feedback = $Engine->changePassword($post_arr['phone_number'], $post_arr['password']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//UPDATE PROFILE
	else if ($action == "update_profile") {
		if (isset($post_arr['user_id'], $post_arr['fullname'], $post_arr['location'])) {
			$feedback = $Engine->updateProfile($post_arr['user_id'], $post_arr['fullname'], $post_arr['location']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//BUY TICKET
	else if ($action == "buy_ticket") {
		if (isset($post_arr['user_id'], $post_arr['start_bus_stop'], $post_arr['destination'], $post_arr['price'], $post_arr['ticket_admits'])) {
			$feedback = $Engine->buyTicket($post_arr['user_id'], $post_arr['start_bus_stop'], $post_arr['destination'], $post_arr['price'], $post_arr['ticket_admits']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//INSERT CREDIT CARD
	else if ($action == "add_card") {
		if (isset($post_arr['user_id'], $post_arr['card_number'], $post_arr['card_cv2'], $post_arr['card_exp_date'], $post_arr['card_pin'], $post_arr['isgiftcard'])) {
			$feedback = $Engine->insertCreditCard($post_arr['user_id'], $post_arr['card_number'], $post_arr['card_cv2'], $post_arr['card_exp_date'], $post_arr['card_pin'], $post_arr['isgiftcard']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//UPDATE USER BALANCE
	else if ($action == "balance_topup") {
		if (isset($post_arr['user_id'], $post_arr['card_id'], $post_arr['amount'])) {
			$feedback = $Engine->updateUserBalance($post_arr['user_id'], $post_arr['card_id'], $post_arr['amount']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//FILE COMPLAINT
	else if ($action == "file_complaint") {
		if (isset($post_arr['user_id'], $post_arr['title'], $post_arr['urgency'], $post_arr['msg'])) {
			$feedback = $Engine->sendComplaint($post_arr['user_id'], $post_arr['title'], $post_arr['urgency'], $post_arr['msg']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//DELETE CARD
	else if ($action == "delete_card") {
		if (isset($post_arr['user_id'], $post_arr['card_id'])) {
			$feedback = $Engine->deleteCard($post_arr['user_id'], $post_arr['card_id']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//GET TICKET HISTORY
	else if ($action == "get_ticket_history") {
		if (isset($post_arr['user_id'])) {
			$feedback = $Engine->getTicketHistory($post_arr['user_id']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//GET USER CREDITCARDS
	else if ($action == "get_user_creditcard") {
		if (isset($post_arr['user_id'])) {
			$feedback = $Engine->getUserCards($post_arr['user_id']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}



	//MERCHANT SIDE:
	//VERIFY TICKET.
	else if ($action == "verify_ticket") {
		if (isset($post_arr['ticket_id'])) {
			$feedback = $Engine->verifyUserTicket($post_arr['ticket_id']);
		} else {
			$feedback["feedback"] = 2;
			$feedback["details"] = "Data truncation occured. Please try again";
		}
	}

	//SET TICKET TO USED
	else if ($action == "set_used_ticket") {
		if (isset($post_arr['ticket_id'])) {
			$feedback = $Engine->setTicketAsUsed($post_arr['ticket_id']);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Data truncation occured. Please try again";
		} 
	}


	echo json_encode($feedback);
}

?>