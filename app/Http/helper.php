<?php 
use App\Models\Settings;
if(!function_exists('get_setting'))
{
    function get_setting($key,$group='general')
    {
        $result = Settings::where('name',$key)->where('group',$group)->first('payload');
        if($result != null){
            return str_replace('"', '', $result->payload);;
        }else{
            return 0;
        }
      
    }
}