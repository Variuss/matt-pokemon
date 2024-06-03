<?php

declare(strict_types=1);

namespace Matt\Pokemon\HttpRemote\Response;

class PokeResponse
{
    private CONST NAME = "name";
    private CONST IMG_URL = "imgUrl";
    private CONST MESSAGE = "message";
    private string $name;

    private string $imgUrl;
    private string $message;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    /**
     * @param string $imgUrl
     */
    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::NAME => $this->name,
            self::IMG_URL => $this->imgUrl,
            self::MESSAGE => $this->message,
        ];
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        $data = [
            self::NAME => $this->name,
            self::IMG_URL => $this->imgUrl,
            self::MESSAGE => $this->message,
        ];

        return json_encode($data);
    }
}
