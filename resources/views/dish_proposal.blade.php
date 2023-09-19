<!DOCTYPE html>
<html>
<head>
    <title>ChatGPT</title>
</head>
<body>
    <h1>ChatGPT</h1>
    <form method="POST" action="/chat">
        @csrf
        <label for="sentence">食材を入力してください:</label>
        <input type="text" name="sentence" id="sentence">
        <button type="submit">送信</button>
    </form>
    
    @isset($sentence)
        <h2>入力文章:</h2>
        <p>{{ $sentence }}</p>
    @endisset
    
    @isset($chat_response)
        <h2>ChatGPTの応答:</h2>
        <p>{{ $chat_response }}</p>
    @endisset
</body>
</html>
