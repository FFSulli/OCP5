<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Interfaces\EntityObjectInterface;

final class Post
{
    private int $id = 1;
    private string $title = "";
    private string $excerpt;
    private string $content;
    private string $slug;
    private string $created_at;
    private string $updated_at;
    private int $user_fk;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    /**
     * @param string $excerpt
     * @return Post
     */
    public function setExcerpt(string $excerpt): Post
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Post
     */
    public function setContent(string $content): Post
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Post
     */
    public function setSlug(string $slug): Post
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     * @return Post
     */
    public function setCreatedAt(string $created_at): Post
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     * @return Post
     */
    public function setUpdatedAt(string $updated_at): Post
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserFk(): int
    {
        return $this->user_fk;
    }

    /**
     * @param int $user_fk
     * @return Post
     */
    public function setUserFk(int $user_fk): Post
    {
        $this->user_fk = $user_fk;
        return $this;
    }

    /**
     * @param $data
     * @return static
     */
    public static function fromArray($data): self
    {
        $self = new self();

        if (isset($data["title"]))
        {
            $self->setTitle($data["title"]);
        }

        if (isset($data["excerpt"]))
        {
            $self->setExcerpt($data["excerpt"]);
        }

        if (isset($data["content"]))
        {
            $self->setContent($data["content"]);
        }

        if (isset($data["created_at"]))
        {
            $self->setCreatedAt($data["created_at"]);
        }

        if (isset($data["updated_at"]))
        {
            $self->setUpdatedAt($data["updated_at"]);
        }

        if (isset($data["user_fk"]))
        {
            $self->setUserFk($data["user_fk"]);
        }

        return $self;
    }
}
