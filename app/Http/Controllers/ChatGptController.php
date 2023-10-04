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
        // バリデーション
        $request->validate([
            'sentences' => 'required|array|min:3',
            'sentences.*' => 'required',
            
        ], [
            // カスタムエラーメッセージ
            'sentences.required' => '食材を入力してください',
            'sentences.min' => '少なくとも３つの食材を入力してください',
        ]);

        // 食材
        $sentences = $request->input('sentences');

        // ChatGPT API処理
        $chat_response = $this->chat_gpt("これらの食材でできる料理名だけを３つ日本語で応答してください", $sentences);

        return view('dish_proposal', compact('sentences', 'chat_response'));
    }

    /**
     * ChatGPT API呼び出し
     * ライブラリ
     */
    function chat_gpt($system, $sentences)
    {
        // APIキー
        $api_key = env('CHAT_GPT_KEY');

        // パラメータ
        $messages = [
            [
                "role" => "system",
                "content" => $system
            ]
        ];

        // ユーザーからの各食材メッセージを追加
        foreach ($sentences as $sentence) {
            $messages[] = [
                "role" => "user",
                "content" => $sentence
            ];
        }

        $data = [
            "model" => "gpt-3.5-turbo",
            "messages" => $messages
        ];

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
