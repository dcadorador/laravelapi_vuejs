<?php
namespace App\Traits;

use App\Models\DebugLogs;
use Log;

trait LoggerTrait
{

    protected function writeLog($title, $message, $type)
    {
        $log = '';

        // adds integrations id if exist
        if (isset($this->integration)) {
            $log .= '[INTEG_' . $this->integration->id . ']';
        }

        // if there is a log tag then use it
        if (defined('self::TAG')) {
            $log .= self::TAG;
        }

        $log .= " $title";

        if (is_string($message)) {
            $log .= empty($message) ? '' : ": $message";
        } else {
            $log .= ": " . json_encode($message);
        }

        switch ($type) {
            case 'info':
                Log::info($log);
                break;
            case 'error':
                Log::error($log);
                break;
            case 'warning':
                Log::warning($log);
                break;
            case 'debug':
                Log::debug($log);
                break;
        }
    }

    protected function errorLog($title, $message = "")
    {
        $this->writeLog($title, $message, 'error');
    }

    protected function infoLog($title, $message = "")
    {
        $this->writeLog($title, $message, 'info');
    }


    protected function debugLog(int $step, $message = "Saved", array $data = [])
    {

        $data['sync_step'] = $step;

        $result = DebugLogs::create($data);
        $message = "$message Debug Log id => " . $result->id;
        $title = "[Workflow - $step]";
        // commented this out for removal in log file
        // $this->writeLog($title, $message, 'debug');
    }
}
