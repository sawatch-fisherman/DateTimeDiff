<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimeDiffController extends Controller
{
    /**
     * 入力値から現在との差分を出力する
     *
     * @param Request $request
     * @param [type] $inputDateTime (例：202501161529)
     * @return json
     */
    public function DiffYearMonthDateTimeMinutes(Request $request, $inputDateTime)
    {
        // クライアントのIPアドレスを取得
        $clientIp = $request->ip();

        // 引数とIPアドレスをログに記録
        Log::info('API Called: DiffYearMonthDateTimeMinutes', [
            'input' => $inputDateTime,
            'client_ip' => $clientIp
        ]);

        // 差分を計算する
        $timezone = new DateTimeZone('Asia/Tokyo');
        $inputDate = DateTime::createFromFormat('YmdHi', $inputDateTime, $timezone);
        $now = new DateTime('now', $timezone);

        if ($inputDate < $now) {
            return response()->json(['error' => '指定された日時は過去です。'], 400);
        }

        $interval = $now->diff($inputDate);

        return response()->json([
            'year' => $interval->format('%Y'),
            'month' => $interval->format('%m'),
            'day' => $interval->format('%d'),
            'hour' => $interval->format('%H'),
            'minutes' => $interval->format('%I'),
            'days' => $interval->days + 1,
        ]);
    }
}
