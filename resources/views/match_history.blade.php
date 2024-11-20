<!-- resources/views/match_history.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3v3 Match History Input</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body>
    <div class="container">
        <h1>3v3 Match History Input</h1>
        <form action="{{ route('matches.submit') }}" method="POST">
            @csrf

            <label for="class">Select Class:</label>
            <select name="class" id="class" required>
                <option value="" disabled selected>Select your class</option>
                <option value="Druid (Resto)">Druid (Resto)</option>
                <option value="Rogue (Sub)">Rogue (Sub)</option>
                <option value="Mage (Frost)">Mage (Frost)</option>
                <!-- Add more classes and specs here -->
            </select>

            <label for="match_data">Match History (format: Class1 (Spec1) - Win/Loss, Class2 (Spec2) - Win/Loss):</label>
            <textarea name="match_data" id="match_data" rows="10" placeholder="e.g., Druid (Resto) - Win, Rogue (Sub) - Loss, Mage (Frost) - Win" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
