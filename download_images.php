<?php

$mangroveImages = [
    'avicennia-marina.jpg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Avicennia_marina_tree.jpg/800px-Avicennia_marina_tree.jpg',
    'rhizophora-mucronata.jpg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7d/Rhizophora_mucronata_02.jpg/800px-Rhizophora_mucronata_02.jpg',
    'sonneratia-alba.jpg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Sonneratia_alba_fruit.jpg/800px-Sonneratia_alba_fruit.jpg',
    'bruguiera-gymnorrhiza.jpg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/74/Bruguiera_gymnorrhiza_fruit.jpg/800px-Bruguiera_gymnorrhiza_fruit.jpg',
    'ceriops-tagal.jpg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Ceriops_tagal_01.jpg/800px-Ceriops_tagal_01.jpg',
    'aegiceras-corniculatum.jpg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Aegiceras_corniculatum_02.jpg/800px-Aegiceras_corniculatum_02.jpg'
];

$targetDir = __DIR__ . '/public/images/mangroves/';

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Create a stream context that disables SSL verification
$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
    'http' => [
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ]
]);

foreach ($mangroveImages as $filename => $url) {
    $targetPath = $targetDir . $filename;
    if (!file_exists($targetPath)) {
        try {
            $imageData = file_get_contents($url, false, $context);
            if ($imageData !== false) {
                file_put_contents($targetPath, $imageData);
                echo "Downloaded: $filename\n";
            } else {
                echo "Failed to download: $filename\n";
            }
        } catch (Exception $e) {
            echo "Error downloading $filename: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Skipped existing: $filename\n";
    }
} 