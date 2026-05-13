import os
import sys
import requests


def generate_summary(diff_text: str, current_body: str, api_key: str, model: str = "gemini-3-flash-preview:cloud") -> str:
    url = os.getenv("OLLAMA_API_URL", "https://api.ollama.com/api/generate")

    prompt = f"""You are a senior software engineer. Please review the following git diff and generate a Pull Request description.

Current PR Body (if any):
{current_body}

Git Diff:
{diff_text}

Please generate a Pull Request description that follows this exact format:

## What does this change?
[Provide a detailed explanation of WHAT the problem was and HOW this change solves it. Focus on the 'why' and 'how'.]

## Asana / Jira / Trello task link
<!-- Provide Asana / Jira / Trello task link here -->

## How to test
[Provide step-by-step instructions to help others verify the change. Suggest specific tests based on the modified files.]

## Potential Risks & Senior Review Items
[Identify potential side effects, performance implications, security considerations, or architectural concerns. Highlight specific areas where a senior engineer should focus their review.]

## Is this PR warrant an automatic approval?
[Yes/No. Provide a brief justification based on the complexity and risk of the changes.]

## Images
<!-- Usually only applicable to UI changes, what did it look like before and what will it look like after? -->

Important:
- If the 'Current PR Body' already contains information (like task links or images), PRESERVE them in the new summary.
- Fill in the 'What does this change?', 'How to test', 'Potential Risks & Senior Review Items', and 'Is this PR warrant an automatic approval?' sections based on the provided diff.
- Keep the other sections exactly as shown (with their HTML comments/placeholders) so the user can fill them in manually if needed.
- Return ONLY the markdown content.
"""

    headers = {
        "Authorization": f"Bearer {api_key}",
        "Content-Type": "application/json",
    }

    data = {
        "model": model,
        "prompt": prompt,
        "stream": False,
    }

    try:
        response = requests.post(url, headers=headers, json=data, timeout=90)
        response.raise_for_status()
        result = response.json()
        return result.get("response", "Could not generate summary.")
    except requests.exceptions.RequestException as e:
        return f"Error calling Ollama API: {e!s}"


def main() -> int:
    if len(sys.argv) < 2:
        print("Usage: python generate_pr_summary.py <diff_file> [current_body_file]")
        return 1

    diff_file = sys.argv[1]
    current_body_file = sys.argv[2] if len(sys.argv) > 2 else None

    api_key = os.getenv("OLLAMA_API_KEY")
    if not api_key:
        print("Error: OLLAMA_API_KEY environment variable not set.")
        return 1

    if not os.path.exists(diff_file):
        print(f"Error: File {diff_file} not found.")
        return 1

    with open(diff_file, encoding="utf-8") as f:
        diff_text = f.read()

    current_body = ""
    if current_body_file and os.path.exists(current_body_file):
        with open(current_body_file, encoding="utf-8") as f:
            current_body = f.read()

    # Limit diff size to avoid token limits
    if len(diff_text) > 50000:
        diff_text = diff_text[:50000] + "\n\n... (diff truncated for size) ..."

    summary = generate_summary(diff_text, current_body, api_key)
    print(summary)
    return 0


if __name__ == "__main__":
    sys.exit(main())
