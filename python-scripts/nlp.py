import re
from deepmultilingualpunctuation import PunctuationModel

model = PunctuationModel()
# Define the temporary transcript file path
file_path = "storage/app/temp_transcript.txt"  # Same path as Laravel

# Read the transcript
with open(file_path, "r", encoding="utf-8") as file:
    text = file.read()

# Write summary back to file (optional)
result = model.restore_punctuation(text)

clean_text = re.sub(r'[\(\[\{\"\',\.\-]*(\b(?:\d{1,2}:)?\d{1,2}:\d{2}\b)[\)\]\}\"\',\.\-]*', r'\1', result)

output_path = "storage/app/processed_transcript.txt"

with open(output_path, "w", encoding="utf-8") as file:
    file.write(result)
    
print(clean_text)
