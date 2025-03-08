<?php
namespace App\Mapper\Entity;
use App\Entity\Film;
use App\Enum\Genres;
use App\Model\Response\Entity\Film\FilmDetail;
use App\Model\Response\Entity\Film\FilmForm;
use App\Model\Response\Entity\Film\FilmList;
use App\Model\Response\Entity\Film\FilmListItem;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Assessment;
use App\Entity\User;
use App\Entity\Person;
class FilmMapper
{
  public function __construct(
    private TranslatorInterface $translator,
  ) {
  }

  public function mapToEntityList(array $films): FilmList
  {
    $items = array_map(
      fn(Film $film) => $this->mapToEntityListItem($film, new FilmListItem($film->getId())),
      $films
    );

    return new FilmList(array_values($items));
  }

  public function mapToEntityListItem(Film $film, FilmListItem $model): FilmListItem
  {
    return $model
      ->setId($film->getId())
      ->setName($film->getName())
      ->setReleaseYear($film->getReleaseYear())
      ->setCover($film->getCover())
    ;
  }
  public function mapToDetail(Film $film, FilmDetail $model, string $locale = 'ru'): FilmDetail
  {
    return $model
      ->setId($film->getId())
      ->setName($film->getName())
      ->setSlogan($film->getSlogan())
      ->setGenreIds($this->mapGenresToIds($film->getGenres()))
      ->setGenreNames(array_map(fn(Genres $genre) => $genre->trans($this->translator, $locale), $film->getGenres()))
      ->setReleaseYear($film->getReleaseYear())
      ->setDescription($film->getDescription())
      ->setRating($film->getRating() ?? 0.0)
      ->setAge($film->getAge())
      ->setDuration($this->setFormattedDuration($film->getDuration()))
      ->setCover($film->getCover() ?: '')
      ->setAssessments($this->mapAssessments($film->getAssessments()->toArray()))
      ->setRating(number_format($film->getRating(), 1) ?? 0.0)
      ->setPublisherData($film->getPublisher() ? $this->mapPublisherData($film->getPublisher()) : [])
      ->setActorsData($film->getActors() ? $this->mapPersonsData($film->getActors()->toArray()) : [])
      ->setTeamData($this->mapFilmTeam($film))
      ->setCreatedAt($film->getCreatedAt()->format('Y-m-d'))
      ->setUpdatedAt($film->getUpdatedAt()->format('Y-m-d'))
    ;
  }

  private function setFormattedDuration($duration): string
  {
    return sprintf('%02d:%02d', $duration->format('H'), $duration->format('i'));
  }

  public function mapToForm(Film $film, FilmForm $model): FilmForm
  {
    return $model
      ->setId($film->getId())
      ->setSlogan($film->getSlogan())
      ->setName($film->getName())
      ->setGenreIds($this->mapGenresToIds($film->getGenres()))
      ->setReleaseYear($film->getReleaseYear())
      ->setActorIds($this->getActorsIds($film))
      ->setDirectorId($film->getDirectedBy() ? $film->getDirectedBy()->getId() : null)
      ->setWriterId($film->getWriter() ? $film->getWriter()->getId() : null)
      ->setProducerId($film->getProducer() ? $film->getProducer()->getId() : null)
      ->setComposerId($film->getComposer() ? $film->getComposer()->getId() : null)
      ->setCover($film->getCover() ?: '')
      ->setDuration($this->setFormattedDuration($film->getDuration()))
      ->setDescription($film->getDescription())
      ->setAge($film->getAge())
    ;
  }

  public function mapToListItem(Film $film): FilmListItem
  {
    return new FilmListItem(
      $film->getId(),
      $film->getName(),
      $film->getReleaseYear(),
      $film->getCover() ? $film->getCover() : '',
      $film->getDescription(),
      $film->getRating(),
      $this->mapAssessments($film->getAssessments()->toArray())

    );
  }
  private function getActorNames(Film $film): array
  {
    $actorsIds = $this->getActorsIds($film);

    $namesArr = [];
    for ($i = 0; $i < count($actorsIds); $i++) {
      $namesArr[] = $film->getActors()[$i]->getFullname();
    }

    return $namesArr;
  }

  private function mapGenresToIds(array $genres): array
  {
    $ids = [];
    foreach ($genres as $genre) {
      $ids[] = $genre->value;
    }

    return $ids;
  }

  private function getActorAvatars(Film $film): array
  {
    $avatarsArr = [];
    foreach ($film->getActors() as $actor) {
      $avatarsArr[] = $actor->getAvatar();
    }

    return $avatarsArr;
  }

  private function getActorsIds(Film $film): array
  {
    $idsArr = [];
    foreach ($film->getActors() as $actor) {
      $idsArr[] = $actor->getId();
    }

    return $idsArr;
  }

  private function mapAssessments(array $assessments): array
  {
    return array_map(
      function (Assessment $assessment) {
        return [
          'id' => $assessment->getId(),
          'authorId' => $assessment->getAuthor()->getId(),
          'authorName' => $assessment->getAuthor()->getDisplayName(),
          'authorAvatar' => $assessment->getAuthor()->getAvatar(),
          'comment' => $assessment->getComment(),
          'rating' => $assessment->getRating(),
          'createdAt' => $assessment->getCreatedAt(),
        ];
      },
      $assessments
    );
  }

  private function mapPublisherData(User $publisher): array
  {
    return [
      'id' => $publisher->getId(),
      'name' => $publisher->getDisplayName(),
    ];
  }

  private function mapPersonsData(array $actors): array
  {
    return array_map(
      function (Person $actor) {
        return [
          'id' => $actor->getId(),
          'name' => $actor->getFullname(),
          'avatar' => $actor->getAvatar(),
        ];
      },
      $actors
    );
  }

  private function mapFilmTeam(Film $film): array
  {

    $directorData = [];
    if ($film->getDirectedBy()) {
      $directorData = [
        'id' => $film->getDirectedBy()->getId() ?? null,
        'name' => $film->getDirectedBy()->getFullName() ?? null,
        'avatar' => $film->getDirectedBy()->getAvatar() ?? null,
      ];
    }

    $writerData = [];
    if ($film->getWriter()) {
      $writerData = [
        'id' => $film->getWriter()->getId() ?? null,
        'name' => $film->getWriter()->getFullName() ?? null,
        'avatar' => $film->getWriter()->getAvatar() ?? null
      ];
    }

    $producerData = [];
    if ($film->getProducer()) {
      $producerData = [
        'id' => $film->getProducer()->getId() ?? null,
        'name' => $film->getProducer()->getFullName() ?? null, 
        'avatar' => $film->getProducer()->getAvatar() ?? null,
      ];
    }

    $composerData = [];
    if ($film->getComposer()) {
      $composerData = [
        'id' => $film->getComposer()->getId() ?? null,
        'name' => $film->getComposer()->getFullName() ?? null,
        'avatar' => $film->getComposer()->getAvatar() ?? null,
      ];
    }

    return array_map(
      function ($data) {
        return [
          'id' => $data['id'] ?? null,
          'name' => $data['name'] ?? null,
          'avatar' => $data['avatar'] ?? null,
        ];
      },
      [$directorData, $writerData, $producerData, $composerData]
    );
  }

}