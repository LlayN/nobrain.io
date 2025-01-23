<?php


namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Stats
{
  #[MongoDB\Id]
  protected string $id;

  #[MongoDB\Field(type: 'string')]
  protected string $name;

  #[MongoDB\Field(type: 'int')]
  protected int $score;

  #[MongoDB\Field(type: 'int')]
  protected int $answers;

  public function setName(string $name): void
  {
    $this->name = $name;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setScore(int $score): void
  {
    $this->score = $score;
  }

  public function getScore(): int
  {
    return $this->score;
  }

  public function setAnswers(int $answers): void
  {
    $this->answers = $answers;
  }

  public function getAnswers(): int
  {
    return $this->answers;
  }
}
