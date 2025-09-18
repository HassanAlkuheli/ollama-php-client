# Ollama PHP Client

A simple PHP client library for [Ollama](https://ollama.ai) API.  
Supports `generate`, `chat`, history, context, and images.

## Requirements
First install and launch ollama and make it ready to serve. 

## Installation

```bash
composer require sfyigit/ollama-php-client


## Usage

<?php

require 'vendor/autoload.php';

use Sfyigit\Ollama\OllamaClient;

// 1️⃣ Default initialization (uses localhost & gemma3:4b)
$ollama = new OllamaClient();

// 2️⃣ Custom baseUrl and model
$ollamaCustom = new OllamaClient(
    "http://my-ollama-server:11434/api",
    "gemma3:4b"
);

// Set prompt and options
$ollamaCustom->prompt = "Explain OOP in one sentence.";
$ollamaCustom->temperature = 0.5;
$ollamaCustom->max_tokens = 100;
$ollamaCustom->context = "User is a beginner in programming.";

// Generate response
echo $ollamaCustom->generate();

// Chat with history
$ollamaCustom->prompt = "Hello again!";
echo $ollamaCustom->chat();

// Add image
$ollamaCustom->addImage("cat.jpg");
$ollamaCustom->prompt = "Describe this picture.";
echo $ollamaCustom->generate();

// Clear history or images
$ollamaCustom->clearHistory();
$ollamaCustom->clearImages();
