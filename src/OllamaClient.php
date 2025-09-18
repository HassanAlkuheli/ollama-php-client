<?php

namespace Sfyigit\Ollama;

use Exception;

/**
 * @property string $prompt       The input text prompt.
 * @property float  $temperature  Controls randomness in the model's output.
 * @property int    $max_tokens   Maximum number of tokens allowed in the response.
 */
class OllamaClient
{
    /**
     * @var string Base URL for the Ollama API
     */
    private string $baseUrl;

    /**
     * @var string Model name to use
     */
    private string $model;

    /**
     * @var string The input text prompt
     */
    public string $prompt = "";

    /**
     * @var float Controls randomness
     */
    public float $temperature = 0.7;

    /**
     * @var int Maximum tokens in response
     */
    public int $max_tokens = 200;

    /**
     * @var string Context for the request
     */
    public string $context = "";

    /**
     * @var array Chat history
     */
    private array $history = [];

    /**
     * @var array Images as base64 strings
     */
    private array $images = [];

    /**
     * Constructor: allow user to set baseUrl and model, otherwise use defaults
     *
     * @param string $baseUrl Base URL for the Ollama API
     * @param string $model Model name to use
     */
    public function __construct(string $baseUrl = "http://localhost:11434/api", string $model = "gemma3:4b")
    {
        $this->baseUrl = $baseUrl;
        $this->model = $model;
    }

    /**
     * Generate a response from the model using the current prompt and options.
     *
     * @return string|null The generated response, or null on failure
     */
    public function generate(): ?string
    {
        $payload = [
            "model"   => $this->model,
            "prompt"  => $this->prompt,
            "stream"  => false,
            "options" => [
                "temperature" => $this->temperature,
                "num_predict" => $this->max_tokens
            ]
        ];

        if (!empty($this->images)) {
            $payload["images"] = $this->images;
        }

        if (!empty($this->context)) {
            $payload["context"] = $this->context;
        }

        return $this->sendRequest("/generate", $payload);
    }

    /**
     * Send a chat message and get the assistant's response.
     *
     * @return string|null The assistant's response, or null on failure
     */
    public function chat(): ?string
    {
        $this->history[] = [
            "role"    => "user",
            "content" => $this->prompt
        ];

        $payload = [
            "model"    => $this->model,
            "stream"   => false,
            "messages" => $this->history,
            "options"  => [
                "temperature" => $this->temperature,
                "num_predict" => $this->max_tokens
            ]
        ];

        if (!empty($this->images)) {
            $payload["images"] = $this->images;
        }

        if (!empty($this->context)) {
            $payload["context"] = $this->context;
        }

        $response = $this->sendRequest("/chat", $payload);

        if ($response) {
            $this->history[] = [
                "role"    => "assistant",
                "content" => $response
            ];
        }

        return $response;
    }

    /**
     * Clear the chat history.
     *
     * @return void
     */
    public function clearHistory(): void
    {
        $this->history = [];
    }

    /**
     * Clear the images array.
     *
     * @return void
     */
    public function clearImages(): void
    {
        $this->images = [];
    }

    /**
     * Add an image to the request by file path.
     *
     * @param string $filePath Path to the image file
     * @return void
     * @throws Exception If the image file is not found
     */
    public function addImage(string $filePath): void
    {
        if (file_exists($filePath)) {
            $this->images[] = base64_encode(file_get_contents($filePath));
        } else {
            throw new Exception("Image not found: " . $filePath);
        }
    }

    /**
     * Send a POST request to the Ollama API.
     *
     * @param string $endpoint API endpoint
     * @param array<string, mixed> $payload Request payload
     * @return string|null The response string, or null on failure
     * @throws Exception On cURL error
     */
    private function sendRequest(string $endpoint, array $payload): ?string
    {
        $ch = curl_init($this->baseUrl . $endpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("Curl error: " . curl_error($ch));
        }

        curl_close($ch);

        $decoded = json_decode($response, true);

        if (isset($decoded["response"])) {
            return $decoded["response"];
        } elseif (isset($decoded["message"]["content"])) {
            return $decoded["message"]["content"];
        }

        return null;
    }
}
