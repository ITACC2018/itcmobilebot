<?php
//header("Access-Control-Allow-Origin: *");
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Common;
use App\Faqs;
/*
|--------------------------------------------------------------------------
| API IT CARE
|--------------------------------------------------------------------------
|
| MAAFKAN DAKU CODE BERANTAKAN..SOALE CUMAN COPAS DARI IT CARE YANG DULUU.
| IYA SAMMAAA GW JUGA PUYENG BACA CODE NYAAAAAAAA!! HAHA
| #HARAP_MAKLUM
| @ARDYANTO_15525
|
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/** 
 * LOGIN ROUTE
 */
Route::post('/login',function(Request $request){
    $postData = !empty( $request->getContent()) ? json_decode( $request->getContent()) : [];
    if (!empty($postData->npk) AND !empty($postData->password)) {
        //$user = DB::table('cc_user')->where('npk', $postData->npk)->where('password', md5($postData->password))->first();
        $user = DB::connection('mysql')->table('cc_user AS a')
            ->Join('cc_customer AS b', 'b.npk', '=', 'a.npk')
            ->Join('cc_customer AS c', 'b.npk_spv', '=', 'c.npk')
            ->select(
                'a.npk', 'a.name', 'a.group_id', 'b.branch', 'b.email', 'b.position',
                DB::raw('CONCAT(c.customer_name," (",c.npk," )") AS spv'),
                DB::raw('a.npk AS npk_crypt')
            )
            ->where('a.npk', $postData->npk)->where('a.password', md5($postData->password))->first();
        if(!empty($user)){
            
            $crypt = Crypt::encrypt($user->npk_crypt);
            $user->npk_crypt = $crypt;
            $userData = json_encode($user);
            echo '{"userData": ' .$userData . '}';
        } else {
            echo '{"error":{"text":"Bad request wrong username and password"}}';
        }
    }else{
        echo '{"error":{"text":"Bad request wrong username and password"}}';
    }
});

/**
 * COAAAAA
 */
Route::get('coa',function(){
    $coa = DB::connection('mysql')->table('cc_master_sub_sub_sub_category')
            ->Join('cc_master_sub_sub_category', 'cc_master_sub_sub_sub_category.sub_sub_category_id', '=', 'cc_master_sub_sub_category.id')
            ->select(
                DB::raw("CONVERT(cc_master_sub_sub_sub_category.id, CHAR) id"),
                DB::raw('UPPER(CONCAT(cc_master_sub_sub_category.name, " - ", cc_master_sub_sub_sub_category.name)) AS name')
            )
            ->where('cc_master_sub_sub_sub_category.is_actived', '=', '1')
            ->where('is_user', '=', '1')
            ->get()->toarray();
    $arr_data[] = (object)array('id'=> '0000000000', 'name' => 'KETIK / PILIH KATEGORI');
    $coa = array_merge($arr_data,$coa);
   

    $flexibleInput = DB::connection('mysql')->table('cc_master_field')
                    ->select('input_field')
                    ->whereNotNull('input_field')
                    ->get();
    $arr = [];
    $arx = [];
    $arf = [];
    $implode = '';
    
    if($flexibleInput){
        foreach($flexibleInput as $key => $value){
            $arx[] = $value->input_field;
        }
        $implode =  implode(';', $arx);
    }
    $explode = explode(";",$implode);
    $arr['coa'] =  $coa;
    $arr['flexibleInput'] =  $explode;
    return response()->json($arr, 200);
});

/**
 * checkDataApprovalDetail
 */

Route::get('coadetail/{id}', function ($id) {
    /* checkDataApprovalDetail */
    $checkDataApprovalDetail = DB::connection('mysql')->table('cc_master_approval')
    ->select(DB::raw('GROUP_CONCAT(npk ,"-" ,NAME ) npk'))
    ->where('coa', '=', $id)
    ->where('active_date', '<=', date('Y-m-d'))
    ->where('inactive_date','>=', date('Y-m-d'))->get();

    $listcheckDataApprovalDetail = array();
    foreach($checkDataApprovalDetail as $row){	
        $arr = explode(',', $row->npk);
        if($arr[0]!=""){
            foreach($arr as $rs){
                $arr_row = explode('-', $rs);
                $listcheckDataApprovalDetail[] = $arr_row[1]."(".$arr_row[0].")";
            }
        }else{
            echo null;
        }
    }
    $listcheckDataApprovalDetail = implode (", ", $listcheckDataApprovalDetail);
    //dd($listcheckDataApprovalDetail);
    /* End checkDataApprovalDetail */

    /* Checkdata */
    $checkData = DB::connection('mysql')->table('cc_master_sub_sub_sub_category AS a')
    ->leftJoin('cc_master_field AS b', 'a.id', '=', 'b.sub_sub_category_id')
    ->leftJoin('cc_user_group AS c', 'a.pic_bucket', '=', 'c.id')
    ->select('a.*', 'b.*', 'c.name as bucket_name')
    ->where('a.id', '=' , $id)
    //->toSql();
    ->get();

    $listCheckData = array();
    if(!empty($checkData)){
        foreach($checkData as $row){	
            $listCheckData[] = array('approval'=>$row->pic_approval, 'bucket'=>$row->pic_bucket, 'isApproval' => '', 'backdesk' => $row->pic_backdesk, 'additional_info' => $row->additional_info, 'is_rroh' => $row->is_rroh, 'bucket_name' => $row->bucket_name);
        }
    }else{
        $listCheckData[] = array('approval'=>'', 'bucket'=>'', 'isApproval'=>'', 'backdesk'=>'', 'additional_info'=>'', 'is_rroh' => '', 'bucket_name' => '');
    }
    
     /* End Checkdata */

    /* getFlexibleField */
    $getFlexibleField = DB::connection('mysql')->table('cc_master_field')
    ->select('label_field', 'input_field')
    ->where('sub_sub_category_id', '=' , $id)
    //->toSql();
    ->get();

    $listgetFlexibleField = array();
    if(!empty($getFlexibleField)){
        
        foreach($getFlexibleField as $row){	
            if(!empty($row->label_field) AND $row->input_field){
                $label_field = explode(';', $row->label_field);
                $input_field = explode(';', $row->input_field);
                if(!empty($label_field) AND !empty($input_field)){
                    foreach($label_field as $k => $r){
                        $listgetFlexibleField[] = array('label'=>$label_field[$k], 'input'=>$input_field[$k]);
                    }
                }
            }
        }
    }else{
        $listgetFlexibleField[] = [];
    }	
    /* End getFlexibleField */

    $allList = [
        'listcheckDataApprovalDetail' => $listcheckDataApprovalDetail,
        'listCheckData' => $listCheckData,
        'listgetFlexibleField' => $listgetFlexibleField
    ];

  
    return response()->json($allList, 200);
});


