<?php

namespace App\Helpers;


class Helpers
{
    public static function send_push_notif_to_device($fcm_token, $data, $web_push_link = null)
    {
        $key = "AAAA1Oc7WKE:APA91bHjlSBeOAMVUYCZt6cM9XbB1NCyBt4EZC-ocVdnTBMtLLwxMxg_fbrBo1S0uDoPZd3G5CYgo8CRSPUDFMvQT58e-lCzgFirHcQeV0FzAsMkX3ydZz_iFHpjkg05MjKwVEoXK_KR";
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );


        if(isset($data['message'])){
            $message = $data['message'];
        }else{
            $message = '';
        }
        if(isset($data['conversation_id'])){
            $conversation_id = $data['conversation_id'];
        }else{
            $conversation_id = '';
        }
        if(isset($data['sender_type'])){
            $sender_type = $data['sender_type'];
        }else{
            $sender_type = '';
        }
        if(isset($data['module_id'])){
            $module_id = $data['module_id'];
        }else{
            $module_id = '';
        }
        if(isset($data['order_type'])){
            $order_type = $data['order_type'];
        }else{
            $order_type = '';
        }

        $click_action = "";
        if($web_push_link){
            $click_action = ',
            "click_action": "'.$web_push_link.'"';
        }

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "mutable_content": true,
            "data" : {
                "title":"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "type":"' . $data['type'] . '",
                "conversation_id":"' . $conversation_id . '",
                "sender_type":"' . $sender_type . '",
                "module_id":"' . $module_id . '",
                "order_type":"' . $order_type . '",
                "is_read": 0
            },
            "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "title_loc_key":"' . $data['order_id'] . '",
                "body_loc_key":"' . $data['type'] . '",
                "type":"' . $data['type'] . '",
                "is_read": 0,
                "icon" : "new",
                "sound": "notification.wav",
                "android_channel_id": "6ammart"
                '.$click_action.'
            }
        }';
        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

}
