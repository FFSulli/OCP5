<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Interfaces\EntityObjectInterface;

final class Comment
{
    private int $id = 1;
    private string $content;
    private bool $verified;
    private string $created_at;
    private int $user_fk;
    private int $post_fk;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Comment
     */
    public function setId(int $id): Comment
    {
        $this->id = $id;
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
     * @return Comment
     */
    public function setContent(string $content): Comment
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * @param bool $verified
     * @return Comment
     */
    public function setVerified(bool $verified): Comment
    {
        $this->verified = $verified;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     * @return Comment
     */
    public function setCreatedAt(string $created_at): Comment
    {
        $this->created_at = $created_at;
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
     * @return Comment
     */
    public function setUserFk(int $user_fk): Comment
    {
        $this->user_fk = $user_fk;
        return $this;
    }

    /**
     * @return int
     */
    public function getPostFk(): int
    {
        return $this->post_fk;
    }

    /**
     * @param int $post_fk
     * @return Comment
     */
    public function setPostFk(int $post_fk): Comment
    {
        $this->post_fk = $post_fk;
        return $this;
    }
}