/**
 * SAVE TICKET
 */
Route::post('saveticket', function(Request $request) {
    $postData = !empty( $request->getContent()) ? json_decode( $request->getContent()) : [];
    $subSubCategory = DB::connection('mysql')->table('cc_master_sub_sub_sub_category')->select(DB::raw("CONVERT(sub_sub_category_id, CHAR) sub_sub_category_id"))->where('id', !empty($postData->selectcoa->id) ?  $postData->selectcoa->id : '')->first();
    $subCategory = DB::connection('mysql')->table('cc_master_sub_sub_category')->select(DB::raw("CONVERT(sub_category_id, CHAR) sub_category_id"))->where('id', $subSubCategory->sub_sub_category_id)->first();
    $category = DB::connection('mysql')->table('cc_master_sub_category')->select(DB::raw("CONVERT(category_id, CHAR) category_id"))->where('id', $subCategory->sub_category_id)->first();	
    $isTicket = DB::connection('mysql')->table('cc_master_category')->select('is_ticket')->where('id', $category->category_id)->first();
    $isApproved = DB::connection('mysql')->table('cc_master_category')->select('is_approved')->where('id', $category->category_id)->first();
    $id = uuid(false);
    $ipAddress = \Request::ip();
    $npk = !empty($postData->inputnpk) ? $postData->inputnpk : '';
    $group_user = !empty($postData->groupid) ? $postData->groupid : '';
    $aproval =  !empty($postData->inputapproveby) ? $postData->inputapproveby : '';
    $npkApproval = preg_match_all("/\((.*?)\)/", $aproval, $matches);
    $npkApproval = !empty($matches[1]) ? str_replace(' ', '', $matches[1])[0] : '';
    $inputPicBucket = !empty($postData->inputpicbucket) ? $postData->inputpicbucket : '';
    $ticket_id = '';

    $data = [
        'id' => $id,
        'npk' => !empty($postData->inputnpk) ? $postData->inputnpk : '',
        'inbound_source' => !empty($postData->optSource) ? $postData->optSource : '',					
        'interaction_category' => $category->category_id,
        'interaction_sub_category' => $subCategory->sub_category_id,
        'interaction_sub_sub_category' => $subSubCategory->sub_sub_category_id,
        'interaction_sub_sub_sub_category' => !empty($postData->selectcoa->id) ? $postData->selectcoa->id : '',
        'description' => !empty($postData->description) ? $postData->description : '',
        'agent_answer' => !empty($postData->inputAgentAnswer) ? $postData->inputAgentAnswer : '',
        'interaction_status' => 'OPEN',
        'request_by' =>!empty($postData->inputCaller) ? $postData->inputCaller : '', 
        'branch' =>!empty($postData->inputbranch) ? $postData->inputbranch : '',
        'workstation_id' => gethostname(),
        'ip_address' => $ipAddress,
        'phone_number' => !empty($postData->inputCallerPhone) ? $postData->inputCallerPhone : '',
        'extension' => !empty($postData->inputExt) ? $postData->inputExt : '',
        'email_address' => !empty($postData->inputemail) ? $postData->inputemail : '',
        'position' => !empty($postData->inputposition) ? $postData->inputposition : '',
        'attachment' => '',
        'sa_name' => '',
        'created_by' => $npk,
        'created_time' => date('Y-m-d H:i:s')
    ];

    $ccInteraction = DB::connection('mysql')->table('cc_interaction')->insert($data);
    if($ccInteraction) {
        Common::dataLogging($ipAddress, 'Customer Interaction', 'INSERT', json_encode($data), $npk);
        // Is Create ticket?
        if($isTicket->is_ticket == '1')
        {
            $row = Common::ticket_increament();
            //dd($row);
            $ticket_id = $row;
            $list = Common::checkUserBackesk();
            $backdesk = Common::roundRobinBackdesk($list);				
            
            $data = array(
                        'ticket_id' => $ticket_id,
                        'interaction_id' => $id,
                        'group_section' => $inputPicBucket,
                        'group_section_status' => 'n/a',								
                        'backdesk' => $backdesk,
                        'backdesk_status' => 'WAIT',								
                        'status' => 'WAPA',
                        'created_by' => $npk,
                        'created_time' => date('Y-m-d H:i:s')
                    );
            $insertTicket = DB::connection('mysql')->table('cc_ticket')->insert($data);
            if($insertTicket > 0){
                Common::dataLogging($ipAddress, 'Ticket Interaction', 'INSERT', json_encode($data), $npk);
            }
            echo $ticket_id;
            
            // check field where sub_sub_category_id
            $checkSubSubCategoryId = DB::connection('mysql')->table('cc_master_field')->select('*')->where('sub_sub_category_id', !empty($postData->selectcoa->id) ? $postData->selectcoa->id : '')->get();
            $countCheckSubSubCategoryId = $checkSubSubCategoryId->count();
            if ($countCheckSubSubCategoryId > 0)
            {
                $rowSubSubCategoryId = DB::connection('mysql')->table('cc_master_field')->select('*')->where('sub_sub_category_id', !empty($postData->selectcoa->id) ? $postData->selectcoa->id : '')->first();
                $field = $rowSubSubCategoryId->input_field;
                $label_field = $rowSubSubCategoryId->label_field;
                $arr_field = explode(';',$field);
                $arr_label_field = explode(';',$label_field);
                $datacc = array();
                for($i=0;$i<count($arr_field);$i++){
                    $string = !empty($arr_field[$i]) ? $arr_field[$i] : '';
                    $detail_field = array(
                                'id' => uuid(false),
                                'ticket_id'  => $ticket_id,
                                'label_name' => !empty($arr_label_field[$i]) ? $arr_label_field[$i] : '',
                                'input_name' => !empty($arr_field[$i]) ? $arr_field[$i] : '',
                                'content'    => !empty($postData->{$string}) ? $postData->{$string} : ''
                            );
                    DB::connection('mysql')->table('cc_interaction_detail_field')->insert($detail_field);
                }						
            }
            // ticket to approved
            if($isApproved->is_approved == '1'){
                // approved by spv
                $arr_approval = array(
                                    'id' => uuid(false),
                                    'ticket_id' => $ticket_id,
                                    'pic_approval' => !empty($npkApproval) ? $npkApproval : '',//$this->input->get_post('inputApproveBy'),
                                    'status' => 'OPEN',
                                    'created_by' => $npk,
                                    'created_time' => date('Y-m-d H:i:s')
                                );
                DB::connection('mysql')->table('cc_ticket_approval')->insert($arr_approval);
                Common::dataLogging($ipAddress, 'Ticket Interaction Approval SPV', 'INSERT', json_encode($arr_approval), $npk);
                // approved by spv
                $arr_approval_coa = explode(',', !empty($postData->optaddapproval) ? $postData->optaddapproval : '');
                foreach($arr_approval_coa as $row_approval){
                    if(!empty($row_approval)){
                        $npkApprovalSpv = preg_match_all("/\((.*?)\)/", $row_approval, $matches2);
                        $npkApprovalSpv = !empty($matches2[1]) ? str_replace(' ', '', $matches2[1])[0] : '';
                        $arr_approval_coa = array(
                                            'id' => uuid(false),
                                            'ticket_id' => $ticket_id,
                                            'pic_approval' => !empty($npkApprovalSpv) ? $npkApprovalSpv : '',
                                            'status' => 'n/a',
                                            'created_by' => $npk,
                                            'created_time' => date('Y-m-d H:i:s')
                                        );
                        DB::connection('mysql')->table('cc_ticket_approval')->insert($arr_approval_coa);
                        Common::dataLogging($ipAddress, 'Ticket Interaction Approval HO', 'INSERT', json_encode($arr_approval_coa), $npk);
                    }
                }
                
                // IS RROH
                $rroh =  DB::connection('mysql')->table('cc_master_sub_sub_sub_category')->select('is_rroh')->where('id', !empty($postData->selectcoa->id) ? $postData->selectcoa->id : '')->first();
                $rrohNpk = DB::connection('mysql')->table('cc_master_rroh')->select('npk')->where('branch', !empty($postData->inputbranch) ? $postData->inputbranch: '')->first();
                if($rroh->is_rroh == "1" && !empty($rrohNpk->npk)){
                    $approvalRroh = array(
                                        'id' => uuid(false),
                                        'ticket_id' => $ticket_id,
                                        'pic_approval' => $rrohNpk,
                                        'status' => 'n/a',
                                        'created_by' => $npk,
                                        'created_time' => date('Y-m-d H:i:s')
                                    );
                    DB::connection('mysql')->table('cc_ticket_approval')->insert($approvalRroh);
                    Common::dataLogging($ipAddress, 'Ticket Approval RROH', 'INSERT', json_encode($approvalRroh), $npk);
                }
            }else{
                $arr_approval = array(
                                    'id' => uuid(false),
                                    'ticket_id' => $ticket_id,
                                    'pic_approval' => 'system',
                                    'status' => 'APTI',
                                    'created_by' => $npk,
                                    'created_time' => date('Y-m-d H:i:s')
                                );
                DB::connection('mysql')->table('cc_ticket_approval')->insert($arr_approval);	
                DB::table('cc_ticket')->where('ticket_id', $ticket_id)->update(['group_section_status' => 'OPEN','status' => 'OPEN','backdesk_status' => 'n/a','status_employee' => 'OPEN']);
                $updateTicketStatus = "update cc_ticket set group_section_status='OPEN',status='OPEN',backdesk_status='n/a',status_employee='OPEN' where ticket_id='".$ticket_id."'";
                Common::dataLogging($ipAddress, 'Update Ticket isApproveal 0', 'UPDATE', json_encode($updateTicketStatus), $npk);
            }
        }else{
            echo "isTicket false";
        }
    }else{
        Common::dataLogging($ipAddress, 'Customer Interaction', 'FAILED INSERT', json_encode($data), $npk);
    }


    //insert into cc_temp_duration
    $interaction_id = DB::connection('mysql')->table('cc_ticket')->select('interaction_id')->where('ticket_id', $ticket_id)->first();
    $userApproval = DB::connection('mysql')->table('cc_ticket_approval')->select(DB::raw("group_concat(pic_approval) as pic_approval"))
                    ->where('ticket_id', $ticket_id)
                    ->where('status', 'OPEN')
                    ->groupBy('ticket_id')
                    ->first();
    if(!empty($userApproval->pic_approval)){
        $arrUA = explode(',',$userApproval->pic_approval);
        foreach($arrUA as $rowToUser){
            $to_user = $rowToUser;
            $groupID = DB::connection('mysql')->table('cc_user')->select('group_id')->where('id', $rowToUser)->first();
            $to_group = $groupID->group_id;
            Common::insertTemp($interaction_id, $ticket_id,$to_group,$to_user, $npk, $group_user);
        }
    }else{
        if($isApproved->is_approved != "1"){
            $ticketStatus = DB::connection('mysql')->table('cc_ticket')->select('status')->where('ticket_id', $ticket_id)->first();
            if($ticketStatus->status == "OPEN"){
                $to_user = null;
                $groupFollowup = DB::connection('mysql')->table('cc_ticket')->select('group_section')->where('ticket_id', $ticket_id)->where('group_section_status', 'OPEN')->first();
                if(!empty($groupFollowup->group_section)){
                    $to_group = $groupFollowup->group_section;
                    Common::insertTemp($interaction_id, $ticket_id,$to_group,$to_user, $npk, $group_user);
                }else{
                    echo " no insert group Temp";
                }
            }else{
                // echo " insert temp close status";
                $to_user = $npk;
                $to_group = $group_user;
                $responseTime = date('Y-m-d H:i:s');
                $assignTime = $this->input->get_post('assign_time');
                Common::insertTemp($interaction_id, $ticket_id,$to_group,$to_user,$responseTime,$assignTime);
            }
        }else{
            echo " no insert temp user appr null";
        }
    }

});

