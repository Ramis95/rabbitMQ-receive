<?

namespace models;

use core\Model;

class CheckModel extends Model
{

	public function get_check($msg_body)
	{
		// Здесь запрос в бд

		$check_result = false;

//		$connect = false;

		$connect = oci_connect(DB_USER, DB_PASS,DB_HOST);
//		$connect = oci_connect('online_user', 'd83j3a', 'vpaydb.tattelecom.ttc/ttcpay');

		if ($connect) {

			$stid = oci_parse($connect, "select ot.ecr_registration_number, fiscal_document_number , fiscal_document_attribute from ofd_check o, ofd_transaction ot where o.id = ot.check_id
 									AND o.account_numb = '104184489' AND (o.DATE_IN BETWEEN to_date('01.04.20','dd.mm.yy') AND to_date('28.04.20','dd.mm.yy'))");

			oci_execute($stid);
			echo "<table>\n";
			while (($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS))
			       != false) {
				echo "<tr>\n";
				foreach ($row as $item) {
					echo "  <td>" . ($item !== null ? htmlspecialchars($item,
							ENT_QUOTES) : "&nbsp;") . " | </td>\n";
				}
				echo "</tr>\n";
			}
			echo "</table>\n";
		}


//		var_dump($connect);

		echo 'ret';
		die();

		return $check_result;
	}
}
