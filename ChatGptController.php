<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatGptController extends Controller
{

    /**
     * index
     *
     * @param  Request  $request
     */
    public function index(Request $request)
    {
        return view('dish_proposal');
    }

    /**
     * chat
     *
     * @param  Request  $request
     */
    public function chat(Request $request)
    {
        // $sentence[] = '';
        // バリデーション
        $request->validate([
            //'sentence' => 'required|array',
            'sentence1' => 'required',
            'sentence2' => 'required',
            'sentence3' => 'required',
        ], [
            // カスタムエラーメッセージ
            'sentence1.required' => '食材を入力してください',
            'sentence2.required' => '食材を入力してください',
            'sentence3.required' => '食材を入力してください',
            
        ]);

        // 食材
        $sentence1 = $request->input('sentence1');
        $sentence2 = $request->input('sentence2');
        $sentence3 = $request->input('sentence3');

        // ChatGPT API処理
        $chat_response = $this->chat_gpt("これらの食材でできる料理名だけを３つ日本語で応答してください", $sentence1, $sentence2, $sentence3);

        return view('dish_proposal', compact('sentence1', 'sentence2', 'sentence3', 'chat_response'));
    }

    /**
     * ChatGPT API呼び出し
     * ライブラリ
     */
    function chat_gpt($system, $user)
    {

        // APIキー
        $api_key = env('CHAT_GPT_KEY');

        // パラメータ
        $data = array(
            "model" => "gpt-3.5-turbo",
            "messages" => [
                [
                    "role" => "system",
                    "content" => $system
                ],
                [
                    "role" => "user",
                    "content" => $user
                ]
            ]
        );

        // APIにリクエストを送信
        $openaiClient = \Tectalic\OpenAi\Manager::build(
            new \GuzzleHttp\Client(),
            new \Tectalic\OpenAi\Authentication($api_key)
        );

        try {

            $response = $openaiClient->chatCompletions()->create(
                new \Tectalic\OpenAi\Models\ChatCompletions\CreateRequest($data)
            )->toModel();

            return $response->choices[0]->message->content;
        } catch (\Exception $e) {
            return "ERROR";
        }
    }
}