/**
* APPROVAL TICKER  TICKET
 */
Route::post('updateticket', function(Request $request) {
        $postData = !empty( $request->getContent()) ? json_decode( $request->getContent()) : [];
        $delegationUser = DB::connection('mysql')->table('cc_master_delegation_approval')->select('user_id')
                        ->where('delegation_user', !empty($postData->npkUserId) ?  $postData->npkUserId : '')
                        ->where('status', 1)
                        ->where('end_date', '>=', date('Y-m-d'))
                        ->first();
        $ipAddress = \Request::ip();
        $ticketID = !empty($postData->ticketID) ?  $postData->ticketID : '';
        $npkUserId = !empty($postData->npkUserId) ?  $postData->npkUserId : '';
        $groupId = !empty($postData->groupId) ?  $postData->groupId : '';
        $optResponse = !empty($postData->optResponse) ?  $postData->optResponse : '';
        $inputDescription = !empty($postData->inputDescription) ?  $postData->inputDescription : '';

        if(!empty($delegationUser))
        {
            $pickup_time = DB::connection('mysql')->table('cc_ticket_approval')->select('pickup_time')
            ->where('ticket_id',  $ticketID)
            ->where('pic_approval', $npkUserId)
            ->first();
        }else{
            $pickup_time = DB::connection('mysql')->table('cc_ticket_approval')->select('pickup_time')
            ->where('ticket_id',  $ticketID)
            ->where('pic_approval', $npkUserId)
            ->first();
        }

        $data = array(
            'id' => uuid(false),
            'ticket_id' =>  $ticketID,
            'user_group' => $groupId,
            'response_by' => $npkUserId,
            'response_time' => date('Y-m-d H:i:s'),
            'pickup_time' => $pickup_time->pickup_time,
            'response_status' => $optResponse,
            'response_description' => $inputDescription,
            'ip_address' => $ipAddress
        );

        $ccInteraction = DB::connection('mysql')->table('cc_ticket_unit_response')->insert($data);
        // Insert into logging
        Common::dataLogging($ipAddress, 'TICKET APPROVAL', 'INSERT', json_encode($data), $npkUserId);

        $update = DB::connection('mysql')->table('cc_ticket_approval')
            ->where('ticket_id', $ticketID)
            ->where('pic_approval', $npkUserId)
            ->update(['status' => $optResponse, 'response_time' => date('Y-m-d H:i:s')]);
        
        if($update){
            Common::dataLogging($ipAddress, 'TICKET APPROVAL', 'UPDATE', json_encode($data), $npkUserId);
        }

        // If status approve ticket then continue to next approval
		if($optResponse == 'APTI'){
            $update = DB::connection('mysql')->table('cc_ticket_approval')
            ->where('ticket_id', $ticketID)
            ->where('status', 'n/a')
            ->update(['status' => 'OPEN']);
            Common::dataLogging($ipAddress, 'TICKET APPROVAL n/a', 'UPDATE', json_encode($data), $npkUserId);
            // check if all ticket approved, then assign to bucket.
            $arrCheckData = DB::connection('mysql')->table('cc_ticket_approval')->select('status')
            ->where('ticket_id', $ticketID)
            ->whereNotIn('status', ['APTI'])
            ->first();
			if(empty($arrCheckData))
			{
				$last_ticket = 'OPEN';
				// update bucket status became OPEN
                $updateBucket = DB::connection('mysql')->table('cc_ticket')
                ->where('ticket_id', $ticketID)
                ->where('backdesk_status', '!=', 'OPEN')
                ->update(['group_section_status' => 'OPEN', 'status' => 'OPEN']);
				//echo "assign to bucket";
			}else{
				$last_ticket = 'WAIT';
				//echo "Still in approval";
                $updateApprovalStatus = DB::connection('mysql')->table('cc_ticket')
                ->where('ticket_id', $ticketID)
                ->update(['status' => 'WAPH']);
			}
		}else if($optResponse == 'RETI'){
			//echo "Reject ticket";
			$updateReti = DB::connection('mysql')->table('cc_ticket')
                ->where('ticket_id', $ticketID)
                ->update(['status' =>  $optResponse]);
		}else{
			echo "other status condition";
        }
        
        /* SKIP EMAIL */

        //update into cc_temp_duration
		if(!empty($delegationUser))
		{
			$to_user = $delegationUser->user_id;
		}else{
			$to_user = $npkUserId;
        }
        
		$to_group = $groupId;
		$ticket_id = $ticketID;

        $idTemp = DB::connection('mysql')->table('cc_temp_duration')->select('id')
        ->where('ticket_id', $ticketID)
        ->whereNull('to_user')
        ->whereNull('response_time')
        ->where(function($q) use ($to_group, $to_user){
            $q->where('from_group','=', $to_group);
            $q->orWhere('from_user', '=', $to_user);
        })
        ->first();

        $idTempId = !empty($idTemp->id) ?  $idTemp->id : '';
        $updateTemp = Common::updateTemp($ticket_id,$to_group,$to_user, array('response_time' => date('Y-m-d H:i:s')), $idTempId);

		if($updateTemp){
            $interaction_id = DB::connection('mysql')->table('cc_interaction AS a')->join('cc_ticket AS b', 'a.id', '=', 'b.interaction_id')->select('a.id')->where('b.ticket_id', $ticket_id)->first();
            $userApproval = DB::connection('mysql')->table('cc_ticket_approval')->select(DB::raw("group_concat(pic_approval) as group_pic_approval"))->where('ticket_id', $ticket_id)->where('status', '=', 'OPEN')->groupBy('ticket_id')->get();
                if(!empty($userApproval)){
                    $group_pic_approval = !empty($userApproval[0]->group_pic_approval) ? $userApproval[0]->group_pic_approval : '';
                    $arrUA = explode(',',$group_pic_approval);
					foreach($arrUA as $rowToUser){
                        $groupID = DB::connection('mysql')->table('cc_user')->select('group_id')->where('id', $rowToUser)->first();
                        $group_id = !empty($groupID->group_id) ? $groupID->group_id : '';
                        $checkTemp = DB::connection('mysql')->table('cc_temp_duration')->select('assign_time')
                        ->where('interaction_id', !empty($interaction_id->id) ? $interaction_id->id : '')
                        ->where('ticket_id', $ticket_id)
                        ->where('to_user', $rowToUser)
                        ->where('to_group', $group_id )
                        ->first();
						if($checkTemp == ""){
							Common::insertTemp($interaction_id->id, $ticket_id, $group_id, $rowToUser);
						}else{
							echo 'no insert new temp checktemp';
						}
					}
				}else{
                    // echo " no insert temp no approval pic";
                    $toUser = DB::connection('mysql')->table('cc_ticket')->select('pic_group_section')->where('ticket_id', $ticket_id)->first();
                    $toGroup = DB::connection('mysql')->table('cc_ticket')->select('group_section')->where('ticket_id', $ticket_id)->first();
                    $pic_group_section = !empty($toUser->pic_group_section) ? $toUser->pic_group_section : '';
                    $group_section = !empty($toGroup->group_section) ? $toGroup->group_section : '';
                    $checkTemp = DB::connection('mysql')->table('cc_temp_duration')->select('assign_time')
                                ->where('interaction_id', $interaction_id->id)
                                ->where('ticket_id', $ticket_id)
                                ->where('to_user', $toUser->pic_group_section)
                                ->where('to_group', $toGroup->group_section)
                                ->first();
                    if(empty($checkTemp)){
                        Common::insertTemp($interaction_id->id, $ticket_id, $group_section, $pic_group_section);
                    }else{
                        echo 'no insert new temp userappr';
                    }
				}
		}else{
			echo " no update temp";
		}
});


