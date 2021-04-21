<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Interfaces\EntityObjectInterface;

final class Post
{
    private int $id;
    private string $title;
    private string $excerpt;
    private string $content;
    private string $slug;
    private \DateTimeImmutable $createdAt;
    private \DateTime $updatedAt;
    private int $user_fk;

    public function __construct(int $id, string $title, string $excerpt, string $content, string $slug, \DateTimeImmutable $createdAt, \DateTime $updatedAt, int $user_fk)
    {
        $this->id = $id;
        $this->title = $title;
        $this->excerpt = $excerpt;
        $this->content = $content;
        $this->slug = $slug;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->user_fk = $user_fk;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

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
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return Post
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): Post
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Post
     */
    public function setUpdatedAt(\DateTime $updatedAt): Post
    {
        $this->updatedAt = $updatedAt;
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
}
