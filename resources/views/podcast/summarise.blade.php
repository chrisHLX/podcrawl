<x-app-layout>
<div class="container">
    <h1>Transcript Editor</h1>
    <h2>Chunk ID: {{ $chunkID }}</h2>
    <!-- using decodeURIComponent to convert the chunk to a uri safe format, this is how we bypass the issues with special characters -->
    <div x-data="transcriptEditor(decodeURIComponent('{{ $chunk }}'), {{ $chunkID }})">
        <div class="chunk">
            <p x-text="chunk.text"></p>
            <div class="actions">
                <button @click="summariseChunk('simple')">Simple Summary</button>
                <button @click="summariseChunk('detailed')">Detailed Summary</button>
            </div>
            <textarea x-show="chunk.summary" x-model="chunk.summary" class="summary-box"></textarea>
            <button @click="saveSummary">Save Summary</button>
        </div>
    </div>
</div>
<script>
function transcriptEditor(initialChunk, chunkId) {
    return {
        chunk: { text: initialChunk, summary: '', id: chunkId },

        async summariseChunk(type) {
            try {
                const response = await fetch('{{ route('summarise.chunk') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ chunk: this.chunk.text, summaryType: type })
                });

                const data = await response.json();
                this.chunk.summary = data.summary;
            } catch (error) {
                console.error('Error summarizing chunk:', error);
            }
        },

        async saveSummary() {
            try {
                const response = await fetch('{{ route('summary.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        tchunks_id: this.chunk.id, // Pass the tchunk ID
                        summary_text: this.chunk.summary,
                        model: 'gpt-3.5-turbo' // You can pass this dynamically if needed
                    })
                });

                const data = await response.json();
                if (data.success) {
                    alert('Summary saved successfully!');
                } else {
                    alert('Failed to save summary.');
                }
            } catch (error) {
                console.error('Error saving summary:', error);
            }
        }
    };
}
</script>

<style>
.chunk {
    margin-bottom: 20px;
}
.summary-box {
    width: 100%;
    height: 1000px;
    margin-top: 10px;
}
</style>

</x-app-layout>