/**
 * GET LIST TICKET
 * http://example.com/custom/url?page=2
 */

Route::get('get_ticket/{id}', function ($id) {
    if(!empty($id)){
        $decrypt = Crypt::decrypt($id);
        $status = "TICKET_STATUS";
        $checkData = DB::connection('mysql')
        ->table('cc_interaction AS a')
        ->join('cc_ticket AS b', 'a.id', '=', 'b.interaction_id')
        ->join('cc_master_sub_sub_sub_category AS i', 'a.interaction_sub_sub_sub_category', '=', 'i.id')
        ->join('cc_master_reference AS g', 'g.ref', '=', 'b.status')
        ->join('cc_ticket_approval AS app', 'app.ticket_id', '=', 'b.ticket_id')
        ->join('cc_master_reference AS j', 'j.ref', '=', 'app.status')
        ->leftJoin('cc_customer AS k', 'k.npk', '=', 'app.pic_approval')
        ->select(
            'a.created_time',
            'b.ticket_id',
            'b.group_section', 
            'i.name',
            'b.status',
            'g.code'
        )
        ->where('a.npk', $decrypt)//->where('g.id', $status)->where('j.id', $status)
        ->groupBy('app.ticket_id')
        ->orderBy('a.created_time', 'DESC')
        //->toSql();
        ->paginate(5);
        return json_encode($checkData);
        //->get();
        //var_dump($checkData->toSql(), $checkData->getBindings());
    }
});

