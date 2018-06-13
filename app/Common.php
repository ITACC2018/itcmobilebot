<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Common extends Model
{
    public function uuid($hyphen = true) {
        // The field names refer to RFC 4122 section 4.1.2
        if($hyphen == false){
            return sprintf('%04x%04x%04x%03x4%04x%04x%04x%04x',
            mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
            mt_rand(0, 65535), // 16 bits for "time_mid"
            mt_rand(0, 4095),  // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
            bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
            // 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
            // (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
            // 8 bits for "clk_seq_low"
            mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node"
            );
        }else{
            return sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
            mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
            mt_rand(0, 65535), // 16 bits for "time_mid"
            mt_rand(0, 4095),  // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
            bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
            // 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
            // (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
            // 8 bits for "clk_seq_low"
            mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node"
            );
        }
    }

    public static function dataLogging($ipAddress, $module, $action, $description, $npk){
        $data = array(
            'id' => uuid(false),
            'ip_address' => $ipAddress,
            'modules' => $module,
            'action' => $action,
            'description' => $description,
            'created_by' => $npk,
            'created_time' => date('Y-m-d H:i:s')
        );
        $cc_logging = DB::connection('mysql')->table('cc_logging')->insert($data);
        return $cc_logging;
    }

    public static function ticket_increament()
	{
		$year = date('Y');
		$ticket = $year.date('md').'0001';
        $sql = DB::connection('mysql')->table('cc_ticket')->select('ticket_id')->where('ticket_id', $ticket)->orderBy('ticket_id', 'desc')->limit(1)->get();
        $query = $sql->count();
		if($query > 0)
		{
            $rs = DB::connection('mysql')->table('cc_ticket')->select(DB::raw("max(ticket_id) as id"))->orderBy('ticket_id', 'desc')->get();
			foreach ($rs as $row)
			{
				return $row->id+1;
			}
		}
		else
		{
			return $ticket;
		}
    }
    

    public static function checkUserBackesk() {
        $sql = DB::connection('mysql')->table('cc_user')->select('*')->where('group_id', 'like', '%IT-BD%')->where('isRoundRobin', '=', '1')->get();
        $query = $sql->count();
		$arr_data = [];
		if($query > 0){
			//jika ada yang login
			foreach($sql as $data){
				$arr_data[] = $data->id;
			}
		}else{
			// tidak ada backdesk yang login
			$arr_data = null;
		}
		return $arr_data;		
    }
    
    public static function roundRobinBackdesk($list) {
        $json = json_encode($list);
        
        $sql = DB::connection('mysql')->table('cc_ticket')
                ->select('backdesk', DB::raw("count(backdesk) jumlah"))
                ->whereIn('backdesk', $list)
                ->whereDate('created_time', '=', date('Y-m-d'))
                ->groupBy('backdesk')
                ->orderBy('jumlah', 'asc')
                ->get();
        $count = $sql->count();
		if($count>0){
			$arrBd = array();
			foreach($sql as $data){
				$arrBd[] = $data->backdesk;
			}
			// Remove Data yang belum ada
            $arr = array_diff($list, $arrBd);
			// sudah sama rata		
			if(count($arr)==0){
                $sqlExist = DB::connection('mysql')->table('cc_ticket')
                ->select('backdesk', DB::raw("count(backdesk) jumlah"))
                ->whereIn('backdesk', $list)
                ->whereDate('created_time', '=', date('Y-m-d'))
                ->groupBy('backdesk')
                ->orderBy('jumlah', 'asc')
                ->first();
				return $sqlExist->backdesk;
			}else{
				// masih ada backdesk yang belum terima bucket
				// echo $arr[0];
				return array_shift($arr);
			}
		}else{
            $arr_first = explode(",",str_replace("]","",str_replace("[","",$json)));
			return str_replace('"',"",array_shift($arr_first));
		}

    }
    
    public static function insertTemp($interaction_id, $ticket_id, $to_group, $to_user, $npk= null, $group_user = null, $responseTime=null,$assignTime=null)
	{
		if($assignTime == null){
			$assign_time = date('Y-m-d H:i:s');
		}else{
			$assign_time = $assignTime;
		}
		$data = array(
					'id' => uuid(false),
					'interaction_id' => !empty($interaction_id->interaction_id) ? $interaction_id->interaction_id : '',
					'ticket_id' => $ticket_id,
					'from_group' => $group_user,
					'from_user' => $npk,
					'to_group' => $to_group,
					'to_user' => $to_user,
					'assign_time' => $assign_time,
					'response_time' => $responseTime,
                );
        DB::connection('mysql')->table('cc_temp_duration')->insert($data);
		return true;		
    }
    

    public static function updateTemp($ticket_id, $to_group, $to_user, $data ,$id_temp=null)
	{
		//$this->db->where('ticket_id',$ticket_id);
		if($id_temp != null){
            //$this->db->where('id',$id_temp);
            $updateReti = DB::connection('mysql')->table('cc_temp_duration')
            ->where('ticket_id', $ticket_id)
            ->where('id', $id_temp)
            ->update($data);
		}
		elseif($to_group != null){
            //$this->db->where('to_group',$to_group);
            $updateReti = DB::connection('mysql')->table('cc_temp_duration')
            ->where('ticket_id', $ticket_id)
            ->where('to_group', $to_group)
            ->update($data);
		}
		elseif($to_user != null){
            //$this->db->where('to_user',$to_user);
            $updateReti = DB::connection('mysql')->table('cc_temp_duration')
            ->where('ticket_id', $ticket_id)
            ->where('to_user', $to_user)
            ->update($data);
		}else{
            $updateReti = DB::connection('mysql')->table('cc_temp_duration')
            ->where('ticket_id', $ticketID)
            ->update($data);
        }
        //$this->db->update('cc_temp_duration', $data);
		return true;		
	}
}

//QUERY CLEANSING


/*

https://stackoverflow.com/questions/36228836/syntax-error-or-access-violation-1055-expression-8-of-select-list-is-not-in-gr
'connections' => [
    'mysql' => [
        'strict' => false,
    ]
]
set global sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

SELECT * FROM cc_ticket WHERE created_time > '2018-03-04';
SELECT * FROM cc_interaction WHERE created_time > '2018-03-04';
SELECT * FROM cc_interaction_detail_field WHERE created_time > '2018-03-04';
SELECT * FROM cc_ticket_approval WHERE created_time > '2018-03-04';
SELECT * FROM cc_temp_duration WHERE assign_time > '2018-03-04';
SELECT * FROM cc_logging WHERE created_time > '2018-03-04';

DELETE FROM cc_ticket WHERE created_time > '2018-03-04';
DELETE FROM cc_interaction WHERE created_time > '2018-03-04';
DELETE FROM cc_interaction_detail_field WHERE created_time > '2018-03-04';
DELETE FROM cc_ticket_approval WHERE created_time > '2018-03-04';
DELETE FROM cc_temp_duration WHERE assign_time > '2018-03-04';
DELETE FROM cc_logging WHERE created_time > '2018-03-04';
*/
