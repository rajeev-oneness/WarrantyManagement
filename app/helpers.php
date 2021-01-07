<?php 

	function sendResponse($message = '',$data = []){
		return response()->json(['error'=>false,'message'=>$message,'data'=>$data]);
	}

	function errorResponse($message = '',$data = []){
		return response()->json(['error'=>true,'message'=>$message,'data'=>$data]);
	}

	function sendMailAllInOne($data, $template = '')
	{
		Mail::send($template, ['data' => $data] , function($message) use ($data) {
			$message->to($data["to"],$data["name"]);
			$message->subject($data["subject"]);
			$message->from('rajeev@onenesstechs.in','Warranty Management');
		});
	}

	function sendOTPonMobile($numbers,$msg)
	{
        // $data = array(
        //     'apikey' => urlencode('yB1VeF5bSqw-EDB6iusfocR0k0J9UYeMgpQnAFEkxP'),
        //     'numbers' => $numbers,
        //     "sender" => urlencode('BIMAST'),
        //     "message" => rawurlencode($msg)
        // );
        // $ch = curl_init('https://api.textlocal.in/send/');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($ch);
        // curl_close($ch);
        // return $response;
	}

?>