/**
 * GET LIST TICKET
 * http://example.com/custom/url?page=2
 */

Route::get('get_detail_ticket/{id}', function ($id) {
    if(!empty($id)){
        $detailTicket = DB::connection('mysql')
        ->table('cc_interaction AS a')
        ->join('cc_ticket AS b', 'a.id', '=', 'b.interaction_id')
        ->join('cc_master_category AS c', 'a.interaction_category', '=', 'c.id')
        ->join('cc_master_sub_category AS d', 'a.interaction_sub_category', '=', 'd.id')
        ->join('cc_master_sub_sub_category AS e', 'a.interaction_sub_sub_category', '=', 'e.id')
        ->join('cc_master_sub_sub_sub_category AS g', 'a.interaction_sub_sub_sub_category', '=', 'g.id')
        ->join('cc_customer AS f', 'a.npk', '=', 'f.npk')
        ->select(
            'a.id', 'a.npk', 'a.description', 'a.agent_answer', 'a.request_by', 
            'a.branch', 'a.workstation_id', 'a.ip_address', 'a.phone_number', 'a.extension', 
            'a.email_address', 'a.position', 'a.attachment', 
            'b.ticket_id', 'c.name as category', 'd.name as sub_category', 
            'e.name as sub_sub_category',
            'g.name as sub_sub_sub_category',
            'f.customer_name','a.attachment', 
            'a.flexible_field', 'a.description'
        )
        ->where('b.ticket_id', $id)//->where('g.id', $status)->where('j.id', $status)
        ->orderBy('a.created_by')
        ->first();

        $listApproval = DB::connection('mysql')
        ->table('cc_ticket_approval AS a')
        ->join('cc_customer AS b', 'a.pic_approval', '=', 'b.npk')
        ->join('cc_master_reference AS c', 'a.status', '=', 'c.ref')
        ->leftJoin('cc_ticket_unit_response AS d', function($join){
            $join->on('a.pic_approval','=','d.response_by'); // i want to join the users table with either of these columns
            $join->on('a.ticket_id','=','d.ticket_id');
        })
        ->select(
            'a.ticket_id', 'a.pic_approval', 'b.customer_name', 'a.status', 'c.code', 'a.response_time', 'd.response_description'
        )
        ->where('a.ticket_id', $id)
        ->get();

        $status = "TICKET_STATUS";
        $listApprovalTimeFrame = DB::connection('mysql')
        ->table('cc_ticket_unit_response AS a')
        ->join('cc_master_reference AS b', 'a.response_status', '=', 'b.ref')
        ->join('cc_customer AS c', function($join){
            $join->on('a.response_by','=','c.npk'); // i want to join the users table with either of these columns
            $join->orOn('a.response_by','=','c.customer_name');
        })
        ->select(
            'a.ticket_id', 'a.response_by as pic_approval', 'c.customer_name', 'b.code as status', 'b.code', 
            'a.response_time', 'a.currative_solution AS response_description'
        )
        ->where('b.id', $status)
        ->where('a.ticket_id', $id)
        ->orderBy('a.response_time', 'DESC')
        ->get();

        $collection  = new Collection;
        $data = [];
        if(!empty($listApprovalTimeFrame)) {
            foreach($listApproval as $key => $value){
                foreach($listApprovalTimeFrame as $k => $v){
                    if(!empty($value->pic_approval) AND !empty($v->pic_approval)){
                        if($value->pic_approval === $v->pic_approval){
                            unset($listApprovalTimeFrame[$k]);
                        }
                    }
                }
            }
            $all = $collection->merge($listApproval)->merge($listApprovalTimeFrame);
        }

        $ticket = [
            'detailTicket' => $detailTicket,
            'detailApproval' => $all
        ];

        return json_encode($ticket);                     
    }
});


