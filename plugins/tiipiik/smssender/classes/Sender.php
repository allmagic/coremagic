<?php namespace Tiipiik\SmsSender\Classes;

use Exception;
use Tiipiik\SmsSender\Models\Setting;
use Tiipiik\SmsSender\Models\MessageHistory;

class Sender
{   
    /*
     * Send SMS trough provider
     * @param integer the phone number to send the message
     * @param string the test message to send
     * return bool
     *
     */    
    public static function sendMessage($to, $message)
    {
        /* Find settings for provider datas */
        $gateway = Setting::get('gateway');
        $from = Setting::get('from'); // Need to change that name 
        
        if ($gateway == 'clickatell')
        {
            $providerUsername = Setting::get('clickatell_user_name');
            $providerPasswd = Setting::get('clickatell_passwd');
            $providerApiId = Setting::get('clickatell_api_id');
            $baseUrl = Setting::get('clickatell_base_url');
            $sessId = '';
            $codeStatus = '';
            $text = $message;
            $text = self::hex_chars($text);
            $text = urlencode($text);
            
            // auth call
            $url = $baseUrl.'/http/auth?user='.$providerUsername.'&password='.$providerPasswd.'&api_id='.$providerApiId;
            
            // do auth call
            $ret = file($url);
         
            // explode our response. return string is on first line of the data returned
            $sess = explode(":",$ret[0]);
            //echo '<pre>';
                //var_dump($sess);
            //echo '</pre>';
            
                $url = $baseUrl.'/http/sendmsg?session_id='.$sessId.'&to='.$to.'&unicode=1&text='.$text;
                //echo '<br>URL : '.$url;
            //die;
            $status = '';
            
            if ($sess[0] == 'OK') {
                $sessId = trim($sess[1]); // remove any whitespace
                //$url = $baseUrl.'/http/sendmsg?session_id='.$sessId.'&to='.$to.'&text='.$text;
                $url = "$baseUrl/http/sendmsg?session_id=$sessId&to=$to&unicode=1&text=$text";
                //echo '<br>URL : '.$url;
                //die;

                // do sendmsg call
                $ret = file($url);
                $send = explode(":",$ret[0]);
        
                if ($send[0] == "ID") {
                    // All is fine, the message is sent
                    $codeStatus = 1;
                    $status = 'Sent';
                    $sessId = $send[1];
                    
                } else {
                    // Hum, sending failed
                    $codeStatus = 2;
                    $status = $ret[0];
                    //echo '<pre>';
                        //var_dump($ret);
                }
            } else {
               // Authentication failure, got to check Api Id, user credentials and co.
                $codeStatus = 3;
                $status = $ret[0];
            }
            // Store datas in table
            $messageDatas = [
                'from' => $from,
                'to' => $to,
                'sess_id' => $sessId,
                'message' => $message,
                'status' => $status,
                'short_status' => (int) $codeStatus,
            ];
            MessageHistory::saveHistory($messageDatas);
            //die('<br>Code : '.$codeStatus);
        }
        ($codeStatus == 1 ? true : false);
    }
    
    /*
     * Get all messages
     * @param int number of days to get messages
     * @param int status of messages (display only sent or failed messages)
     *
     * return array messages
     *
     */
    public static function getMessages($days, $rows, $status)
    {
        // Convert days to correct date formatting
        $fromDays = date("Y-m-d 00:00:01", time() - (1 * ($days * 24) * 60 * 60));
        
        $messages = MessageHistory::where('created_at', '>', $fromDays);
        if ($status != 0)
            $messages = $messages->where('short_status', '!=', $status);
        $messages = $messages->orderBy('created_at', 'desc')
            ->take($rows)
            ->get();
        
        return $messages;
    }
    
    /*
     * Transform text to unicode usable by Clickatell, witch seems to use specific unicode
     * @param string text to convert
     * @return string text converted
     *
     * Find on Internet, but I have lost the page.
     */
    public static function hex_chars($data){
        $mb_hex = '';
        for($i = 0 ; $i<mb_strlen($data,'UTF-8') ; $i++){
            $c = mb_substr($data,$i,1,'UTF-8');
            $o = unpack('N',mb_convert_encoding($c,'UCS-4BE','UTF-8'));
            $mb_hex .= sprintf('%04X',$o[1]);
        }
        return $mb_hex;
    }
}