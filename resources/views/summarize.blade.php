<!-- resources/views/summarize.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Summarization</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Text Summarization</h1>
        <form method="POST" action="/summarize">
            @csrf
            <div class="form-group">
                <label for="text">Enter Text to Summarize:</label>
                <textarea class="form-control" id="text" name="text" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Summarize</button>
        </form>

        @if (isset($summary))
            <div class="mt-4">
                <h3>Summary:</h3>
                <p>{{ $summary }}</p>
            </div>
        @endif
    </div>
</body>
</html>