/**
 * GET LIST APPROVAL
 * http://example.com/custom/url?page=2
 */
Route::get('get_approval/{id}', function ($id) {
    if(!empty($id)){
        $decrypt = Crypt::decrypt($id);
        $status = "TICKET_STATUS";
        $checkData = DB::connection('mysql')
        ->table('cc_interaction AS a')
        ->join('cc_ticket AS b', 'a.id', '=', 'b.interaction_id')
        ->join('cc_ticket_approval AS h', 'b.ticket_id', '=', 'h.ticket_id')
        ->join('cc_master_sub_sub_sub_category AS i', 'a.interaction_sub_sub_sub_category', '=', 'i.id')
        ->join('cc_customer AS f', 'a.npk', '=', 'f.npk')
        ->join('cc_master_reference AS g', 'h.status', '=', 'g.ref')
        ->select(
            'a.id', 'a.npk', 'a.inbound_source', 'a.description',
            'a.agent_answer', 'a.request_by', 'a.branch', 'a.workstation_id',
            'a.ip_address', 'a.phone_number', 'a.extension', 'a.email_address',
            'a.position', 'a.created_by', 'a.created_time', 'b.ticket_id', 'b.priority',
            'b.backdesk', 'b.backdesk_status', 'b.pic_group_section', 'b.group_section_status',
            'a.interaction_category AS category', 'a.interaction_sub_category AS sub_category',
            'a.interaction_sub_sub_category AS sub_sub_category', 'i.name AS sub_sub_sub_category',
            'f.customer_name', 'g.code', 'b.status', 'a.created_time AS duration', 'a.attachment', 'a.interaction_sub_sub_sub_category',
            DB::raw("TIMEDIFF(NOW(), a.created_time) AS duration")
        )
        ->where('h.pic_approval', $decrypt)->where('g.id', $status)
        ->whereNotIn('h.status', ['APTI', 'RETI', 'EXAP', 'n/a'])
        ->orderBy('a.created_time', 'DESC')
        //->toSql();
        ->paginate(5);
        return json_encode($checkData);;
    }
});



