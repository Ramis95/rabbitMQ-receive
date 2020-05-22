<?

namespace models;

use core\Model;

class CheckModel extends Model
{

    public function get_check($msg_body)
    {
        // Здесь запрос в бд

        $db_connection = true;

        if(!$db_connection) // Если нет соединения с БД, возвращаем false
        {
            $check_result = false;
        }
        else
        {
            $check_result = 'Какой-то чек из бд';
        }

        return $check_result;
    }
}
