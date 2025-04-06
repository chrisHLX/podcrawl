import requests
import json

# Replace with your Hugging Face API token from your account settings
API_TOKEN = "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"
headers = {"Authorization": f"Bearer {API_TOKEN}"}

# Specify your model's API endpoint (using the model ID from its page)
api_url = "https://api-inference.huggingface.co/models/oliverguhr/fullstop-punctuation-multilang-large"

def query(payload):
    response = requests.post(api_url, headers=headers, json=payload)
    return response.json()

# Prepare your payload (this depends on the model's expected input)
data = {
    "inputs" : "i cant envision saying something like that about myself 3 56 you know can you imagine going out in front of a national audience and saying i a fighter i i i cant imagine anyone 4 02 calling you a leader. thats true"
}

# Send the request to the API
results = query(data)


print(json.dumps(results))
