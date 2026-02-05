<?php

namespace App\DTOs;

use JsonSerializable;

final readonly class MoveLocation implements JsonSerializable
{
    public const string TYPE_STOCK = 'stock';

    public const string TYPE_WASTE = 'waste';

    public const string TYPE_FOUNDATION = 'foundation';

    public const string TYPE_TABLEAU = 'tableau';

    public function __construct(
        public string $type,
        public int|string|null $index = null,
    ) {}

    public function isStock(): bool
    {
        return $this->type === self::TYPE_STOCK;
    }

    public function isWaste(): bool
    {
        return $this->type === self::TYPE_WASTE;
    }

    public function isFoundation(): bool
    {
        return $this->type === self::TYPE_FOUNDATION;
    }

    public function isTableau(): bool
    {
        return $this->type === self::TYPE_TABLEAU;
    }

    public static function stock(): self
    {
        return new self(type: self::TYPE_STOCK);
    }

    public static function waste(): self
    {
        return new self(type: self::TYPE_WASTE);
    }

    public static function foundation(string $suit): self
    {
        return new self(type: self::TYPE_FOUNDATION, index: $suit);
    }

    public static function tableau(int $index): self
    {
        return new self(type: self::TYPE_TABLEAU, index: $index);
    }

    /**
     * @param  array{type: string, index?: int|string|null}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            index: $data['index'] ?? null,
        );
    }

    /**
     * @return array{type: string, index: int|string|null}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'index' => $this->index,
        ];
    }

    /**
     * @return array{type: string, index: int|string|null}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
