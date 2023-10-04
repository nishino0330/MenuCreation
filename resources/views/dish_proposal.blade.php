<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8' />
</head>

<body>
    {{-- フォーム --}}
    <form method="POST">
        @csrf
        <div>
            <input type="text" class="form-control" name="sentences[]" value="{{ isset($sentences[0]) ? $sentences[0] : '' }}">
        </div>
        <div>
            <input type="text" class="form-control" name="sentences[]" value="{{ isset($sentences[1]) ? $sentences[1] : '' }}">
        </div>
        <div>
            <input type="text" class="form-control" name="sentences[]" value="{{ isset($sentences[2]) ? $sentences[2] : '' }}">
        </div>
        <button type="submit" class="btn btn-primary">submit</button>
        </form>

    
    {{-- エラーメッセージ --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- 結果 --}}
    {{ isset($chat_response) ? $chat_response : '' }}
</body>

</html>
