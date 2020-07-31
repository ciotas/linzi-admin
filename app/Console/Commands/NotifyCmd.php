<?php

namespace App\Console\Commands;

use App\Models\Notify;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:it';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '间隔通知时间';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notifys = Notify::where('switch', true)->get();
        foreach ($notifys as $notify)
        {
            $now_minute =  Carbon::now()->minute;
            if ( ($now_minute + $notify->duration) % $notify->duration == 0) {
                $this->pushMessage($notify->duration, $notify->user_id);
            }
        }
    }

    private function pushMessage($duration, $user_id)
    {
        $mobile = User::find($user_id)->mobile;
        $api = 'https://oapi.dingtalk.com/robot/send?access_token=9f809246656a6a8af7fff296986960a92fd0ace31ebef24ff44e467707a8be3d';
        $body = [
            'msgtype' => 'text',
            'text' => [
                'content' => $duration.'分钟间隔时间已到，请留意价格！'
            ],
            'at' => [
                'atMobiles' => [
                    $mobile
                ],
                "isAtAll" => false
            ]

        ];
        $data_string = \GuzzleHttp\json_encode($body);

        $result = $this->request_by_curl($api, $data_string);
        return $result;

    }

    private function request_by_curl($remote_server, $post_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
