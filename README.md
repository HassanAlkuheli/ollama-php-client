# Ollama PHP Client# Ollama PHP Client



[![Packagist Version](https://img.shields.io/packagist/v/sfyigit/ollama-php-client)](https://packagist.org/packages/sfyigit/ollama-php-client)A simple PHP client library for [Ollama](https://ollama.ai) API.  

[![PHP Version](https://img.shields.io/packagist/php-v/sfyigit/ollama-php-client)](https://packagist.org/packages/sfyigit/ollama-php-client)Supports `generate`, `chat`, history, context, and images.

[![License](https://img.shields.io/packagist/l/sfyigit/ollama-php-client)](https://github.com/sfyigit/ollama-php-client/blob/main/LICENSE)

## Requirements

A simple and powerful PHP client library for [Ollama](https://ollama.ai) API that makes it easy to integrate local AI models into your PHP applications.First install and launch ollama and make it ready to serve. 



## 🚀 Features## Installation



- ✅ **Text Generation** - Generate responses using local AI models```bash

- ✅ **Chat Conversations** - Maintain chat history for conversational AIcomposer require sfyigit/ollama-php-client

- ✅ **Image Analysis** - Process images with vision models

- ✅ **Context Support** - Provide context for better responses

- ✅ **Customizable Options** - Control temperature, token limits, and more## Usage

- ✅ **Simple API** - Easy-to-use interface with sensible defaults

- ✅ **Error Handling** - Comprehensive exception handling<?php

- ✅ **Flexible Image Input** - Support for local files and URLs

require 'vendor/autoload.php';

## 📋 Requirements

use Sfyigit\Ollama\OllamaClient;

- PHP 7.4 or higher

- cURL extension enabled// 1️⃣ Default initialization (uses localhost & gemma3:4b)

- [Ollama](https://ollama.ai) installed and running$ollama = new OllamaClient();



### Setting up Ollama// 2️⃣ Custom baseUrl and model

$ollamaCustom = new OllamaClient(

1. Install Ollama from [https://ollama.ai](https://ollama.ai)    "http://my-ollama-server:11434/api",

2. Start the Ollama service:    "gemma3:4b"

   ```bash);

   ollama serve

   ```// Set prompt and options

3. Pull a model (e.g., gemma3):$ollamaCustom->prompt = "Explain OOP in one sentence.";

   ```bash$ollamaCustom->temperature = 0.5;

   ollama pull gemma3:4b$ollamaCustom->max_tokens = 100;

   ```$ollamaCustom->context = "User is a beginner in programming.";



## 📦 Installation// Generate response

echo $ollamaCustom->generate();

Install via Composer:

// Chat with history

```bash$ollamaCustom->prompt = "Hello again!";

composer require sfyigit/ollama-php-clientecho $ollamaCustom->chat();

```

// Add image

## 🔧 Quick Start$ollamaCustom->addImage("cat.jpg");

$ollamaCustom->prompt = "Describe this picture.";

```phpecho $ollamaCustom->generate();

<?php

// Clear history or images

require 'vendor/autoload.php';$ollamaCustom->clearHistory();

$ollamaCustom->clearImages();

use Sfyigit\Ollama\OllamaClient;

// Initialize client
$ollama = new OllamaClient();

// Generate a simple response
$ollama->prompt = "What is PHP?";
echo $ollama->generate();
```

## 📖 Usage Examples

### Basic Text Generation

```php
<?php

require 'vendor/autoload.php';

use Sfyigit\Ollama\OllamaClient;

// Default initialization (uses localhost:11434 & gemma3:4b)
$ollama = new OllamaClient();

// Set your prompt
$ollama->prompt = "Explain object-oriented programming in simple terms.";

// Configure generation parameters
$ollama->temperature = 0.7;  // Controls creativity (0.0 - 1.0)
$ollama->max_tokens = 150;   // Maximum response length

// Generate response
$response = $ollama->generate();
echo $response;
```

### Custom Server and Model

```php
// Connect to custom Ollama server with specific model
$ollama = new OllamaClient(
    "http://my-ollama-server:11434/api",
    "llama2:7b"
);

$ollama->prompt = "Write a haiku about programming.";
echo $ollama->generate();
```

### Chat Conversations

```php
$ollama = new OllamaClient();

// Start a conversation
$ollama->prompt = "Hi, I'm learning PHP. Can you help me?";
echo "Bot: " . $ollama->chat() . "\n";

// Continue the conversation (history is maintained)
$ollama->prompt = "What are PHP namespaces?";
echo "Bot: " . $ollama->chat() . "\n";

// Clear history when needed
$ollama->clearHistory();
```

### Image Analysis

```php
$ollama = new OllamaClient("http://localhost:11434/api", "llava:7b");

// Add image from local file
$ollama->addImage("/path/to/image.jpg");

// Or add image from URL
$ollama->addImage("https://example.com/image.png");

// Ask about the image
$ollama->prompt = "What do you see in this image?";
echo $ollama->generate();

// Clear images when done
$ollama->clearImages();
```

### Using Context

```php
$ollama = new OllamaClient();

// Provide context for better responses
$ollama->context = "You are a helpful PHP mentor teaching a beginner.";
$ollama->prompt = "How do I connect to a database?";

echo $ollama->generate();
```

### Advanced Configuration

```php
$ollama = new OllamaClient();

// Configure all parameters
$ollama->prompt = "Explain RESTful APIs";
$ollama->temperature = 0.3;     // More focused responses
$ollama->max_tokens = 300;      // Longer responses
$ollama->context = "You are explaining to a backend developer.";

$response = $ollama->generate();
```

## 🎛️ Configuration Options

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `prompt` | string | `""` | The input text prompt |
| `temperature` | float | `0.7` | Controls randomness (0.0-1.0) |
| `max_tokens` | int | `200` | Maximum tokens in response |
| `context` | string | `""` | Additional context for the AI |

## 🔄 Available Methods

### Core Methods

- `generate()` - Generate a one-off response
- `chat()` - Send a chat message with history
- `addImage(string $pathOrUrl)` - Add image for analysis
- `clearHistory()` - Clear chat history
- `clearImages()` - Clear added images

### Constructor

```php
new OllamaClient(string $baseUrl = "http://localhost:11434/api", string $model = "gemma3:4b")
```

## ⚠️ Error Handling

The client throws exceptions for various error conditions:

```php
try {
    $ollama = new OllamaClient();
    $ollama->addImage("/nonexistent/image.jpg");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Ollama](https://ollama.ai) for providing the excellent local AI platform
- The PHP community for continuous support and inspiration

## 📞 Support

If you encounter any issues or have questions:

1. Check the [Issues](https://github.com/sfyigit/ollama-php-client/issues) page
2. Create a new issue if your problem isn't already reported
3. Provide as much detail as possible including PHP version, Ollama version, and error messages