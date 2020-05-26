<?

namespace models;

use core\Model;

class CheckModel extends Model
{

	public function get_check($msg_body)
	{
		$check_result = [];

		if ($this->db_connect) { //Проверяем соединение с бд

			// Фильтруем входные данные
			$account_numb
				       = strip_tags(htmlspecialchars(addslashes('104184489')));
			$date_from = strip_tags(htmlspecialchars(addslashes('01.04.20')));
			$date_to   = strip_tags(htmlspecialchars(addslashes('28.04.20')));

			$stid = oci_parse($this->db_connect, "select ot.ecr_registration_number, fiscal_document_number , fiscal_document_attribute from ofd_check o, ofd_transaction ot where o.id = ot.check_id
 									AND o.account_numb = :account_numb AND (o.DATE_IN BETWEEN to_date(:date_from,'dd.mm.yy') AND to_date(:date_to,'dd.mm.yy'))");

			oci_bind_by_name($stid, ':account_numb', $account_numb, -1);
			oci_bind_by_name($stid, ':date_from', $date_from, -1);
			oci_bind_by_name($stid, ':date_to', $date_to, -1);

			oci_execute($stid); // Делаем подготовленный запрос

			while (($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS))
			       != false) {
				$check_result[] = $row;
			}
		} else {
			// Если с бд что-то случилось, отправляем сигнал, что база отвалилась
			// Завершаем выполнение
			$response = [
				'status'  => 'error',
				'message' => 'БД не отвечает',
			];
			echo json_encode($response);
			die();
		}

		return $check_result;
	}
}
