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
            <input type="text" class="form-control" name="sentence1" value="{{ isset($sentence1) ? $sentence1 : '' }}">
        </div>
        <div>
            <input type="text" class="form-control" name="sentence2" value="{{ isset($sentence2) ? $sentence2 : '' }}">
        </div>
        <div>
            <input type="text" class="form-control" name="sentence3" value="{{ isset($sentence3) ? $sentence3 : '' }}">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
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
