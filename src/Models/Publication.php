<?php
namespace App\Models;

class Publication {
    private int $id;
    private string $text;
    private ?string $image;
    private ?string $audio;

    public function __construct(int $id, string $text, ?string $image = null, ?string $audio = null) {
        $this->id = $id;
        $this->text = $text;
        $this->image = $image;
        $this->audio = $audio;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getText(): string {
        return $this->text;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function getAudio(): ?string {
        return $this->audio;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'image' => $this->image,
            'audio' => $this->audio
        ];
    }
}
