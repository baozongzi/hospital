<?php
namespace JokeChat\Util;
/**
 * 日志操作操作
 * @description
 * @author jokechat
 * @date 2016年3月26日
 * @mail  jokechat@qq.com
 */
class Log
{
	
	private static $base_dir 	= "./";//应用基础路径
	
	private static $log_path 	= 'log/';//日志路径
	
    public static function i($msg,$filename="mx")
    {
    	if (is_array($msg))
    	{
    		$msg 	= json_encode($msg,JSON_UNESCAPED_UNICODE);
    	}
        self::write('I', $msg,$filename);
    }
    
    public static function e($msg,$filename="mx")
    {
    	if (is_array($msg))
    	{
    		$msg 	= json_encode($msg,JSON_UNESCAPED_UNICODE);
    	}
        self::write('E', $msg,$filename);
    }
    
    private static function write($level, $msg,$filename="mx")
    {
        $logDir = self::$base_dir.self::$log_path;
        if (!file_exists($logDir)) {
    	    mkdir($logDir);
    	}
    	
    	$filename 	= $filename.date("Ymd").".log";
        $logFile = fopen($logDir . $filename, "aw");
        fwrite($logFile, $level . "/" . date(" Y-m-d H:i:s") . "  " . $msg . "\n");
        fclose($logFile);    
    }
}
?>