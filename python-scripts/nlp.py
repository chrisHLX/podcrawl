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

def clean_transcript(transcript):
    """
    Cleans the transcript by removing timestamps and normalizing text.
    """
    timestamps = re.findall(r'\b\d{1,2}:\d{2}\b', transcript)
    text_without_timestamps = re.sub(r'\b\d{1,2}:\d{2}\b', '', transcript)
    cleaned_text = re.sub(r'-\s', '', text_without_timestamps).strip()
    return cleaned_text, timestamps

def split_into_topics(transcript):
    """
    Splits the transcript into topic-based chunks using spaCy.
    """
    doc = nlp(transcript)
    topics = []  # List to store all topics
    current_topic_content = []  # Temporary storage for content of the current topic
    current_topic_title = None  # Current topic title

    # Initialize PhraseMatcher to match known topics or keywords
    matcher = PhraseMatcher(nlp.vocab)
    keywords = ['Nietzsche', 'Jordan Peterson', 'Philosophy', 'Psychology']
    patterns = [nlp.make_doc(keyword) for keyword in keywords]
    matcher.add("TopicMatcher", None, *patterns)

    for sent in doc.sents:
        matches = matcher(sent)
        topic_title = None

        # Match with known keywords
        for match_id, start, end in matches:
            topic_title = nlp.vocab.strings[match_id]
            break

        # Fallback to NER
        if not topic_title:
            for ent in sent.ents:
                if ent.label_ in ['PERSON', 'ORG', 'GPE']:
                    topic_title = ent.text
                    break

        # Fallback to generic label
        if not topic_title:
            topic_title = "General Discussion"

        # If a new topic is detected, save the current topic and reset
        if topic_title != current_topic_title and current_topic_content:
            topics.append({
                "title": current_topic_title or "General Discussion",
                "content": " ".join(current_topic_content),
            })
            current_topic_content = []

        current_topic_content.append(sent.text)
        current_topic_title = topic_title

    # Add the last topic if exists
    if current_topic_content:
        topics.append({
            "title": current_topic_title or "General Discussion",
            "content": " ".join(current_topic_content),
        })

    return topics


def append_timestamps(topics, timestamps):
    result = []
    timestamp_index = 0
    for topic in topics:
        topic["timestamp"] = timestamps[timestamp_index] if timestamp_index < len(timestamps) else None
        timestamp_index += 1
        result.append(topic)
    return result

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
    topics = split_into_topics(cleaned_text)
    topics_with_timestamps = append_timestamps(topics, timestamps)

    # Output the result as JSON
    print(json.dumps(topics_with_timestamps, indent=4))
