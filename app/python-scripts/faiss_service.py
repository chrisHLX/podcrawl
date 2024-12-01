import faiss
import numpy as np

# Example FAISS setup
dimension = 128  # Change based on your embedding size
index = faiss.IndexFlatL2(dimension)

def add_embeddings(embeddings):
    index.add(np.array(embeddings, dtype='float32'))

def search(query_embedding, k=5):
    return index.search(np.array([query_embedding], dtype='float32'), k)