/**
 * GET LIST TICKET
 * http://localhost:8080/itcare_acc_dev/ticket/ticket_approval/approvalResponse/?id=267deb445cb760e44d5a79ce12ff99c7
 */

Route::get('get_detail_approval/{id}', function ($id) {
    if(!empty($id)){
        $detailTicket = DB::connection('mysql')
        ->table('cc_interaction AS a')
        ->join('cc_ticket AS b', 'a.id', '=', 'b.interaction_id')
        ->join('cc_master_category AS c', 'a.interaction_category', '=', 'c.id')
        ->join('cc_master_sub_category AS d', 'a.interaction_sub_category', '=', 'd.id')
        ->join('cc_master_sub_sub_category AS e', 'a.interaction_sub_sub_category', '=', 'e.id')
        ->join('cc_master_sub_sub_sub_category AS g', 'a.interaction_sub_sub_sub_category', '=', 'g.id')
        ->join('cc_customer AS f', 'a.npk', '=', 'f.npk')
        ->select(
            'a.id', 'a.npk', 'a.description', 'a.agent_answer', 'a.request_by', 
            'a.branch', 'a.workstation_id', 'a.ip_address', 'a.phone_number', 'a.extension', 
            'a.email_address', 'a.position', 'a.attachment', 
            'b.ticket_id', 'c.name as category', 'd.name as sub_category', 
            'e.name as sub_sub_category',
            'g.name as sub_sub_sub_category',
            'f.customer_name','a.attachment', 
            'a.flexible_field'
        )
        ->where('b.ticket_id', $id)//->where('g.id', $status)->where('j.id', $status)
        ->orderBy('a.created_by')
        ->first();

        //$this->cockpit_model->getFlexibleField($this->session->userdata('CURRENT_TICKET_ID'));
        $flexibleInput = DB::connection('mysql')
        ->table('cc_interaction_detail_field AS a')
        ->join('cc_ticket AS b', 'b.ticket_id', '=', 'a.ticket_id')
        ->select('a.ticket_id','label_name', 'input_name','content')
        ->where('a.ticket_id', $id)//->where('g.id', $status)->where('j.id', $status)
        ->get();

        // $status = "TICKET_STATUS";
        // $listApproval = DB::connection('mysql')
        // ->table('cc_ticket_unit_response AS a')
        // ->join('cc_master_reference AS b', 'a.response_status', '=', 'b.ref')
        // ->join('cc_customer AS c', function($join){
        //     $join->on('a.response_by','=','c.npk'); // i want to join the users table with either of these columns
        //     $join->orOn('a.response_by','=','c.customer_name');
        // })
        // ->select(
        //     'a.id', 'a.ticket_id', 'a.user_group',
        //     DB::raw('CONCAT(c.customer_name, "(", a.response_by, ")") response_by'),
        //     'a.response_time', 'a.currative_solution AS response_description', 'a.attachment',
        //     'b.code', 
        //     DB::raw('"" AS assign_time'), DB::RAW('"" AS duration')
        // )
        // ->where('b.id', $status)
        // ->where('a.ticket_id', $id)
        // ->orderBy('a.response_time')
        // ->get();
        $listApproval = DB::connection('mysql')
        ->table('cc_ticket_approval AS a')
        ->join('cc_customer AS b', 'a.pic_approval', '=', 'b.npk')
        ->join('cc_master_reference AS c', 'a.status', '=', 'c.ref')
        ->leftJoin('cc_ticket_unit_response AS d', function($join){
            $join->on('a.pic_approval','=','d.response_by'); // i want to join the users table with either of these columns
            $join->on('a.ticket_id','=','d.ticket_id');
        })
        ->select(
            'a.ticket_id', 'a.pic_approval', 'b.customer_name', 'a.status', 'c.code', 'a.response_time', 'd.response_description'
        )
        ->where('a.ticket_id', $id)
        ->get();

        $ticket = [
            'detailTicket' => $detailTicket,
            'flexibleInput' => $flexibleInput,
            'detailApproval' => $listApproval
        ];

        return json_encode($ticket);                     
    }
});

