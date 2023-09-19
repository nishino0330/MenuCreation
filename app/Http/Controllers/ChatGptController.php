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
     * 料理提案を生成するメソッド
     */
    private function generateDishProposal($ingredients)
    {
        // 食材を含むリクエストを作成
        $system = "以下の食材で作れる料理を教えてください: " . implode(", ", $ingredients);
        $user = "";

        // ChatGPT API呼び出し
        $chat_response = $this->chat_gpt($system, $user);

        // API応答から料理提案を抽出
        $proposedDish = "";
        if ($chat_response != "ERROR") {
            $proposedDish = $chat_response;
        }

        return $proposedDish;
    }

    public function proposeDish(Request $request)
    {
        // バリデーション
        $request->validate([
            'ingredients' => 'required',
        ], [
            'ingredients.required' => '食材を入力してください',
        ]);

        // 入力された食材を取得
        $ingredients = explode(",", $request->input('ingredients'));

        // 料理提案を生成
        $proposedDish = $this->generateDishProposal($ingredients);

        // ビューにデータを渡して表示
        return view('dish_proposal', compact('ingredients', 'proposedDish'));
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
    $data = [
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
    ];

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $api_key,
        ])->post('https://api.openai.com/v1/engines/gpt-3.5-turbo/completions', $data);

        // APIからの応答をデバッグ出力
        dump("APIからの応答:");
        dump($response->json()); // 応答データを取得してデバッグ出力

        // APIからのレスポンスからメッセージの内容を抽出
        if (isset($responseJson['choices'][0]['message']['content'])) {
            return $responseJson['choices'][0]['message']['content'];
        } else {
            return "APIからの無効な応答";
        }

    } catch (\Exception $e) {
        // エラーメッセージを表示
        return "APIエラー: " . $e->getMessage();
    }
    }

}
