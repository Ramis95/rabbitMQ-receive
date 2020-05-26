<?

namespace controllers;

use core\Rabbit;
use interfaces\SenderInterface;
use senders\SendSMS;
use senders\SendEmail;
use senders\SendTelegram;

class CheckController extends Rabbit
{
	public $check_model;
	private $msg;

	public function __construct($check_model)
	{
		parent::__construct();
		$this->check_model = $check_model;
	}

	public function listenQueu()
	{
		$channel = $this->RabbitConnection->channel();
		$channel->queue_declare('MyQueue', false, true, false,
			false); // Объявляем очередь, если очередь объявлена то она не создается по новой

//		echo " [*] Ждем сообщении. Для выхода CTRL+C\n";

		$callback = function ($msg) {
			$this->msg = $msg;
			$this->receive_check();
		};

		$channel->basic_consume('MyQueue', '', false, false, false, false,
			$callback);

		while (count($channel->callbacks)) {
			$channel->wait();
		}

		$channel->close();
		$this->RabbitConnection->close();
	}

	private function receive_check()
	{
		$msg_body = json_decode($this->msg->body);
		$sender   = false;

		$check_db_result = $this->check_model->get_check($msg_body);

		if ($msg_body->response_type == 1) {
			$sender = new SendEmail();
		} elseif ($msg_body->response_type == 2) {
			$sender = new SendSMS();
		} elseif ($msg_body->response_type == 3) {
			$sender = new SendTelegram();
		} else {
			// Завершаем выполнение
			echo json_encode('Нет такого способа ответа');
			die();
		}


		$this->send_check($sender, $check_db_result);
	}

	private function send_check(SenderInterface $sender, $check)
	{
		$sender->send($this->msg, $check);
	}
}
