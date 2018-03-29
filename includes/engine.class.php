<?php
require_once('definitions.php');
require_once('error_handler.php');

/********************************************************
PROGRAMMER: OLAYINKA OKEWALE
FB: http://www.facebook.com/okjool
IG: @olayinkaokewale
PHONE NO.: 08165707173
COMMENT: This is the tfare app server engine.
********************************************************/ 

Class Engine {
	private $mysqli;

	//CONSTRUCTOR METHOD
	function __construct() {
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	}

	//DESTRUCTOR METHOD
	function __destruct() {
		$this->mysqli->close();
	}

	//OTHER METHODS FOR SMOOTH OPERATION.
	//Register Function
	function userRegister($phoneNumber, $password) {
		$phoneNumber = $this->mysqli->real_escape_string($phoneNumber);
		$txtPass = $password;
		$password = md5($this->mysqli->real_escape_string($password));
		$query = "INSERT INTO `users` (`phone_number`, `password`) VALUES ('$phoneNumber', '$password')";
		$result = $this->mysqli->query($query);
		if ($result != '') {//Successful
			$feedback = $this->userLogin($phoneNumber, $txtPass);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "Registration failed";
		}
		return $feedback;
	}

	//Login Function
	function userLogin($phoneNumber, $password) {
		$phoneNumber = $this->mysqli->real_escape_string($phoneNumber);
		$password = md5($this->mysqli->real_escape_string($password));
		$userSelect = "SELECT * FROM `users` WHERE `phone_number` = '$phoneNumber'";
		$result = $this->mysqli->query($userSelect);
		if ($result->num_rows > 0) { //USER EXIST
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$serverPassword = $row["password"];
			if ($serverPassword == $password) {
				$feedback["feedback"] = 1;
				$feedback["details"] = $row;
			} else {
				$feedback["feedback"] = 0;
				$feedback["details"] = "Incorrect password! Try again";
			}
		} else { //USER DOES NOT EXIT
			$feedback["feedback"] = 0;
			$feedback["details"] = "User does not exist. Try to register first";
		}
		return $feedback;
	}

	//Change pin/password function
	function changePassword($phoneNumber, $newPassword) {
		$phoneNumber = $this->mysqli->real_escape_string($phoneNumber);
		$password = md5($this->mysqli->real_escape_string($newPassword));
		$query = "UPDATE `users` SET `password`='$password' WHERE `phone_number`='$phoneNumber'";
		$result = $this->mysqli->query($query);
		if ($result != '') {//Successful
			$feedback["feedback"] = 1;
			$feedback["details"] = "password changed successfully";
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "password change failed";
		}
		return $feedback;
	}

	//Update profile function
	function updateProfile($userId, $fullname, $location) {
		$userId = $this->mysqli->real_escape_string($userId);
		$fullname = $this->mysqli->real_escape_string($fullname);
		$location = $this->mysqli->real_escape_string($location);
		$query = "UPDATE `users` SET `fullname`='$fullname', `location`='$location' WHERE `user_id`='$userId'";
		$result = $this->mysqli->query($query);
		if ($result != '') {//Successful
			$feedback["feedback"] = 1;
			$feedback["details"] = "profile updated successfully";
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "profile update failed";
		}
		return $feedback;
	}

	//Buy ticket
	function buyTicket($userId, $startBusStop, $destination, $price, $ticketAdmits) {
		$userId = $this->mysqli->real_escape_string($userId);
		$startBusStop = $this->mysqli->real_escape_string($startBusStop);
		$destination = $this->mysqli->real_escape_string($destination);
		$price = $this->mysqli->real_escape_string($price);
		$ticketAdmits = $this->mysqli->real_escape_string($ticketAdmits);

		$query = "INSERT INTO `ticket` (`user_id`, `start_bus_stop`, `destination`, `price`, `ticket_admits`) VALUES ('$userId', '$startBusStop', '$destination', '$price', '$ticketAdmits')";
		$result = $this->mysqli->query($query);
		if ($result != '') {//Successful
			$feedback["feedback"] = 1;
			$feedback["details"] = "ticket bought successfully";
			$feedback["ticket_id"] = $this->mysqli->insert_id;

			$feedback["firebase_notification"] = $this->sendBoughtTicketNotification($merchantId, $feedback["ticket_id"]);
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "unable to purchase ticket";
		}
		return $feedback;
	}

	//Insert credit card details.
	function insertCreditCard($userId, $card_number, $card_cv2, $card_exp_date, $card_pin, $isGiftCard) {
		$userId = $this->mysqli->real_escape_string($userId);
		$card_number = $this->mysqli->real_escape_string($card_number);
		$card_cv2 = $this->mysqli->real_escape_string($card_cv2);
		$card_exp_date = $this->mysqli->real_escape_string($card_exp_date);
		$card_pin = $this->mysqli->real_escape_string($card_pin);
		$isGiftCard = $this->mysqli->real_escape_string($isGiftCard);

		if ($isGiftCard == "1") {
			//First check the gift card if there's something like that.
			$checkGiftCardTable = $this->mysqli->query("SELECT * FROM `giftcard` WHERE `card_number`='$card_number' AND `card_cv2`='$card_cv2'");
			if ($checkGiftCardTable->num_rows > 0) { //Card exists, now add to the card table.
				$query = "INSERT INTO `creditcard` (`user_id`, `card_number`, `card_cv2`, `card_exp_date`, `card_pin`, `isgiftcard`) VALUES ('$userId', '$card_number', '$card_cv2', '$card_exp_date', '$card_pin', '$isGiftCard')";
				$result = $this->mysqli->query($query);
				if ($result != '') {//Successful
					//Update the giftcard usage to 1 - indicating used.
					$this->mysqli->query("UPDATE `giftcard` SET `card_used`='1' WHERE `card_number`='$card_number' AND `card_cv2`='$card_cv2'");
					//Set feedback
					$feedback["feedback"] = 1;
					$feedback["details"] = "credit card added successfully";
				} else {
					$feedback["feedback"] = 0;
					$feedback["details"] = "unable to add credit card";
				}
			} else {
				//Tell the user the card does not exist.
				$feedback["feedback"] = 0;
				$feedback["details"] = "Card does not exist. Please try using a valid card";
			}
		} else {
			//Verify card if it is correct or not.
		}
		
		return $feedback;
	}

	//Retrieve user's cards from database
	function getUserCards($userId) {
		$userId = $this->mysqli->real_escape_string($userId);
		$query = "SELECT * FROM `creditcard` WHERE `user_id`='$userId'";
		$result = $this->mysqli->query($query);
		if ($result->num_rows > 0) {
			$feedback["feedback"] = 1;
			$c = 0;
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$feedback["details"][$c] = $row;
				$c = $c + 1;
			}
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "You have no saved credit card";
		}
		return $feedback;
	}

	//Update balance for user.
	function updateUserBalance($userId, $cardId, $amount) {
		$userId = $this->mysqli->real_escape_string($userId);
		$cardId = $this->mysqli->real_escape_string($cardId);
		$amount = $this->mysqli->real_escape_string($amount);
		//First: Verify if the card is the owner's card.
		$getCardQuery = "SELECT * FROM `creditcard` WHERE `user_id`='$userId' AND `card_id`='$cardId'";
		$getCardResult = $this->mysqli->query($getCardQuery);
		if ($getCardResult->num_rows > 0) { //Card exists.
			$cardRow = $getCardResult->fetch_array(MYSQLI_ASSOC);
			$isGiftCard = $cardRow["isgiftcard"];
			$cardNumber = $cardRow["card_number"];
			$cardCv2 = $cardRow["card_cv2"];

			//Get card query.
	 		if ($isGiftCard == "1") { //Get card value from giftcard table.
				$query = "SELECT `card_value`, `card_id` FROM `giftcard` WHERE `card_number`='$cardNumber' AND `card_cv2`='$cardCv2'";
				$result = $this->mysqli->query($query);
				if ($result->num_rows > 0) {
					$row = $result->fetch_array(MYSQLI_ASSOC);
					$balance = $row['card_value'];
					$giftcardId = $row['card_id'];
					if ($balance >= $amount) {
						//Subtract amount from balance
						$remainder = intval($balance) - intval($amount);
						//update gift card value with balance and update user with the amount as balance.
						$updateGiftCard = $this->mysqli->query("UPDATE `giftcard` SET `card_value`='$remainder' WHERE `card_id`='$giftcardId'");
						if ($updateGiftCard != "") {
							//First get the existing amount on user balance and add the amount to it.
							$getUserBalance = $this->mysqli->query("SELECT `topup_balance` FROM `users` WHERE `user_id`='$userId'");
							$userBal = $getUserBalance->fetch_array(MYSQLI_ASSOC)["topup_balance"];
							$topupAmount = intval($amount) + intval($userBal);
							$updateUserBalance = $this->mysqli->query("UPDATE `users` SET `topup_balance`='$topupAmount' WHERE `user_id`='$userId'");
							if ($updateUserBalance != "") {
								$feedback["feedback"] = 1;
								$feedback["details"] = "Top-up successful";
								$feedback["topup_balance"] = $topupAmount;
							} else {
								$feedback["feedback"] = 0;
								$feedback["details"] = "Top-up failed!";
								//Revert back the amount taken from the gift card.
								$revertCardValue = "UPDATE `giftcard` SET `card_value`='$balance' WHERE `card_id`='$giftcardId'";
								$this->mysqli->query($revertCardValue);
							}
						} else {
							$feedback["feedback"] = 0;
							$feedback["details"] = "Error processing card.";
						}
						
					} else {
						//The user does not have sufficient amount to get the topup
						$feedback["feedback"] = 0;
						$feedback["details"] = "Insufficient fund in card";
					}
				} else {
					//Card does not exist. Give a feedback.
					$feedback["feedback"] = 0;
					$feedback["details"] = "There is no such gift card. Please try another card.";
				}
			} else {
				//Get the amount verification from third party cards.
			}
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "There is no such card assocociated to your account";
		}
		return $feedback;
	}

	//Insert complaint.
	function sendComplaint($userId, $title, $urgency, $complaint) {
		$userId = $this->mysqli->real_escape_string($userId);
		$title = $this->mysqli->real_escape_string($title);
		$urgency = $this->mysqli->real_escape_string($urgency);
		$complaint = $this->mysqli->real_escape_string($complaint);

		$query = "INSERT INTO `complaints` (`user_id`, `complaint_title`, `complaint_urgency`, `complaint`) VALUES ('$userId', '$title', '$urgency', '$complaint')";
		$result = $this->mysqli->query($query);
		if ($result != '') {
			$feedback["feedback"] = 1;
			$feedback["details"] = "complaint was successfully filed";
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "unable to file complaint";
		}
		return $feedback;
	}

	//Delete card.
	function deleteCard($userId, $cardId) {
		$userId = $this->mysqli->real_escape_string($userId);
		$cardId = $this->mysqli->real_escape_string($cardId);

		$query = "DELETE FROM `creditcard` WHERE `user_id`='$userId' AND `card_id`='$cardId'";
		$result = $this->mysqli->query($query);
		if ($result != '') { //Successful
			$feedback["feedback"] = 1;
			$feedback["details"] = "card was successfully deleted";
		} else { //Failed.
			$feedback["feedback"] = 0;
			$feedback["details"] = "unable to delete card";
		}
		return $feedback;
	}

	//Get ticket history from database
	function getTicketHistory($userId) {
		$userId = $this->mysqli->real_escape_string($userId);

		$query = "SELECT * FROM `ticket` WHERE `user_id`='$userId' ORDER BY `buying_time` DESC";
		$result = $this->mysqli->query($query);
		if ($result->num_rows > 0) {
			$c = 0;
			$feedback["feedback"] = 1;
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$feedback["details"][$c] = $row;
				$c = $c + 1;
			}
		} else {
			$feedback["feedback"] = 0;
			$feedback["details"] = "You have no ticket history";
		}
		return $feedback;
	}





	//MERCHANT SIDE ENGINE WORK.
	//Fetch ticket from database and check its status.
	function verifyUserTicket($ticketId) {
		$ticketId = $this->mysqli->real_escape_string($ticketId);

		$query = "SELECT * FROM `ticket` WHERE `ticket_id`='$ticketId'";
		$result = $this->mysqli->query($query);
		if ($result->num_rows > 0) {
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$ticketUsed = $row['used'];
			if ($ticketUsed == '0') {
				$feedback["feedback"] = 1;
				$feedback["details"] = $row;
			} else {
				$feedback["feedback"] = 0;
				$feedback["message"] = "Ticket has been used";
			}
		} else {
			$feedback["feedback"] = 0;
			$feedback["message"] = "Invalid ticket";
		}
		return $feedback;
	}

	//Function to set ticket as used
	function setTicketAsUsed($ticketId) {
		$ticketId = $this->mysqli->real_escape_string($ticketId);
		$time = date("Y-m-d H:i:s", time());
		$query = "UPDATE `ticket` SET `used`='1',`usage_time`='$time' WHERE `ticket_id`='$ticketId'";
		$this->mysqli->query($query);
		$result = $this->mysqli->affected_rows;
		if ($result > 0) {
			$feedback["feedback"] = 1;
			$feedback["message"] = "Ticket was successfully set as used.";
		} else {
			$feedback["feedback"] = 0;
			$feedback["message"] = "Unable to set ticket as used./nPossible Errors:/n -Ticket is invalid/n -Ticket already set as used";
		}
		return $feedback;
	}


	
}

?>