<?php 
namespace App\Traits;
use Doctrine\ORM\Mapping as ORM;
trait UpdatedAtTrait {
    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

}