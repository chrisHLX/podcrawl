from sentence_transformers import SentenceTransformer
from sklearn.cluster import KMeans

# Load pre-trained sentence embedding model
model = SentenceTransformer('sentence-transformers/all-MiniLM-L6-v2')

# Load chunks from JSON file
import json
with open('chunks.json', 'r') as f:
    chunks = json.load(f)

# Create embeddings
embeddings = model.encode(chunks)

# Define the number of clusters (topics)
num_clusters = 5  # Adjust based on your dataset
kmeans = KMeans(n_clusters=num_clusters, random_state=42)
labels = kmeans.fit_predict(embeddings)

# Group chunks by their cluster label
clustered_chunks = {i: [] for i in range(num_clusters)}
for i, label in enumerate(labels):
    clustered_chunks[label].append(chunks[i])

# Save results
results = {
    "topics": [
        {"topic_id": cluster, "keywords": keywords, "chunks": texts}
        for cluster, (keywords, texts) in enumerate(zip(topic_keywords.values(), clustered_chunks.values()))
    ]
}

with open('topics.json', 'w') as f:
    json.dump(results, f)
