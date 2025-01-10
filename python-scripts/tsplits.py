import sys
import json
import re
import spacy
import nltk
from spacy.matcher import PhraseMatcher

nltk.download('punkt')
from nltk.tokenize import sent_tokenize

# Initialize spaCy NLP model
nlp = spacy.load("en_core_web_sm")

def split_by_speaker_changes(transcript):
    """
    Splits the transcript into chunks based on speaker changes, 
    indicated by a dash surrounded by spaces.
    """
    # Split the transcript based on ' - ' while preserving the delimiter
    chunks = re.split(r' - ', transcript)
    
    # Strip any leading or trailing whitespace from each chunk
    # chunks = [chunk.strip() for chunk in chunks if chunk.strip()]
    
    return chunks


def clean_transcript(transcript):
    """
    Cleans the transcript by removing timestamps and normalizing text.
    """
    # Updated regex for timestamps with optional hours
    timestamps = re.findall(r'\b(?:\d{1,2}:)?\d{1,2}:\d{2}\b', transcript)
    # Remove timestamps from transcript
    text_without_timestamps = re.sub(r'\b(?:\d{1,2}:)?\d{1,2}:\d{2}\b', '', transcript)
    # Normalize text
    cleaned_text = re.sub(r'-\s', '', text_without_timestamps).strip()
    return cleaned_text, timestamps

if __name__ == "__main__":
    # Check for a file path in the command-line arguments
    if len(sys.argv) < 2:
        print("Error: No file path provided. Usage: python nlp.py <file_path>")
        sys.exit(1)

    file_path = sys.argv[1]

    # Read the transcript from the file
    try:
        with open(file_path, 'r', encoding='utf-8') as file:
            raw_transcript = file.read()
    except FileNotFoundError:
        print(f"Error: File not found at path {file_path}")
        sys.exit(1)
    except Exception as e:
        print(f"Error reading file: {e}")
        sys.exit(1)

    # Process the transcript
    cleaned_text, timestamps = clean_transcript(raw_transcript)
    splits = split_by_speaker_changes(raw_transcript)

    # Output the result as JSON
    print(json.dumps(splits, indent=4))
