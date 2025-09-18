<?php

namespace Sfyigit\Ollama;

use Exception;

class OllamaClient
{
    private string $baseUrl;
    private string $model;

    public string $prompt = "";
    public float $temperature = 0.7;
    public int $max_tokens = 200;
    public string $context = "";

    private array $history = [];
    private array $images = [];

    /**
     * Constructor: allow user to set baseUrl and model, otherwise use defaults
     */
    public function __construct(string $baseUrl = "http://localhost:11434/api", string $model = "gemma3:4b")
    {
        $this->baseUrl = $baseUrl;
        $this->model = $model;
    }

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

    public function clearHistory(): void
    {
        $this->history = [];
    }

    public function clearImages(): void
    {
        $this->images = [];
    }

    public function addImage(string $filePath): void
    {
        if (file_exists($filePath)) {
            $this->images[] = base64_encode(file_get_contents($filePath));
        } else {
            throw new Exception("Image not found: " . $filePath);
        }
    }

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
