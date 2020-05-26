<?

namespace core;

abstract class Model
{
	protected $db_connect;

    function __construct()
    {
	    $this->db_connect = oci_connect(DB_USER, DB_PASS,DB_HOST);
    }
}
