<?

namespace senders;

use interfaces\SenderInterface;

class SendSMS implements SenderInterface
{
	public function send($msg, $check)
	{
		$sender = true;

		if ($sender)  // Проверяем отправителя. Если он доступен и чек успешно отправлен, удаляем сообщение из очереди //Переделать
		{


			if ($check)//Проверяем чек на пустоту, если не пусто то строим чек и отправляем его клиенту
			{
				foreach ($check as $key => $value) {
					echo 'https://ofd.yandex.ru/vaucher/'
					     . $value['ECR_REGISTRATION_NUMBER'] . '/'
					     . $value['FISCAL_DOCUMENT_NUMBER'] . '/'
					     . $value['FISCAL_DOCUMENT_ATTRIBUTE'] . "\n";
				}
			} else {
				echo "Нет чека за этот период \n";
			}

			$msg_body = json_decode($msg->body);

			echo 'SendSMS Пользователю:' . $msg_body->phone;
			echo "\n";
//	        echo $check . "\n";
			echo "-------------------------------------------------------------------------\n";

			$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']); // Удаляем сообщение из очереди

		} else {
			// Если с отправителем что-то случилось или сообщение не отправилось, посылаем сигнал, что отправитель отвалился
			// Завершаем выполнение
			echo 'смс сервис не отвечает';
			die();
		}
	}
}
