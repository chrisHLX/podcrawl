import os
import spacy
import re

# Get the directory of the current script (python-scripts)
script_dir = os.path.dirname(os.path.abspath(__file__))

# Build the absolute path to the transcript file (adjust the relative path as needed)
file_path = os.path.join(script_dir, '..', 'storage', 'app', 'tran_temp.txt')

# Load spaCy's English model
nlp = spacy.load("en_core_web_sm")

# Read the transcript
with open(file_path, "r", encoding="utf-8") as file:
    text = file.read()

# Replace all types of whitespace with a single space
clean_text = re.sub(r'\s+', ' ', text).strip()

# Process the text
doc = nlp(clean_text)

# Collect the sentences inferred by spaCy
sentences = [sent.text.strip() for sent in doc.sents]

# Add a period at the end of each sentence and capitalize the first word
punctuated_sentences = [sent.capitalize() + '.' for sent in sentences]
punctuated_text = " ".join(punctuated_sentences)

# Build the output path similarly
output_path = os.path.join(script_dir, '..', 'storage', 'app', 'new_tran.txt')
with open(output_path, "w", encoding="utf-8") as file:
    file.write(punctuated_text)

print(punctuated_text)