/**
 * GET LIST CATEGORY QUESTION
 */
Route::get('get_help_category', function () {
    $sub_category = Faqs::select('sub_category_id', 'sub_category_name as text_label')->where('category_id', '<>' , 0)->orderBy('sub_category_name')->groupBy('sub_category_name')->get();
    return json_encode($sub_category);
});

Route::get('get_help_sub_category/{id}', function ($id) {
    if($id === 'default'){
        $sub_sub_category = Faqs::select('sub_sub_category_id', 'sub_sub_category_name as text_label')->orderBy('sub_sub_category_name')->groupBy('sub_sub_category_name')->get();
    }else{
        $sub_sub_category = Faqs::select('sub_sub_category_id', 'sub_sub_category_name as text_label')->where('sub_category_id', '=' , $id)->orderBy('sub_sub_category_name')->groupBy('sub_sub_category_name')->get();
    } 
    return json_encode($sub_sub_category);
});

Route::get('get_help_sub_sub_category/{id}', function ($id) {
    $sub_sub_category_question = Faqs::select('id', 'question_Category as text_label')->where('sub_sub_category_id', '=' , $id)->orderBy('question_Category')->get();
    return json_encode($sub_sub_category_question);
});

Route::get('get_help_answer_question_category/{id}', function ($id) {
    $answer_category = Faqs::select('question_category', 'answer_category as text_label')->where('id', '=' , $id)->get();
    return json_encode($answer_category);
});

Route::get('get_all_answer', function (Request $request) {
    $get = $request->input('keyword');
    $question_Category = Faqs::select('id', 'question_Category as text_label')->where('question_Category', 'like', "%$get%")->get();
    return json_encode($question_Category);
});


/**
 * GET LIST APPROVAL
 * http://example.com/custom/url?page=2
 */
Route::get('list_blog', function () {
    $checkData = DB::connection('mysql')
    ->table('mobile_posts AS a')
    ->join('mobile_categories AS b', 'a.category_id', '=', 'b.id')
    ->select(
        'a.id', 'a.image_url', 'a.title', 'a.post_body', 'b.name as category', 'a.is_published', 'a.created_at'
    )
    ->where('a.is_published', 'Yes')
    ->paginate(10);
    
    if(!empty($checkData)){
        //dd($checkData);
        foreach($checkData as $key => $value){
            $tanggal = $value->created_at;
            $hari    = date('l', microtime($tanggal));
            $hari_indonesia = array(
                                'Monday'  => 'Senin',
                                'Tuesday'  => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu',
                                'Sunday' => 'Minggu'
                            );
            $phpdate = strtotime( $value->created_at );
            $page = !empty($_GET['page']) ? $_GET['page'] :  0;
            if($page == 1 AND $key == 0){
                $checkData[$key]->post_body = limit_words($value->post_body, 20) .' ..';
            }else{
                $checkData[$key]->post_body = limit_words($value->post_body, 10) .' ..';  
            }
            $checkData[$key]->image_url = url('/images/' . $value->image_url . '');
            $checkData[$key]->created_at = $hari_indonesia[$hari] .", ". date("d M Y H:i", $phpdate) . ' WIB';
        }
    }
    return json_encode($checkData);
});

Route::get('get_detail_blog/{id}', function ($id) {
    if(!empty($id)){
        $checkData = DB::connection('mysql')
        ->table('mobile_posts AS a')
        ->join('mobile_categories AS b', 'a.category_id', '=', 'b.id')
        ->select(
            'a.id', 'a.image_url', 'a.title', 'a.post_body', 'b.name as category', 'a.is_published', 'a.created_at'
        )->where('a.id', $id)->get();
        if(!empty($checkData)){
            $tanggal = $checkData[0]->created_at;
            $hari    = date('l', microtime($tanggal));
            $hari_indonesia = array(
                                'Monday'  => 'Senin',
                                'Tuesday'  => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu',
                                'Sunday' => 'Minggu'
                            );
            $phpdate = strtotime( $checkData[0]->created_at );
            $checkData[0]->image_url = url('/images/' . $checkData[0]->image_url . '');
            $checkData[0]->created_at = $hari_indonesia[$hari] .", ". date("d M Y H:i", $phpdate) . ' WIB';
            return json_encode($checkData);
        }
    }
});

function limit_words($string, $word_limit)
{
    $words = explode(" ", strip_tags($string));
    return implode(" ", array_splice($words,0,$word_limit));
}

function uuid($hyphen = true) {
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

/*
SELECT * FROM cc_ticket WHERE created_by = '13828' 
SELECT * FROM cc_interaction WHERE created_by = '13828' 
SELECT * FROM cc_ticket_approval WHERE created_by = '13828'

DELETE FROM cc_ticket WHERE created_by = '13828' 
DELETE FROM cc_interaction WHERE created_by = '13828' 
DELETE FROM cc_ticket_approval WHERE created_by = '13828' 
*/