<?

namespace senders;

use interfaces\SenderInterface;

class SendSMS implements SenderInterface
{
    public function send($msg, $check)
    {
        $sender = true;

        if ($sender)  // Проверяем отправителя. Если он доступен и чек успешно отправлен, удаляем сообщение из очереди
        {
            //Проверяем чек на пустоту, если не пусто то строим чек и отправляем его клиенту
            echo 'SendSMS' . "\n";
            var_dump($msg->body);
	        echo $check . "\n";
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
