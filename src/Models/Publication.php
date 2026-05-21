<?php
namespace App\Models;

class Publication {
    private ?int $id;
    private int $userId;
    private string $text;
    private ?string $image;
    private ?string $audio;
    private ?string $username;
    private ?string $createdAt;
    private bool $isLiked = false;
    private bool $isFavorited = false;
    private bool $isDisliked = false;

    public function __construct(?int $id, int $userId, string $text, ?string $image = null, ?string $audio = null, ?string $username = null, ?string $createdAt = null) {
        $this->id = $id;
        $this->userId = $userId;
        $this->text = $text;
        $this->image = $image;
        $this->audio = $audio;
        $this->username = $username;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->userId;
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

    public function getUsername(): ?string {
        return $this->username;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    public function getIsLiked(): bool {
        return $this->isLiked;
    }

    public function setIsLiked(bool $isLiked): void {
        $this->isLiked = $isLiked;
    }

    public function getIsFavorited(): bool {
        return $this->isFavorited;
    }

    public function setIsFavorited(bool $isFavorited): void {
        $this->isFavorited = $isFavorited;
    }

    public function getIsDisliked(): bool {
        return $this->isDisliked;
    }

    public function setIsDisliked(bool $isDisliked): void {
        $this->isDisliked = $isDisliked;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'text' => $this->text,
            'image' => $this->image,
            'audio' => $this->audio,
            'username' => $this->username,
            'created_at' => $this->createdAt,
            'is_liked' => $this->isLiked,
            'is_favorited' => $this->isFavorited,
            'is_disliked' => $this->isDisliked
        ];
    }
}
