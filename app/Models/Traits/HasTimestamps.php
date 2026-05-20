<?php
namespace App\Models\Traits;

trait HasTimestamps {
    protected string $createdAt;

    public function initTimestamps(): void {
        $this->createdAt = date('Y-m-d H:i:s');
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }
}
